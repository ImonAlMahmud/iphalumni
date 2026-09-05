<?php
declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Models\Setting;
use App\Services\UddoktaPayService;
use App\Services\UploadService;
use Illuminate\Http\Request;

class SettingsController extends BaseController
{
    public function index(Request $request)
    {
        $settings = (new Setting())->getAll();
        $settingsMap = array_column($settings, 'value', 'key');

        return $this->legacyView('admin/settings/index', compact('settingsMap'), 'admin', 'Site Settings');
    }

    public function update(Request $request)
    {
        $model = new Setting();
        $fields = [
            'site_name', 'site_tagline', 'site_email', 'site_phone', 'site_address', 'site_founded',
            'membership_annual_fee', 'membership_lifetime_fee', 'facebook_url', 'linkedin_url',
            'footer_text', 'maintenance_mode', 'payment_instructions', 'directory_require_membership',
            'mail_host', 'mail_port', 'mail_encryption', 'mail_username', 'mail_password', 'mail_from_address', 'mail_from_name',
            'uddoktapay_api_key', 'uddoktapay_api_url', 'uddoktapay_mode', 'uddoktapay_enabled'
        ];

        foreach ($fields as $f) {
            if ($request->has($f)) {
                $model->set($f, (string)$request->input($f));
            }
        }

        return redirect('/admin/settings')->with('success', 'Settings saved successfully.');
    }

    public function testUddoktaPay(Request $request, UddoktaPayService $service)
    {
        $apiUrl = (string)$request->input('uddoktapay_api_url', '');
        $apiKey = (string)$request->input('uddoktapay_api_key', '');

        $result = $service->testConnection($apiUrl, $apiKey);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json($result);
        }

        if ($result['success']) {
            return redirect('/admin/settings')->with('success', $result['message']);
        }

        return redirect('/admin/settings')->with('error', $result['message']);
    }

    public function testSmtp(Request $request)
    {
        $testTo = trim((string)$request->input('test_to', ''));
        if (empty($testTo) || !filter_var($testTo, FILTER_VALIDATE_EMAIL)) {
            $user = \Illuminate\Support\Facades\Auth::user();
            $testTo = $user ? (string)$user->email : 'admin@example.com';
        }

        $host        = (string)$request->input('mail_host', (new Setting())->get('mail_host', ''));
        $port        = (int)$request->input('mail_port', (new Setting())->get('mail_port', 587));
        $encryption  = (string)$request->input('mail_encryption', (new Setting())->get('mail_encryption', 'tls'));
        $username    = (string)$request->input('mail_username', (new Setting())->get('mail_username', ''));
        $password    = (string)$request->input('mail_password', (new Setting())->get('mail_password', ''));
        $fromAddress = (string)$request->input('mail_from_address', (new Setting())->get('mail_from_address', 'contact@iphalumni.dev.cv'));
        $fromName    = (string)$request->input('mail_from_name', (new Setting())->get('mail_from_name', 'IPH Alumni Association'));

        if (empty($host)) {
            return response()->json([
                'success' => false,
                'message' => '❌ SMTP Host প্রদান করা হয়নি। অনুগ্রহ করে SMTP Host ইনপুট দিন।'
            ]);
        }

        try {
            $enc = strtolower((string)$encryption);
            $port = (int)($port ?: 587);

            $scheme = null;
            $mailEncryption = null;
            if ($enc === 'ssl' || $port === 465) {
                $scheme = 'smtps';
                $mailEncryption = 'ssl';
            } elseif ($enc === 'tls' || $port === 587) {
                $scheme = null;
                $mailEncryption = 'tls';
            } elseif ($enc === 'none' || $port === 25) {
                $scheme = null;
                $mailEncryption = null;
            } else {
                $scheme = null;
                $mailEncryption = $enc ?: null;
            }

            config([
                'mail.default'                 => 'smtp',
                'mail.mailers.smtp.transport'  => 'smtp',
                'mail.mailers.smtp.scheme'     => $scheme,
                'mail.mailers.smtp.host'       => $host,
                'mail.mailers.smtp.port'       => $port,
                'mail.mailers.smtp.encryption' => $mailEncryption,
                'mail.mailers.smtp.username'   => $username,
                'mail.mailers.smtp.password'   => $password,
                'mail.mailers.smtp.verify_peer'=> false,
                'mail.mailers.smtp.context'    => [
                    'ssl' => [
                        'allow_self_signed' => true,
                        'verify_peer'       => false,
                        'verify_peer_name'  => false,
                    ],
                ],
                'mail.from.address'            => $fromAddress ?: 'contact@iphalumni.dev.cv',
                'mail.from.name'               => $fromName ?: 'IPH Alumni Association',
            ]);

            app()->forgetInstance('mailer');
            app()->forgetInstance('mail.manager');
            \Illuminate\Support\Facades\Mail::clearResolvedInstances();

            $testParams = [
                'title'       => 'SMTP Connection Test Successful!',
                'badge'       => 'SMTP SERVER TEST',
                'content'     => '<p>অভিনন্দন! আপনার আইপিএইচ অ্যালামনাই অ্যাসোসিয়েশন পোর্টালের <strong>SMTP & Email Server</strong> সফলভাবে কানেক্ট হয়েছে এবং কাজ করছে।</p><div style="background:#f0fdf4;border:1px solid #bbf7d0;padding:16px;border-radius:12px;margin:16px 0;color:#166534;"><strong>Host:</strong> ' . htmlspecialchars($host) . '<br><strong>Port:</strong> ' . $port . '<br><strong>Encryption:</strong> ' . strtoupper($encryption) . '<br><strong>From Address:</strong> ' . htmlspecialchars($fromAddress) . '</div>',
                'action_text' => 'Admin Settings',
                'action_url'  => url('/admin/settings'),
            ];

            $html = \App\Services\MailService::renderHtml($testParams);

            \Illuminate\Support\Facades\Mail::html($html, function ($message) use ($testTo, $fromAddress, $fromName) {
                $message->to($testTo)
                        ->subject('✅ [IPH Alumni] SMTP Connection Test Successful')
                        ->from($fromAddress, $fromName);
            });

            return response()->json([
                'success' => true,
                'message' => "✅ SMTP সার্ভারের সাথে সফলভাবে কানেক্ট হয়েছে এবং টেস্ট ইমেইলটি '{$testTo}' ঠিকানায় পাঠানো হয়েছে!",
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => '❌ SMTP কানেকশন ব্যর্থ হয়েছে: ' . $e->getMessage(),
            ]);
        }
    }

    public function uploadLogo(Request $request)
    {
        $file = $request->file('logo');
        if (!$file || !$file->isValid()) {
            return redirect('/admin/settings')->with('error', 'No file selected.');
        }

        $upload = new UploadService();
        $filename = $upload->uploadStoryImage($file);
        if (!$filename) {
            return redirect('/admin/settings')->with('error', 'Upload failed. Use JPG/PNG/WebP under 2MB.');
        }

        (new Setting())->set('site_logo', $filename);

        return redirect('/admin/settings')->with('success', 'Logo updated.');
    }
}
