<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\AlumniProfile;
use App\Models\Event;
use App\Models\News;
use App\Models\Setting;
use App\Services\AuditLogger;
use App\Services\MailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends BaseController
{
    public function index(Request $request)
    {
        $alumni     = new AlumniProfile();
        $eventModel = new Event();
        $newsModel  = new News();

        $stats = $alumni->getStats();

        $featured = DB::select(
            "SELECT ap.*, u.name, u.email,
                    (SELECT ae.job_title FROM alumni_employment ae WHERE ae.alumni_profile_id = ap.id AND ae.is_current = 1 LIMIT 1) as job_title,
                    (SELECT ae.organization FROM alumni_employment ae WHERE ae.alumni_profile_id = ap.id AND ae.is_current = 1 LIMIT 1) as organization
             FROM alumni_profiles ap
             JOIN users u ON u.id = ap.user_id
             WHERE ap.status IN ('approved', 'verified', 'active') AND ap.deleted_at IS NULL AND ap.is_featured = 1
             ORDER BY ap.created_at DESC LIMIT 8"
        );

        if (count($featured) < 4) {
            $alumni_featured = DB::select(
                "SELECT ap.*, u.name, u.email,
                         (SELECT ae.job_title FROM alumni_employment ae WHERE ae.alumni_profile_id = ap.id AND ae.is_current = 1 LIMIT 1) as job_title,
                         (SELECT ae.organization FROM alumni_employment ae WHERE ae.alumni_profile_id = ap.id AND ae.is_current = 1 LIMIT 1) as organization
                  FROM alumni_profiles ap
                  JOIN users u ON u.id = ap.user_id
                  WHERE ap.status IN ('approved', 'verified', 'active') AND ap.deleted_at IS NULL
                  ORDER BY ap.created_at DESC LIMIT 8"
            );
        } else {
            $alumni_featured = $featured;
        }
        $alumni_featured = array_map(fn($r) => (array)$r, (array)$alumni_featured);

        $events         = $eventModel->getUpcoming(3);
        $news           = $newsModel->getLatest(3);
        $successStories = array_map(fn($r) => (array)$r, DB::select("SELECT * FROM success_stories WHERE status = 'published' AND deleted_at IS NULL ORDER BY is_featured DESC, created_at DESC LIMIT 3"));
        $membershipTypes = array_map(fn($r) => (array)$r, DB::select("SELECT * FROM membership_types WHERE is_active = 1 ORDER BY sort_order ASC"));

        $title       = 'IPH Alumni Association';
        $description = config('app.name') . ' — ' . env('APP_TAGLINE', 'Institute of Public Health Alumni Network');

        return $this->legacyView('home/index', compact(
            'stats', 'alumni_featured', 'events', 'news', 'successStories', 'membershipTypes'
        ), 'main', $title, $description);
    }

    public function about(Request $request)
    {
        return $this->legacyView('about/index', [], 'main', 'About Us');
    }

    public function history(Request $request)
    {
        return redirect('/about');
    }

    public function committee(Request $request)
    {
        $members = DB::select(
            "SELECT cm.*, u.name, ap.avatar, ae.job_title, ae.organization
             FROM committee_members cm
             JOIN users u ON u.id = cm.user_id
             LEFT JOIN alumni_profiles ap ON ap.user_id = cm.user_id
             LEFT JOIN alumni_employment ae ON ae.alumni_profile_id = ap.id AND ae.is_current = 1
             WHERE cm.deleted_at IS NULL
             ORDER BY cm.committee_type ASC, cm.sort_order ASC"
        );
        $members = array_map(fn($r) => (array)$r, $members);
        $byType  = [];
        foreach ($members as $m) {
            $byType[$m['committee_type']][] = $m;
        }
        return $this->legacyView('about/committee', compact('members', 'byType'), 'main', 'Committee');
    }

    public function constitution(Request $request)
    {
        return $this->legacyView(
            'about/constitution', [], 'main',
            'সংগঠনের গঠনতন্ত্র (Constitution)',
            'ইনস্টিটিউট অব পাবলিক হেলথ অ্যালামনাই অ্যাসোসিয়েশনের (IPHA) অফিসিয়াল গঠনতন্ত্র ও বিধিমালা।'
        );
    }

    public function constitutionPdf(Request $request)
    {
        $viewFile = resource_path('views/about/print_constitution.php');
        if (file_exists($viewFile)) {
            ob_start();
            require $viewFile;
            return response(ob_get_clean());
        }
        abort(404);
    }

    public function faq(Request $request)
    {
        $faqs = array_map(fn($r) => (array)$r, DB::select("SELECT * FROM faqs WHERE is_active = 1 ORDER BY sort_order ASC"));
        return $this->legacyView('faq/index', compact('faqs'), 'main', 'FAQ');
    }

    public function contact(Request $request)
    {
        $siteSettings = [];
        try {
            $settingsRows = DB::select("SELECT setting_key, setting_value FROM settings");
            foreach ($settingsRows as $sr) {
                $siteSettings[$sr->setting_key] = $sr->setting_value;
            }
        } catch (\Exception $e) {}

        $num1 = rand(1, 9);
        $num2 = rand(1, 9);
        session(['captcha_answer' => $num1 + $num2]);
        $captchaQuestion = "{$num1} + {$num2} = ?";

        return $this->legacyView(
            'contact/index',
            compact('siteSettings', 'captchaQuestion'),
            'main',
            'যোগাযোগ (Contact Us)',
            'আইপিএইচ অ্যালামনাই অ্যাসোসিয়েশনের সাথে যোগাযোগ করুন।'
        );
    }

    public function handleContact(Request $request)
    {
        $inquiryType     = trim($request->input('inquiry_type', 'general'));
        $name            = trim($request->input('name', ''));
        $companyName     = trim($request->input('company_name', ''));
        $email           = trim($request->input('email', ''));
        $phone           = trim($request->input('phone', ''));
        $sponsorCategory = trim($request->input('sponsor_category', ''));
        $subject         = trim($request->input('subject', $inquiryType === 'sponsor' ? 'New Sponsorship Proposal' : 'General Inquiry'));
        $message         = trim($request->input('message', ''));
        $captchaInput    = trim($request->input('captcha_input', ''));
        $expectedCaptcha = session('captcha_answer');

        if ($expectedCaptcha === null || (int)$captchaInput !== (int)$expectedCaptcha) {
            return back()->with('error', 'ক্যাপচা (Math Captcha) উত্তর সঠিক নয়। দয়া করে আবার চেষ্টা করুন।');
        }
        if (empty($name) || empty($email) || empty($message)) {
            return back()->with('error', 'অনুগ্রহ করে আপনার নাম, ইমেইল এবং বার্তা সঠিকভাবে প্রদান করুন।');
        }

        $adminEmail = DB::table('settings')
            ->whereIn('setting_key', ['site_email', 'contact_email'])
            ->where('setting_value', '!=', '')
            ->value('setting_value') ?? 'info@iphalumni.org';

        $mailService  = new MailService();
        $emailSubject = ($inquiryType === 'sponsor' ? '[SPONSORSHIP PROPOSAL] ' : '[CONTACT FORM] ') . $subject;

        $bodyHtml = "
        <div style='font-family: Arial, sans-serif; line-height: 1.6; color: #101820; max-width: 600px; margin: 0 auto; border: 1px solid #e2e8f0; border-radius: 12px; padding: 24px; background: #ffffff;'>
            <h2 style='color: #800020;'>IPH Alumni Association - Website Contact Form</h2>
            <p>New response received on " . date('d M Y, h:i A') . "</p>
            <table style='width: 100%; border-collapse: collapse;'>
                <tr><td><strong>Inquiry Type:</strong></td><td>" . strtoupper($inquiryType) . "</td></tr>
                <tr><td><strong>Name:</strong></td><td>" . e($name) . "</td></tr>
                <tr><td><strong>Email:</strong></td><td>" . e($email) . "</td></tr>
                <tr><td><strong>Phone:</strong></td><td>" . e($phone ?: 'N/A') . "</td></tr>
                <tr><td><strong>Subject:</strong></td><td>" . e($subject) . "</td></tr>
            </table>
            <div style='background: #f8fafc; border-left: 4px solid #800020; padding: 14px; margin-top: 16px;'>
                <h4>Message:</h4>
                <p style='white-space: pre-wrap;'>" . nl2br(e($message)) . "</p>
            </div>
        </div>";

        $mailService->send($adminEmail, $emailSubject, $bodyHtml);

        $successMsg = $inquiryType === 'sponsor'
            ? 'ধন্যবাদ! আপনার স্পন্সরশিপ প্রস্তাবনাটি সফলভাবে পাঠানো হয়েছে।'
            : 'ধন্যবাদ! আপনার বার্তাটি সফলভাবে পাঠানো হয়েছে।';

        return redirect('/contact')->with('success', $successMsg);
    }

    public function sitemap(Request $request)
    {
        $urls = [
            url('/'), url('/about'), url('/history'), url('/committee'),
            url('/directory'), url('/events'), url('/news'), url('/stories'),
            url('/jobs'), url('/faq'), url('/contact'),
        ];

        $newsList  = DB::select("SELECT slug FROM news WHERE status = 'published' AND deleted_at IS NULL");
        $eventList = DB::select("SELECT slug FROM events WHERE deleted_at IS NULL");

        foreach ($newsList as $n) $urls[] = url('/news/' . $n->slug);
        foreach ($eventList as $e) $urls[] = url('/events/' . $e->slug);

        return response(view()->file(resource_path('views/sitemap.xml.php'), compact('urls')))
            ->header('Content-Type', 'application/xml; charset=utf-8');
    }

    public function mentorship(Request $request)
    {
        return $this->legacyView(
            'mentorship/index', [], 'main',
            'অ্যালামনাই মেনটরশিপ কানেক্ট (Mentorship Connect)',
            'আইপিএইচ অ্যালামনাইদের ক্যারিয়ার গাইডেন্স ও উচ্চশিক্ষা বিষয়ক মেনটরশিপ হাব।'
        );
    }

    public function adminBroadcast(Request $request)
    {
        return $this->legacyView('admin/broadcast/index', [], 'admin', 'Mass Email Broadcast');
    }

    public function adminBroadcastSend(Request $request)
    {
        $group   = trim($request->input('recipient_group', 'all'));
        $subject = trim($request->input('subject', ''));
        $body    = trim($request->input('body', ''));

        if (empty($subject) || empty($body)) {
            return back()->with('error', 'ইমেইল বিষয় ও বার্তা সঠিকভাবে লিখুন।');
        }

        $recipients  = DB::table('users')->where('email', '!=', '')->pluck('email')->toArray();
        $mailService = new MailService();
        $sentCount   = 0;

        foreach ($recipients as $toEmail) {
            $bodyHtml = "<div style='font-family: Arial, sans-serif;'>
                <h2 style='color: #800020;'>IPH Alumni Association Announcement</h2>
                <div>" . nl2br(e($body)) . "</div>
            </div>";
            if ($mailService->send($toEmail, $subject, $bodyHtml)) $sentCount++;
        }

        DB::table('email_broadcasts')->insert([
            'sender_id'        => Auth::id(),
            'subject'          => $subject,
            'recipient_group'  => $group,
            'body'             => $body,
            'sent_count'       => $sentCount,
            'created_at'       => now(),
        ]);

        AuditLogger::log('ADMIN_BROADCAST', "Sent broadcast email to {$sentCount} recipients");

        return redirect('/admin/broadcast')->with('success', "সফলভাবে {$sentCount} জনকে ইমেইল পাঠানো হয়েছে!");
    }
}
