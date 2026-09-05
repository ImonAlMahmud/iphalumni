<?php

namespace Tests\Feature;

use Tests\TestCase;

class SecurityHardeningTest extends TestCase
{
    /**
     * Test that the bilingual translation helper handles legacy strings safely.
     */
    public function test_bilingual_helper_handles_string_replacements(): void
    {
        // Default Bengali locale
        $resultBn = __('লগআউট', 'Logout');
        $this->assertEquals('লগআউট', $resultBn);

        // English session locale
        session(['locale' => 'en']);
        $resultEn = __('লগআউট', 'Logout');
        $this->assertEquals('Logout', $resultEn);
        session()->forget('locale');
    }

    /**
     * Test that GET /logout is disabled and returns 405 Method Not Allowed.
     */
    public function test_logout_get_is_disallowed(): void
    {
        $response = $this->get('/logout');
        $this->assertTrue(in_array($response->status(), [404, 405]));
    }

    /**
     * Test that UddoktaPay webhook requires valid HMAC-SHA256 signature when secret is configured.
     */
    public function test_uddoktapay_webhook_rejects_invalid_signature(): void
    {
        config(['services.uddoktapay.webhook_secret' => 'test-secret-key']);

        $payload = json_encode(['invoice_id' => 'INV-12345', 'status' => 'COMPLETED']);
        $response = $this->call(
            'POST',
            '/webhook/uddoktapay',
            [],
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_RT_SIGNATURE' => 'invalid-hmac-signature',
            ],
            $payload
        );

        $response->assertStatus(401);
    }
}
