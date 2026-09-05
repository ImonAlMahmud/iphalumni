<?php
declare(strict_types=1);

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class UddoktaPayService
{
    protected string $apiKey;
    protected string $apiUrl;
    protected string $mode;
    protected string $webhookSecret;

    public function __construct()
    {
        $settingModel = new Setting();

        // 1. Check DB Settings first, then fallback to config/env
        $dbApiKey = (string) $settingModel->get('uddoktapay_api_key', '');
        $this->apiKey = !empty($dbApiKey) ? trim($dbApiKey) : (string) config('services.uddoktapay.api_key', env('UDDOKTAPAY_API_KEY', ''));

        $dbSecret = (string) $settingModel->get('uddoktapay_webhook_secret', '');
        $this->webhookSecret = !empty($dbSecret) ? trim($dbSecret) : (string) config('services.uddoktapay.webhook_secret', env('UDDOKTAPAY_WEBHOOK_SECRET', ''));

        $dbMode = (string) $settingModel->get('uddoktapay_mode', '');
        $this->mode = !empty($dbMode) ? trim($dbMode) : (string) config('services.uddoktapay.mode', env('UDDOKTAPAY_MODE', 'sandbox'));

        $defaultUrl = ($this->mode === 'live')
            ? 'https://pay.uddoktapay.com/api'
            : 'https://sandbox.uddoktapay.com/api';

        $dbApiUrl = (string) $settingModel->get('uddoktapay_api_url', '');
        $configuredUrl = !empty($dbApiUrl) ? trim($dbApiUrl) : (string) config('services.uddoktapay.api_url', env('UDDOKTAPAY_API_URL', ''));

        if (!empty($configuredUrl)) {
            $cleanUrl = preg_replace('/(\/checkout-v2|\/verify-payment|\/)$/', '', rtrim($configuredUrl, '/'));
            $this->apiUrl = $cleanUrl;
        } else {
            $this->apiUrl = $defaultUrl;
        }
    }

    /**
     * Check if UddoktaPay is properly configured
     */
    public function isConfigured(): bool
    {
        return !empty($this->apiKey);
    }

    public function getApiKey(): string
    {
        return $this->apiKey;
    }

    public function getApiUrl(): string
    {
        return $this->apiUrl;
    }

    public function getMode(): string
    {
        return $this->mode;
    }

    /**
     * Test connection to UddoktaPay API
     *
     * @param string|null $apiUrl
     * @param string|null $apiKey
     * @return array ['success' => bool, 'message' => string, 'details' => mixed]
     */
    public function testConnection(?string $apiUrl = null, ?string $apiKey = null): array
    {
        $key = !empty($apiKey) ? trim($apiKey) : $this->apiKey;
        $url = !empty($apiUrl) ? trim($apiUrl) : $this->apiUrl;

        if (empty($key)) {
            return [
                'success' => false,
                'message' => 'API Key প্রদান করা হয়নি। অনুগ্রহ করে আপনার UddoktaPay API Key দিন।',
            ];
        }

        if (empty($url)) {
            return [
                'success' => false,
                'message' => 'Base URL পাওয়া যায়নি। Sandbox: https://sandbox.uddoktapay.com/api অথবা Live: https://pay.uddoktapay.com/api ব্যবহার করুন।',
            ];
        }

        $cleanUrl = preg_replace('/(\/checkout-v2|\/verify-payment|\/)$/', '', rtrim($url, '/'));

        try {
            $endpoint = $cleanUrl . '/verify-payment';

            $response = Http::withHeaders([
                'RT-UDDOKTAPAY-API-KEY' => $key,
                'Content-Type'          => 'application/json',
                'Accept'                => 'application/json',
            ])->timeout(12)->post($endpoint, [
                'invoice_id' => 'TEST_PING_CHECK_' . time(),
            ]);

            $statusCode = $response->status();
            $data       = $response->json();
            $respMsg    = (string)($data['message'] ?? '');
            $respStatus = (string)($data['status'] ?? '');

            // Check for API key mismatch or unauthenticated error
            if ($statusCode === 401 || $statusCode === 403 || 
                stripos($respMsg, 'Api Do Not Match') !== false ||
                stripos($respMsg, 'invalid api') !== false ||
                stripos($respMsg, 'unauthenticated') !== false ||
                stripos($respMsg, 'unauthorized') !== false ||
                strtoupper($respStatus) === 'ERROR') {
                return [
                    'success' => false,
                    'message' => '❌ অথেন্টিকেশন ব্যর্থ হয়েছে! UddoktaPay API Key সঠিক নয় (' . ($respMsg ?: 'Invalid Key') . ')।',
                    'details' => $data,
                ];
            }

            // If API key is valid: UddoktaPay will say invoice not found or return invoice data
            if ($statusCode === 200 || stripos($respMsg, 'invoice') !== false || stripos($respMsg, 'not found') !== false || (isset($data['status']) && $data['status'] === true)) {
                return [
                    'success' => true,
                    'message' => '✅ UddoktaPay গেটওয়ের সাথে সফলভাবে কানেক্ট হয়েছে! API Key এবং Base URL সম্পূর্ণ সঠিক ও সক্রিয়।',
                    'details' => $data,
                ];
            }

            return [
                'success' => false,
                'message' => 'সার্ভার রেসপন্স: ' . ($respMsg ?: 'HTTP ' . $statusCode),
                'details' => $data,
            ];
        } catch (\Throwable $e) {
            return [
                'success' => false,
                'message' => '❌ কানেকশন ব্যর্থ হয়েছে: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Initiate Checkout V2 Session
     *
     * @param array $payload
     * @return array ['success' => bool, 'payment_url' => ?string, 'message' => ?string]
     */
    public function initPayment(array $payload): array
    {
        if (!$this->isConfigured()) {
            return [
                'success' => false,
                'message' => 'UddoktaPay API key is not configured. Please set it in Admin Settings.',
            ];
        }

        try {
            $endpoint = $this->apiUrl . '/checkout-v2';

            $response = Http::withHeaders([
                'RT-UDDOKTAPAY-API-KEY' => $this->apiKey,
                'Content-Type'          => 'application/json',
                'Accept'                => 'application/json',
            ])->timeout(20)->post($endpoint, $payload);

            $data = $response->json();

            if ($response->successful() && isset($data['status']) && $data['status'] === true && !empty($data['payment_url'])) {
                return [
                    'success'     => true,
                    'payment_url' => $data['payment_url'],
                    'message'     => $data['message'] ?? 'Payment URL generated',
                ];
            }

            Log::error('UddoktaPay init error:', ['response' => $data, 'payload' => $payload]);

            return [
                'success' => false,
                'message' => $data['message'] ?? 'Failed to initialize payment with UddoktaPay.',
            ];
        } catch (\Throwable $e) {
            Log::error('UddoktaPay init exception: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Connection error with UddoktaPay: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Verify payment status using invoice_id
     *
     * @param string $invoiceId
     * @return array|null
     */
    public function verifyPayment(string $invoiceId): ?array
    {
        if (!$this->isConfigured() || empty($invoiceId)) {
            return null;
        }

        try {
            $endpoint = $this->apiUrl . '/verify-payment';

            $response = Http::withHeaders([
                'RT-UDDOKTAPAY-API-KEY' => $this->apiKey,
                'Content-Type'          => 'application/json',
                'Accept'                => 'application/json',
            ])->timeout(20)->post($endpoint, [
                'invoice_id' => $invoiceId,
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('UddoktaPay verify error:', ['invoice_id' => $invoiceId, 'response' => $response->body()]);
            return null;
        } catch (\Throwable $e) {
            Log::error('UddoktaPay verify exception: ' . $e->getMessage());
            return null;
        }
    }

    public function getWebhookSecret(): string
    {
        return $this->webhookSecret;
    }

    /**
     * Validate HMAC-SHA256 signature against the raw webhook body
     */
    public function validateWebhookSignature(string $rawBody, string $signature): bool
    {
        if (empty($this->webhookSecret) || empty($signature)) {
            return false;
        }
        $expected = hash_hmac('sha256', $rawBody, $this->webhookSecret);
        return hash_equals($expected, trim($signature));
    }

    /**
     * Validate webhook request authenticity via API Key / Secret header
     */
    public function validateWebhookHeader(string $receivedKey): bool
    {
        $expected = !empty($this->webhookSecret) ? $this->webhookSecret : $this->apiKey;
        if (empty($expected) || empty($receivedKey)) {
            return false;
        }
        return hash_equals($expected, trim($receivedKey));
    }
}
