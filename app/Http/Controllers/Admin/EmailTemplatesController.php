<?php
declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Services\MailService;
use Illuminate\Http\Request;

class EmailTemplatesController extends BaseController
{
    public function index(Request $request)
    {
        $templates   = MailService::getTemplates();
        $selectedKey = (string)($request->input('key') ?? 'new_member_welcome');
        if (!isset($templates[$selectedKey])) {
            $selectedKey = array_key_first($templates);
        }

        $activeTemplate = $templates[$selectedKey];
        $renderedHtml   = MailService::renderHtml($activeTemplate);

        return $this->legacyView(
            'admin/email_templates/index',
            compact('templates', 'selectedKey', 'activeTemplate', 'renderedHtml'),
            'admin',
            'Email Event Templates Preview'
        );
    }

    public function sendTest(Request $request)
    {
        $recipient = trim((string)$request->input('test_email', ''));
        $key       = (string)$request->input('template_key', 'new_member_welcome');
        $customSub = trim((string)$request->input('custom_subject', ''));

        if (empty($recipient) || !filter_var($recipient, FILTER_VALIDATE_EMAIL)) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => 'অনুগ্রহ করে একটি সঠিক ইমেইল ঠিকানা প্রদান করুন।'], 422);
            }
            return redirect()->back()->with('error', 'অনুগ্রহ করে একটি সঠিক ইমেইল ঠিকানা প্রদান করুন।');
        }

        $templates = MailService::getTemplates();
        if (!isset($templates[$key])) {
            $key = array_key_first($templates);
        }

        $template = $templates[$key];
        $subject  = !empty($customSub) ? $customSub : ('[TEST] ' . ($template['subject'] ?? 'IPH Alumni Test Email'));

        $result = MailService::send($recipient, $subject, $template);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json($result);
        }

        if ($result['success']) {
            return redirect()->back()->with('success', $result['message']);
        }

        return redirect()->back()->with('error', $result['message']);
    }
}
