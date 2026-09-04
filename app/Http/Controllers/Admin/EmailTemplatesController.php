<?php
declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Services\MailService;
use Illuminate\Http\Request;

class EmailTemplatesController extends BaseController
{
    private function getTemplates(): array
    {
        $siteUrl = url('/');
        return [
            'contact_form' => [
                'name' => 'Contact Form User Message (ওয়েবসাইট কন্টাক্ট ফর্ম)',
                'trigger' => 'যখন কোনো ব্যবহারকারী ওয়েবসাইট কন্টাক্ট ফর্মে বার্তা পাঠান',
                'subject' => '[IPH Alumni] New Contact Inquiry from Dr. Rafiqul Islam',
                'title' => 'নতুন কন্টাক্ট বার্তা এসেছে!',
                'content' => '<p>ওয়েবসাইট কন্টাক্ট ফর্ম থেকে একটি বার্তা এসেছে:</p><blockquote style="border-left:4px solid #800020;padding-left:12px;margin:16px 0;color:#333;"><strong>নাম:</strong> Dr. Rafiqul Islam<br><strong>ইমেইল:</strong> rafiqul@example.com<br><strong>বিষয়:</strong> Research Collaboration Enquiry<br><br><strong>বার্তা:</strong> Hello, I would like to know more about the upcoming IPH International Public Health Conference.</blockquote>',
                'action_text' => 'View Contact Submissions',
                'action_url' => $siteUrl . '/admin/contact'
            ],
            'contact_request_sent' => [
                'name' => 'Alumni Contact Request Alert (ডিরেক্টরি প্রাইভেট কন্টাক্ট রিকোয়েস্ট)',
                'trigger' => 'পাবলিক ডিরেক্টরি থেকে কোনো মেম্বারের নিকট যোগাযোগের অনুরোধ আসলে মেম্বারকে পাঠানো অ্যালার্ট',
                'subject' => '[IPH Alumni] নতুন যোগাযোগ অনুরোধ: Public Health Research Collaboration',
                'title' => 'নতুন যোগাযোগ অনুরোধ এসেছে!',
                'content' => '<p>প্রিয় ড. তানভীর আহমেদ,</p><p>আইপিএইচ অ্যালামনাই অ্যাসোসিয়েশন ডিরেক্টরি থেকে <strong>ড. রফিকুল ইসলাম</strong> (rafiqul@example.com) আপনার সাথে যোগাযোগের জন্য একটি অনুরোধ পাঠিয়েছেন:</p><blockquote style="border-left:4px solid #800020;padding-left:12px;margin:16px 0;color:#333;"><strong>বিষয় (Topic):</strong> Public Health Research Collaboration<br><strong>বার্তার সারসংক্ষেপ:</strong> Hello Dr. Tanveer, I am researching Epidemiology at IPH and would like to discuss possible joint journal publication.</blockquote><p>আপনার পোর্টালে লগইন করে এই অনুরোধটি Accept করলে আপনার পছন্দের যোগাযোগ মাধ্যম উক্ত ইমেইলে পাঠাবে।</p>',
                'action_text' => 'অনুরোধ পর্যালোচনা করুন (Portal)',
                'action_url' => $siteUrl . '/portal/contact-requests'
            ],
            'contact_request_accepted' => [
                'name' => 'Contact Request Approved & Details (কন্টাক্ট রিকোয়েস্ট এপ্রুভাল ইমেইল)',
                'trigger' => 'মেম্বার কন্টাক্ট রিকোয়েস্ট Accept করলে অনুরোধকারীর ইমেইলে প্রেরিত যোগাযোগের তথ্য',
                'subject' => '[IPH Alumni] যোগাযোগের অনুরোধ গৃহীত হয়েছে: ড. তানভীর আহমেদ',
                'title' => 'যোগাযোগের অনুরোধ অনুমোদন করা হয়েছে!',
                'content' => '<p>প্রিয় ড. রফিকুল ইসলাম,</p><p>শুভ সংবাদ! আইপিএইচ অ্যালামনাই সদস্য <strong>ড. তানভীর আহমেদ</strong> আপনার যোগাযোগের অনুরোধটি গ্রহণ (Accept) করেছেন।</p><div style="background:#f0fdf4;border:1px solid #bbf7d0;padding:16px;border-radius:12px;margin:16px 0;color:#166534;"><strong>পছন্দের মাধ্যম (Method):</strong> WhatsApp Message & Email<br><strong>যোগাযোগের তথ্য (Contact Info):</strong> +880 1711-223344 (tanveer@example.com)<br><strong>নির্দেশনা / সময়সূচি:</strong> শুধুমাত্র শনি-রবিবার সন্ধ্যা ৭টার পর হোয়াটসঅ্যাপে বার্তা দিন।</div><p>সদস্যের নির্দেশনা অনুসরণ করে উক্ত মাধ্যমে যোগাযোগ করার জন্য অনুরোধ করা হলো।</p>',
                'action_text' => 'ডিরেক্টরি ভিজিট করুন',
                'action_url' => $siteUrl . '/directory'
            ],
            'broadcast_email' => [
                'name' => 'Email Broadcast & Mass Mail (অ্যাডমিন ব্রডকাস্ট ইমেইল)',
                'trigger' => 'অ্যাডমিন থেকে সকল বা নির্দিষ্ট ব্যাচের সকল অ্যালামনাই সদস্যকে একযোগে প্রেরিত ইমেইল',
                'subject' => '[IPH Alumni Broadcast] Annual Alumni Reunion 2026 Announcement',
                'title' => 'Annual Alumni Reunion 2026',
                'content' => '<p>প্রিয় অ্যালামনাই সদস্য,</p><p>আমরা আনন্দের সাথে জানাচ্ছি যে আইপিএইচ অ্যালামনাই অ্যাসোসিয়েশনের বাৎসরিক পুনর্মিলনী আগামী ২৫ ডিসেম্বর ২০২৬ তারিখে অনুষ্ঠিত হবে। আমাদের অনলাইন পোর্টালে টিকিট রেজিস্ট্রেশন প্রক্রিয়া শুরু হয়েছে।</p><p>সকলের উপস্থিতি ও আন্তরিক অংশগ্রহণ আমাদের আয়োজনকে সাফল্যমণ্ডিত করবে।</p>',
                'action_text' => 'Register for Event',
                'action_url' => $siteUrl . '/events/1'
            ],
            'job_application_alert' => [
                'name' => 'Job Application Notification (জব সার্কুলার আবেদন)',
                'trigger' => 'কোনো চাকরির সার্কুলারে মেম্বার আবেদন করলে পোস্টদাতাকে পাঠানো অ্যালার্ট ইমেইল',
                'subject' => '[IPH Job Portal] New Application for Senior Research Officer',
                'title' => 'চাকরির নতুন আবেদন জমা হয়েছে!',
                'content' => '<p>প্রিয় নিয়োগকারী,</p><p>আপনার পোস্ট করা <strong>Senior Research Officer</strong> পদে নতুন একটি আবেদন জমা হয়েছে:</p><blockquote style="border-left:4px solid #800020;padding-left:12px;margin:16px 0;color:#333;"><strong>আবেদনকারীর নাম:</strong> ড. সাদিয়া রহমান<br><strong>ব্যাচ:</strong> L-4<br><strong>বর্তমান পদবি:</strong> Research Fellow, IPH</blockquote>',
                'action_text' => 'View Application CV',
                'action_url' => $siteUrl . '/portal/jobs/1/applications'
            ],
            'new_member_admin_alert' => [
                'name' => 'New Member Registration Admin Alert (নতুন মেম্বার সাইনআপ অ্যাডমিন অ্যালার্ট)',
                'trigger' => '১. নতুন কোনো অ্যালামনাই সদস্য রেজিস্ট্রেশন করলে অ্যাডমিনের নিকট প্রেরিত নোটিফিকেশন ইমেইল',
                'subject' => '[IPH Admin Alert] New Alumni Registration: Dr. Mahmudul Hasan (L-5)',
                'title' => 'নতুন মেম্বার রেজিস্ট্রেশন সম্পন্ন হয়েছে!',
                'content' => '<p>প্রিয় অ্যাডমিন,</p><p>আইপিএইচ অ্যালামনাই অ্যাসোসিয়েশন পোর্টালে নতুন একজন সদস্য একাউন্ট তৈরি করেছেন:</p><div style="background:#f8fafc;border:1px solid #e2e8f0;padding:16px;border-radius:12px;margin:16px 0;color:#334155;"><strong>সদস্যের নাম:</strong> Dr. Mahmudul Hasan<br><strong>ইমেইল:</strong> mahmud@example.com<br><strong>ফোন:</strong> +880 1812-345678<br><strong>ব্যাচ বছর:</strong> L-5 (2015)<br><strong>ডিগ্রি:</strong> MPH in Virology</div><p>মেম্বারের তথ্য যাচাই ও অনুমোদন (Approve/Verify) করার জন্য অ্যাডমিন প্যানেলে লগইন করুন।</p>',
                'action_text' => 'Review Alumni Profile',
                'action_url' => $siteUrl . '/admin/alumni'
            ],
            'new_member_welcome' => [
                'name' => 'New Member Welcome Email (নতুন মেম্বার স্বাগতম ইমেইল)',
                'trigger' => '২. সাইনআপ সম্পন্ন করার সাথে সাথে নতুন সদস্যকে স্বাগত জানিয়ে পাঠানো ইমেইল',
                'subject' => 'Welcome to IPH Alumni Association, Dr. Mahmudul Hasan!',
                'title' => 'আইপিএইচ অ্যালামনাই নেটওয়ার্কে আপনাকে স্বাগতম!',
                'content' => '<p>প্রিয় ড. মাহমুদুল হাসান,</p><p>ইনস্টিটিউট অব পাবলিক হেলথ (IPH) অ্যালামনাই অ্যাসোসিয়েশন নেটওয়ার্কে যোগদানের জন্য আপনাকে আন্তরিক শুভেচ্ছা ও স্বাগতম!</p><p>আপনার একাউন্টটি সফলভাবে তৈরি করা হয়েছে। পোর্টালে লগইন করে আপনার পেশাগত অভিজ্ঞতা, দক্ষতা, হলের নাম ও যোগাযোগ তথ্য সমৃদ্ধ করুন যাতে অন্যান্য সহপাঠী ও জুনিয়ররা সহজেই আপনার সাথে যুক্ত হতে পারেন।</p>',
                'action_text' => 'Login to Portal',
                'action_url' => $siteUrl . '/login'
            ],
            'membership_paid_invoice' => [
                'name' => 'Membership Renewal / Payment Invoice (সদস্যপদ ফি পেমেন্ট ইনভয়েস)',
                'trigger' => '৩. সদস্যপদ ফি বা রিনিউয়াল ফি সফলভাবে পরিশোধের পর মেম্বারকে প্রেরিত ইনভয়েস ইমেইল',
                'subject' => '[IPH Alumni] Membership Payment Receipt & Invoice #INV-2026-084',
                'title' => 'সদস্যপদ ফি রসিদ ও ইনভয়েস',
                'content' => '<p>প্রিয় ড. তানভীর আহমেদ,</p><p>আইপিএইচ অ্যালামনাই অ্যাসোসিয়েশনের সদস্যপদ ফি সফলভাবে গৃহীত হয়েছে। নিচে আপনার পেমেন্ট ইনভয়েস সমূহের বিবরণ প্রদান করা হলো:</p><div style="background:#fafafa;border:1px solid #ea6;padding:16px;border-radius:12px;margin:16px 0;"><table style="width:100%;font-size:13px;color:#333;"><tr><td><strong>Invoice ID:</strong> #INV-2026-084</td><td style="text-align:right;"><strong>তারিখ:</strong> ' . date('d M Y') . '</td></tr><tr><td><strong>সদস্যপদ ধরণ:</strong> General Active Membership</td><td style="text-align:right;"><strong>মেয়াদ:</strong> 1 Year (Valid till Dec 2027)</td></tr><tr style="border-top:1px solid #ddd;"><td style="padding-top:8px;"><strong>মোট পরিশোধিত অর্থ:</strong></td><td style="text-align:right;padding-top:8px;font-weight:bold;color:#800020;">BDT 2,000.00 (Paid via bKash)</td></tr></table></div><p>অ্যালামনাই অ্যাসোসিয়েশনের সাথে যুক্ত থাকার জন্য আপনাকে ধন্যবাদ।</p>',
                'action_text' => 'Download Membership Card',
                'action_url' => $siteUrl . '/portal/id-card'
            ],
            'membership_expiry_alert' => [
                'name' => 'Membership Expiry Alert (মেয়াদ শেষ হওয়ার ৭ দিন আগে অ্যালার্ট)',
                'trigger' => '৪. সদস্যপদের মেয়াদের শেষ ৭ দিন অবশিষ্ট থাকতে মেম্বারকে পাঠানো রিমাইন্ডার ইমেইল',
                'subject' => '[Urgent Reminder] Your IPH Alumni Membership Expires in 7 Days!',
                'title' => 'সদস্যপদের মেয়াদ শেষ হওয়ার পূর্ববর্তী নোটিশ',
                'content' => '<p>প্রিয় ড. তানভীর আহমেদ,</p><p>আমরা আপনাকে অবহিত করতে চাই যে আপনার আইপিএইচ অ্যালামনাই অ্যাসোসিয়েশনের বার্ষিক সদস্যপদের মেয়াদ আগামী <strong>৭ দিনের মধ্যে (৩১ ডিসেম্বর ২০২৬)</strong> শেষ হতে যাচ্ছে।</p><p>ডিজিটাল আইডি কার্ড, মেম্বার ডিরেক্টরি এক্সেস এবং বার্ষিক পুনর্মিলনীর বিশেষ সুবিধা নিরবচ্ছিন্ন রাখতে দয়া করে আপনার সদস্যপদ রিনিউ করুন।</p>',
                'action_text' => 'Renew Membership Now',
                'action_url' => $siteUrl . '/portal/membership'
            ],
            'membership_cancelled_notice' => [
                'name' => 'Membership Cancellation Notice (মেয়াদ শেষে সদস্যপদ স্থগিতের নোটিশ)',
                'trigger' => '৫. নির্দিষ্ট সময়ের মধ্যে মেম্বারশিপ রিনিউ না করায় একাউন্ট সার্ভিস স্থগিতের ইমেইল',
                'subject' => '[Notice] Your IPH Alumni Active Status Has Been Suspended',
                'title' => 'সদস্যপদ নিষ্ক্রিয় সংক্রান্ত নোটিশ',
                'content' => '<p>প্রিয় ড. তানভীর আহমেদ,</p><p>আপনার মেম্বারশিপের নির্ধারিত মেয়াদ শেষ হওয়ার পর নির্ধারিত সময়ে ফি জমা না হওয়ায় আপনার <strong>Active Member Status</strong> সাময়িকভাবে স্থগিত (Suspended) করা হয়েছে।</p><p>পুনরায় একটিভ অ্যালামনাই মেম্বার হিসেবে সকল সুবিধা পেতে যেকোনো সময় আপনার পোর্টালে লগইন করে রিনিউয়াল ফি পরিশোধ করতে পারেন।</p>',
                'action_text' => 'Re-activate Membership',
                'action_url' => $siteUrl . '/portal/membership'
            ],
            'new_story_published_alert' => [
                'name' => 'New Story Published Broadcast (নতুন ব্লগ/স্টোরি পাবলিশ ইমেইল)',
                'trigger' => '৬. পোর্টালে নতুন কোনো Success Story বা ব্লগ পাবলিশ হলে সকল অ্যালামনাই মেম্বারদের পাঠানো ইমেইল',
                'subject' => '[New Story] "Pioneering Public Health Research in Bangladesh" by Dr. Anisur Rahman',
                'title' => 'নতুন অ্যালামনাই ব্লগে চোখ রাখুন!',
                'content' => '<p>প্রিয় অ্যালামনাই সদস্য,</p><p>আমাদের অ্যালামনাই পোর্টালে একটি নতুন অনুপ্রেরণাদায়ক স্টোরি প্রকাশিত হয়েছে:</p><blockquote style="border-left:4px solid #800020;padding-left:12px;margin:16px 0;color:#333;"><h3 style="margin:0 0 5px 0;color:#800020;">Pioneering Public Health Research in Bangladesh</h3><p style="margin:0;font-size:13px;color:#555;">লেখক: ড. আনিসুর রহমান (L-2)<br>পাবলিক হেলথ গবেষণায় বাংলাদেশের অভাবনীয় সাফল্য ও ভবিষ্যৎ সম্ভাবনা নিয়ে বিস্তারিত নিবন্ধ...</p></blockquote>',
                'action_text' => 'Read Full Story',
                'action_url' => $siteUrl . '/stories/pioneering-public-health-research'
            ],
            'contact_form_admin_notice' => [
                'name' => 'Contact Form Submission Admin Notice (ফর্মে রেসপন্সে অ্যাডমিন অ্যালার্ট)',
                'trigger' => '৭. ওয়েবসাইটে যেকোনো কনটাক্ট ফর্ম / মতামত ফর্মে বার্তা জমা পড়লে অ্যাডমিনকে প্রেরিত ইমেইল',
                'subject' => '[Admin Notification] New General Inquiry Submitted on Contact Form',
                'title' => 'কন্টাক্ট ফর্মে নতুন রেসপন্স জমা হয়েছে!',
                'content' => '<p>প্রিয় অ্যাডমিন,</p><p>পাবলিক ওয়েবসাইটের কন্টাক্ট ফর্ম থেকে নতুন একটি রেসপন্স রেকর্ড করা হয়েছে:</p><div style="background:#f1f5f9;border:1px solid #cbd5e1;padding:16px;border-radius:12px;margin:16px 0;color:#1e293b;"><strong>প্রেরক:</strong> Engr. Kamrul Ahsan<br><strong>ইমেইল:</strong> kamrul@example.com<br><strong>ফোন:</strong> +880 1912-889900<br><strong>বিষয়:</strong> Sponsorship Enquiry for IPH Alumni Event</div><p>বিস্তারিত পর্যালোচনা ও উত্তর দেওয়ার জন্য অ্যাডমিন প্যানেলে লগইন করুন।</p>',
                'action_text' => 'View Admin Dashboard',
                'action_url' => $siteUrl . '/admin/dashboard'
            ]
        ];
    }

    public function index(Request $request)
    {
        $templates = $this->getTemplates();
        $selectedKey = (string)($request->input('key') ?? 'contact_request_sent');
        if (!isset($templates[$selectedKey])) {
            $selectedKey = array_key_first($templates);
        }

        $activeTemplate = $templates[$selectedKey];

        $renderedHtml = "<div style='font-family: Arial, sans-serif; line-height: 1.6; max-width: 600px; margin: 0 auto; border: 1px solid #e2e8f0; border-radius: 12px; padding: 24px; background: #fff;'>
            <h2 style='color: #800020;'>" . e($activeTemplate['title']) . "</h2>
            <div>" . $activeTemplate['content'] . "</div>
        </div>";

        return $this->legacyView(
            'admin/email_templates/index',
            compact('templates', 'selectedKey', 'activeTemplate', 'renderedHtml'),
            'admin',
            'Email Event Templates Preview'
        );
    }
}
