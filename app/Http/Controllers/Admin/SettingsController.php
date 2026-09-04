<?php
declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Models\Setting;
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
            'footer_text', 'maintenance_mode', 'payment_instructions',
            'mail_host', 'mail_port', 'mail_encryption', 'mail_username', 'mail_password', 'mail_from_address', 'mail_from_name'
        ];

        foreach ($fields as $f) {
            if ($request->has($f)) {
                $model->set($f, (string)$request->input($f));
            }
        }

        return redirect('/admin/settings')->with('success', 'Settings saved successfully.');
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
