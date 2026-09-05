<?php
declare(strict_types=1);

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class MailService
{
    /**
     * Wrap raw email body inside an ultra-premium, animated responsive HTML layout
     */
    public static function renderHtml(array $params): string
    {
        $settingModel = new Setting();
        $siteName     = (string) $settingModel->get('site_name', 'IPH Alumni Association');
        $siteTagline  = (string) $settingModel->get('site_tagline', 'Institute of Public Health — Alumni Network');
        $siteLogo     = (string) $settingModel->get('site_logo', '');
        $siteEmail    = (string) $settingModel->get('site_email', 'contact@iphalumni.dev.cv');
        $sitePhone    = (string) $settingModel->get('site_phone', '+880 1711-000000');
        $siteAddress  = (string) $settingModel->get('site_address', 'Institute of Public Health (IPH), Mohakhali, Dhaka-1212, Bangladesh');
        $siteUrl      = url('/');

        $logoUrl = !empty($siteLogo) ? url('storage/' . $siteLogo) : url('/images/LOGO.png');

        $title       = $params['title'] ?? 'Notification from IPH Alumni';
        $subtitle    = $params['subtitle'] ?? 'Institute of Public Health Alumni Network';
        $badge       = $params['badge'] ?? 'SYSTEM NOTIFICATION';
        $content     = $params['content'] ?? '';
        $actionText  = $params['action_text'] ?? null;
        $actionUrl   = $params['action_url'] ?? null;
        $footerNote  = $params['footer_note'] ?? 'This is an official automated notification from IPH Alumni Association.';
        $currentYear = date('Y');

        $ctaHtml = '';
        if (!empty($actionText) && !empty($actionUrl)) {
            $ctaHtml = '
                <div style="text-align: center; margin: 32px 0 20px 0;">
                  <a href="' . htmlspecialchars($actionUrl) . '" class="cta-btn" target="_blank">
                    ' . htmlspecialchars($actionText) . ' &nbsp;→
                  </a>
                </div>
                <div style="margin-top: 24px; padding: 14px 16px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 14px; font-size: 12px; color: #64748b; line-height: 1.5;">
                  <span>🔒 যদি বোতামটি কাজ না করে, তবে সরাসরি ব্রাউজারে এই লিঙ্কটি কপি ও পেস্ট করুন:<br>
                    <a href="' . htmlspecialchars($actionUrl) . '" style="color: #800020; word-break: break-all; text-decoration: underline;">' . htmlspecialchars($actionUrl) . '</a>
                  </span>
                </div>';
        }

        return '<!DOCTYPE html>
<html lang="bn">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>' . htmlspecialchars($title) . '</title>
  <style type="text/css">
    @import url("https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&family=Outfit:wght@400;600;700&display=swap");

    body, p, h1, h2, h3, h4, h5, h6, table, td {
      margin: 0;
      padding: 0;
      font-family: "Hind Siliguri", "Outfit", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    }
    body {
      background-color: #f1f5f9;
      color: #1e293b;
      -webkit-font-smoothing: antialiased;
      width: 100% !important;
      line-height: 1.65;
    }
    img {
      border: 0;
      outline: none;
      text-decoration: none;
      display: block;
    }
    table {
      border-collapse: separate !important;
    }

    @keyframes floatSoft {
      0%, 100% { transform: translateY(0px); }
      50% { transform: translateY(-5px); }
    }
    @keyframes pulseGlow {
      0%, 100% { box-shadow: 0 0 15px rgba(212, 175, 55, 0.4); }
      50% { box-shadow: 0 0 25px rgba(212, 175, 55, 0.8); }
    }
    @keyframes fadeInCard {
      from { opacity: 0; transform: translateY(12px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .animated-card {
      animation: fadeInCard 0.6s ease-out forwards;
    }
    .animated-logo {
      animation: floatSoft 4s ease-in-out infinite;
    }
    .animated-badge {
      animation: pulseGlow 2.5s infinite;
    }

    .cta-btn {
      display: inline-block;
      padding: 14px 34px;
      background: linear-gradient(135deg, #800020 0%, #A22638 50%, #800020 100%);
      color: #ffffff !important;
      text-decoration: none !important;
      font-weight: 700;
      font-size: 14.5px;
      border-radius: 50px;
      box-shadow: 0 4px 15px rgba(128, 0, 32, 0.35);
      border: 1px solid rgba(255, 215, 0, 0.3);
      transition: all 0.3s ease;
      letter-spacing: 0.3px;
    }

    .content-box {
      font-size: 14.5px;
      color: #334155;
      line-height: 1.75;
    }
    .content-box p {
      margin-bottom: 14px;
    }

    @media only screen and (max-width: 620px) {
      .email-container { width: 100% !important; border-radius: 0 !important; }
      .email-body { padding: 24px 18px !important; }
      .email-header { padding: 28px 18px 20px 18px !important; }
      .cta-btn { width: 100% !important; box-sizing: border-box; text-align: center; }
    }
  </style>
</head>
<body style="background-color: #f1f5f9; padding: 25px 0; margin: 0;">

  <center>
    <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 620px; margin: 0 auto;">
      <tr>
        <td align="center">

          <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" class="email-container animated-card" style="background-color: #ffffff; border-radius: 24px; overflow: hidden; box-shadow: 0 12px 40px rgba(15, 23, 42, 0.08); border: 1px solid rgba(226, 232, 240, 0.8);">

            <!-- Top Header with Brand Gradient & Logo -->
            <tr>
              <td class="email-header" align="center" style="background: linear-gradient(135deg, #4A0012 0%, #800020 50%, #9B1B30 100%); padding: 36px 30px 28px 30px; border-bottom: 3px solid #D4AF37;">
                
                <!-- Perfectly Centered Logo Badge -->
                <table role="presentation" border="0" cellpadding="0" cellspacing="0" style="margin: 0 auto 16px auto;">
                  <tr>
                    <td align="center" valign="middle" class="animated-logo" style="width: 86px; height: 86px; background: #ffffff; border-radius: 50%; border: 3px solid #D4AF37; box-shadow: 0 8px 25px rgba(0,0,0,0.35); text-align: center; vertical-align: middle; line-height: 0; font-size: 0; padding: 0;">
                      <img src="' . htmlspecialchars($logoUrl) . '" alt="IPH Alumni Logo" width="76" height="76" style="width: 76px; height: 76px; max-width: 76px; max-height: 76px; object-fit: contain; border-radius: 50%; display: inline-block; margin: 0 auto; vertical-align: middle;">
                    </td>
                  </tr>
                </table>

                <h1 style="color: #ffffff; font-size: 20px; font-weight: 700; letter-spacing: 0.3px; margin: 0;">
                  ' . htmlspecialchars($siteName) . '
                </h1>
                <p style="color: #fce7f3; font-size: 12.5px; margin-top: 4px; font-weight: 400; opacity: 0.9;">
                  ' . htmlspecialchars($siteTagline) . '
                </p>

                <div style="margin-top: 14px;">
                  <span class="animated-badge" style="display: inline-block; background: rgba(212, 175, 55, 0.2); border: 1px solid #D4AF37; color: #fef08a; font-size: 10.5px; font-weight: 700; text-transform: uppercase; padding: 4px 14px; border-radius: 50px; letter-spacing: 0.8px;">
                    ✦ ' . htmlspecialchars($badge) . '
                  </span>
                </div>

              </td>
            </tr>

            <!-- Body Content Area -->
            <tr>
              <td class="email-body" style="padding: 36px 36px 30px 36px;">

                <h2 style="color: #0f172a; font-size: 21px; font-weight: 700; margin-bottom: 18px; line-height: 1.35; border-bottom: 2px solid #f1f5f9; padding-bottom: 12px;">
                  ' . htmlspecialchars($title) . '
                </h2>

                <div class="content-box">
                  ' . $content . '
                </div>

                ' . $ctaHtml . '

              </td>
            </tr>

            <!-- Footer Area -->
            <tr>
              <td style="background-color: #0f172a; padding: 30px 30px; text-align: center; color: #94a3b8; font-size: 12px; border-top: 1px solid #1e293b;">

                <div style="margin-bottom: 16px;">
                  <a href="' . htmlspecialchars($siteUrl) . '" style="color: #cbd5e1; text-decoration: none; margin: 0 10px; font-weight: 500; font-size: 12.5px;">Home</a>
                  <span style="color: #475569;">•</span>
                  <a href="' . htmlspecialchars($siteUrl) . '/directory" style="color: #cbd5e1; text-decoration: none; margin: 0 10px; font-weight: 500; font-size: 12.5px;">Directory</a>
                  <span style="color: #475569;">•</span>
                  <a href="' . htmlspecialchars($siteUrl) . '/events" style="color: #cbd5e1; text-decoration: none; margin: 0 10px; font-weight: 500; font-size: 12.5px;">Events</a>
                  <span style="color: #475569;">•</span>
                  <a href="' . htmlspecialchars($siteUrl) . '/portal" style="color: #D4AF37; text-decoration: none; margin: 0 10px; font-weight: 600; font-size: 12.5px;">Alumni Portal</a>
                </div>

                <p style="color: #64748b; margin-bottom: 8px; line-height: 1.5;">
                  📍 ' . htmlspecialchars($siteAddress) . '
                </p>
                <p style="color: #64748b; margin-bottom: 16px;">
                  📧 <a href="mailto:' . htmlspecialchars($siteEmail) . '" style="color: #94a3b8; text-decoration: none;">' . htmlspecialchars($siteEmail) . '</a> &nbsp;|&nbsp; 📞 ' . htmlspecialchars($sitePhone) . '
                </p>

                <div style="height: 1px; background: rgba(255,255,255,0.08); margin: 16px 0;"></div>

                <p style="color: #475569; font-size: 11px; margin-bottom: 4px;">
                  ' . htmlspecialchars($footerNote) . '
                </p>
                <p style="color: #475569; font-size: 11px;">
                  © ' . $currentYear . ' ' . htmlspecialchars($siteName) . '. All Rights Reserved.
                </p>

              </td>
            </tr>

          </table>

        </td>
      </tr>
    </table>
  </center>

</body>
</html>';
    }

    /**
     * Get all structured system email templates
     */
    public static function getTemplates(): array
    {
        $siteUrl = url('/');
        $currentDate = date('d M Y');

        return [
            'new_member_welcome' => [
                'name' => '1. New Member Welcome (নতুন মেম্বার স্বাগতম ইমেইল)',
                'trigger' => 'সাইনআপ সম্পন্ন করার সাথে সাথে নতুন সদস্যকে স্বাগত জানিয়ে পাঠানো স্বাগতম ইমেইল',
                'badge' => 'WELCOME TO IPH ALUMNI',
                'subject' => '🎉 Welcome to IPH Alumni Association Network!',
                'title' => 'আইপিএইচ অ্যালামনাই নেটওয়ার্কে আপনাকে স্বাগতম!',
                'content' => '<p>প্রিয় ড. মাহমুদুল হাসান,</p><p>ইনস্টিটিউট অব পাবলিক হেলথ (IPH) অ্যালামনাই অ্যাসোসিয়েশনের বিশাল নেটওয়ার্কে আপনাকে আন্তরিক শুভেচ্ছা ও উষ্ণ স্বাগতম!</p><p>আপনার অ্যাকাউন্টটি সফলভাবে তৈরি করা হয়েছে। আমাদের এই প্ল্যাটফর্মটি দেশের অন্যতম শীর্ষস্থানীয় পাবলিক হেলথ প্রফেশনাল ও গবেষকদের এক অনন্য মেলবন্ধন।</p><div style="background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%); border-left: 4px solid #800020; padding: 18px 20px; border-radius: 0 16px 16px 0; margin: 20px 0;"><h4 style="color: #800020; font-size: 14.5px; margin-bottom: 8px; font-weight: 700;">🚀 আপনার অ্যাকাউন্টে যা যা করতে পারেন:</h4><ul style="margin: 0; padding-left: 18px; color: #475569; font-size: 13.5px; line-height: 1.7;"><li>পেশাগত তথ্য, কর্মক্ষেত্র ও গবেষণার বিবরণ যুক্ত করে প্রোফাইল সমৃদ্ধ করুন।</li><li>পাবলিক ডিরেক্টরিতে সহপাঠী ও শিক্ষক-গবেষকদের সাথে কানেক্ট হন।</li><li>ডিজিটাল QR আইডি কার্ড ও মেম্বারশিপ সুবিধাসমূহ গ্রহণ করুন।</li><li>অ্যালামনাই জব পোর্টাল ও স্টোরি প্ল্যাটফর্মে অংশগ্রহণ করুন।</li></ul></div><p>পোর্টালে লগইন করে আপনার প্রোফাইল সম্পূর্ণ করুন এবং আপনার ব্যাচমেটদের সাথে যুক্ত হোন।</p>',
                'action_text' => 'Login to Alumni Portal',
                'action_url' => $siteUrl . '/login'
            ],

            'membership_paid_invoice' => [
                'name' => '2. Membership Payment Receipt (পেমেন্ট ইনভয়েস ও রসিদ)',
                'trigger' => 'সদস্যপদ ফি সফলভাবে পরিশোধ বা রিনিউয়ের পর মেম্বারকে প্রেরিত ডিজিটাল ইনভয়েস',
                'badge' => 'PAYMENT INVOICE RECEIPT',
                'subject' => '🧾 [IPH Alumni] Payment Confirmation & Invoice #INV-2026-084',
                'title' => 'সদস্যপদ ফি সফলভাবে পরিশোধিত হয়েছে!',
                'content' => '<p>প্রিয় ড. তানভীর আহমেদ,</p><p>আইপিএইচ অ্যালামনাই অ্যাসোসিয়েশনের মেম্বারশিপ ফি সফলভাবে গৃহীত হয়েছে। আপনার পেমেন্টের অফিসিয়াল মানি রিসিট ও বিবরণ নিচে দেওয়া হলো:</p><div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px; padding: 20px; margin: 20px 0; box-shadow: 0 4px 12px rgba(0,0,0,0.03);"><table style="width: 100%; font-size: 13.5px; border-collapse: collapse;"><tr style="border-bottom: 1px solid #f1f5f9;"><td style="padding: 8px 0; color: #64748b;"><strong>Invoice No:</strong></td><td style="padding: 8px 0; text-align: right; font-family: monospace; font-weight: 700; color: #1e293b;">#INV-2026-084</td></tr><tr style="border-bottom: 1px solid #f1f5f9;"><td style="padding: 8px 0; color: #64748b;"><strong>পেমেন্ট মাধ্যম:</strong></td><td style="padding: 8px 0; text-align: right; color: #1e293b;">UddoktaPay (bKash)</td></tr><tr style="border-bottom: 1px solid #f1f5f9;"><td style="padding: 8px 0; color: #64748b;"><strong>Transaction ID:</strong></td><td style="padding: 8px 0; text-align: right; font-family: monospace; font-weight: 600; color: #800020;">TRX89764512</td></tr><tr style="border-bottom: 1px solid #f1f5f9;"><td style="padding: 8px 0; color: #64748b;"><strong>তারিখ:</strong></td><td style="padding: 8px 0; text-align: right; color: #1e293b;">' . $currentDate . '</td></tr><tr style="border-bottom: 1px solid #f1f5f9;"><td style="padding: 8px 0; color: #64748b;"><strong>সদস্যপদ প্ল্যান:</strong></td><td style="padding: 8px 0; text-align: right; font-weight: 600; color: #1e293b;">Lifetime Membership</td></tr><tr><td style="padding: 12px 0 4px 0; font-size: 15px; font-weight: 700; color: #1e293b;">মোট পরিশোধিত অর্থ:</td><td style="padding: 12px 0 4px 0; text-align: right; font-size: 17px; font-weight: 800; color: #16a34a;">৳ 2,000.00 BDT (PAID)</td></tr></table></div><p>আপনার ডিজিটাল QR মেম্বারশিপ আইডি কার্ডটি পোর্টাল থেকে যেকোনো সময় সরাসরি ডাউনলোড করতে পারবেন।</p>',
                'action_text' => 'Download QR ID Card',
                'action_url' => $siteUrl . '/portal/id-card'
            ],

            'contact_request_sent' => [
                'name' => '3. Alumni Contact Request Alert (ডিরেক্টরি প্রাইভেট কন্টাক্ট অনুরোধ)',
                'trigger' => 'ডিরেক্টরি থেকে কোনো মেম্বারের নিকট যোগাযোগের অনুরোধ আসলে মেম্বারকে পাঠানো অ্যালার্ট',
                'badge' => 'NEW CONNECTION REQUEST',
                'subject' => '💬 [IPH Alumni] নতুন যোগাযোগ অনুরোধ: Research Collaboration',
                'title' => 'নতুন যোগাযোগ অনুরোধ এসেছে!',
                'content' => '<p>প্রিয় ড. তানভীর আহমেদ,</p><p>আইপিএইচ অ্যালামনাই অ্যাসোসিয়েশন ডিরেক্টরি থেকে <strong>ড. রফিকুল ইসলাম</strong> (rafiqul@example.com) আপনার সাথে যোগাযোগের একটি প্রাইভেট অনুরোধ পাঠিয়েছেন:</p><div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 16px; padding: 20px; margin: 20px 0;"><p style="margin-bottom: 6px; font-size: 13.5px; color: #64748b;"><strong>বিষয় (Topic):</strong></p><p style="color: #1e293b; font-weight: 600; margin-bottom: 12px;">Public Health Research Collaboration & Journal Paper</p><p style="margin-bottom: 6px; font-size: 13.5px; color: #64748b;"><strong>বার্তার সারসংক্ষেপ:</strong></p><p style="color: #334155; font-style: italic; background: #ffffff; padding: 12px 16px; border-radius: 10px; border: 1px solid #f1f5f9;">"Hello Dr. Tanveer, I am currently researching Epidemiology at IPH and would like to discuss possible joint journal publication and data review with you."</p></div><p>আপনার নিরাপত্তা ও গোপনীয়তা রক্ষার স্বার্থে সরাসরি আপনার ফোন নম্বর প্রকাশ করা হয়নি। আপনি পোর্টালে লগইন করে অনুরোধটি <strong>Accept</strong> করলে আপনার নির্বাচিত মাধ্যমে যোগাযোগ স্থাপন হবে।</p>',
                'action_text' => 'অনুরোধ পর্যালোচনা করুন (Portal)',
                'action_url' => $siteUrl . '/portal/contact-requests'
            ],

            'contact_request_accepted' => [
                'name' => '4. Contact Request Approved (কন্টাক্ট রিকোয়েস্ট এপ্রুভাল নোটিশ)',
                'trigger' => 'মেম্বার কন্টাক্ট রিকোয়েস্ট Accept করলে অনুরোধকারীর ইমেইলে প্রেরিত যোগাযোগের তথ্য',
                'badge' => 'REQUEST ACCEPTED',
                'subject' => '✅ [IPH Alumni] যোগাযোগের অনুরোধ গৃহীত হয়েছে: ড. তানভীর আহমেদ',
                'title' => 'যোগাযোগের অনুরোধ অনুমোদন করা হয়েছে!',
                'content' => '<p>প্রিয় ড. রফিকুল ইসলাম,</p><p>অত্যন্ত আনন্দের সাথে জানাচ্ছি যে আইপিএইচ অ্যালামনাই সদস্য <strong>ড. তানভীর আহমেদ</strong> আপনার প্রেরিত যোগাযোগের অনুরোধটি গ্রহণ (Accept) করেছেন।</p><div style="background: #f0fdf4; border: 1px solid #bbf7d0; padding: 20px; border-radius: 16px; margin: 20px 0; color: #166534;"><h4 style="margin-bottom: 10px; font-size: 15px; font-weight: 700; color: #15803d;">📋 সদস্যের যোগাযোগের বিবরণ:</h4><p style="margin-bottom: 6px; font-size: 13.5px;"><strong>পছন্দের মাধ্যম:</strong> WhatsApp Message & Email</p><p style="margin-bottom: 6px; font-size: 13.5px;"><strong>যোগাযোগের তথ্য:</strong> +880 1711-223344 (tanveer@example.com)</p><p style="margin-bottom: 0; font-size: 13.5px;"><strong>নির্দেশনা / সময়সূচি:</strong> শুধুমাত্র শনি-রবিবার সন্ধ্যা ৭টার পর হোয়াটসঅ্যাপে বার্তা দিন।</p></div><p>সদস্যের উল্লেখিত নির্দেশনা অনুসরণ করে উক্ত মাধ্যমে যোগাযোগ করার জন্য বিনীত অনুরোধ করা হলো।</p>',
                'action_text' => 'ডিরেক্টরি ভিজিট করুন',
                'action_url' => $siteUrl . '/directory'
            ],

            'membership_expiry_alert' => [
                'name' => '5. Membership Expiry Reminder (মেয়াদ শেষের পূর্ববর্তী রিমাইন্ডার)',
                'trigger' => 'সদস্যপদের মেয়াদের শেষ ৭ দিন অবশিষ্ট থাকতে স্বয়ংক্রিয়ভাবে প্রেরিত রিমাইন্ডার',
                'badge' => 'URGENT EXPIRY REMINDER',
                'subject' => '⚠️ [Urgent] Your IPH Alumni Membership Expires in 7 Days!',
                'title' => 'মেম্বারশিপের মেয়াদ সমাপ্তি সংক্রান্ত নোটিশ',
                'content' => '<p>প্রিয় ড. তানভীর আহমেদ,</p><p>আমরা আপনাকে বিনীতভাবে অবহিত করতে চাই যে আপনার আইপিএইচ অ্যালামনাই অ্যাসোসিয়েশনের বার্ষিক মেম্বারশিপের মেয়াদ আগামী <strong>৭ দিনের মধ্যে (৩১ ডিসেম্বর ২০২৬)</strong> সমাপ্ত হতে যাচ্ছে।</p><div style="background: #fffbeb; border: 1px solid #fde68a; padding: 18px 20px; border-radius: 16px; margin: 20px 0; color: #92400e;"><h4 style="font-size: 14.5px; font-weight: 700; margin-bottom: 8px;">⭐ সক্রিয় সদস্যপদের সুবিধা যা নিরবচ্ছিন্ন রাখতে চান:</h4><ul style="margin: 0; padding-left: 18px; font-size: 13px; line-height: 1.7;"><li>পাবলিক ডিরেক্টরিতে ভেরিফাইড ব্যাজ ও যোগাযোগ সুবিধা।</li><li>ডিজিটাল QR আইডি কার্ডের সক্রিয় বৈধতা।</li><li>বার্ষিক পুনর্মিলনী ও বিশেষ কনফারেন্সে মেম্বার ডিসকাউন্ট।</li></ul></div><p>পোর্টালে লগইন করে সহজেই UddoktaPay (bKash/Nagad/Cards) এর মাধ্যমে মুহূর্তেই মেম্বারশিপ রিনিউ করতে পারেন।</p>',
                'action_text' => 'Renew Membership Now',
                'action_url' => $siteUrl . '/portal/membership'
            ],

            'membership_cancelled_notice' => [
                'name' => '6. Membership Suspended Notice (মেয়াদ শেষে সদস্যপদ স্থগিতের নোটিশ)',
                'trigger' => 'নির্দিষ্ট সময়ের মধ্যে মেম্বারশিপ রিনিউ না করায় একাউন্ট সার্ভিস স্থগিতের নোটিশ',
                'badge' => 'MEMBERSHIP SUSPENDED',
                'subject' => '⚠️ [Notice] Your IPH Alumni Active Status Has Been Suspended',
                'title' => 'সদস্যপদ সাময়িকভাবে স্থগিত করা হয়েছে',
                'content' => '<p>প্রিয় ড. তানভীর আহমেদ,</p><p>আপনার মেম্বারশিপের নির্ধারিত মেয়াদ সমাপ্ত হওয়ার পর নির্ধারিত সময়ে ফি জমা না হওয়ায় আপনার <strong>Active Member Status</strong> সাময়িকভাবে স্থগিত (Suspended) করা হয়েছে।</p><div style="background: #fef2f2; border: 1px solid #fecaca; padding: 18px 20px; border-radius: 16px; margin: 20px 0; color: #991b1b;"><p style="margin: 0; font-size: 13.5px; line-height: 1.6;">পুনরায় একটিভ অ্যালামনাই মেম্বার হিসেবে সকল প্রিভিলেজ ও ডিরেক্টরি প্রোফাইল চালু করতে যেকোনো সময় আপনার পোর্টালে লগইন করে রিনিউয়াল ফি পরিশোধ করতে পারেন।</p></div>',
                'action_text' => 'Re-activate Membership',
                'action_url' => $siteUrl . '/portal/membership'
            ],

            'new_member_admin_alert' => [
                'name' => '7. New Member Registration Admin Alert (নতুন মেম্বার সাইনআপ অ্যাডমিন অ্যালার্ট)',
                'trigger' => 'নতুন কোনো অ্যালামনাই সদস্য রেজিস্ট্রেশন করলে অ্যাডমিনের নিকট প্রেরিত নোটিফিকেশন ইমেইল',
                'badge' => 'ADMIN ALERT - NEW MEMBER',
                'subject' => '🔔 [IPH Admin Alert] New Alumni Registration: Dr. Mahmudul Hasan (L-5)',
                'title' => 'নতুন মেম্বার রেজিস্ট্রেশন সম্পন্ন হয়েছে!',
                'content' => '<p>প্রিয় অ্যাডমিন,</p><p>আইপিএইচ অ্যালামনাই অ্যাসোসিয়েশন পোর্টালে নতুন একজন সদস্য একাউন্ট তৈরি করেছেন:</p><div style="background: #f8fafc; border: 1px solid #e2e8f0; padding: 18px 20px; border-radius: 16px; margin: 20px 0; color: #334155;"><table style="width: 100%; font-size: 13.5px;"><tr><td style="color: #64748b; padding: 4px 0;"><strong>সদস্যের নাম:</strong></td><td style="font-weight: 700; color: #1e293b;">Dr. Mahmudul Hasan</td></tr><tr><td style="color: #64748b; padding: 4px 0;"><strong>ইমেইল:</strong></td><td style="color: #1e293b;">mahmud@example.com</td></tr><tr><td style="color: #64748b; padding: 4px 0;"><strong>ফোন:</strong></td><td style="color: #1e293b;">+880 1812-345678</td></tr><tr><td style="color: #64748b; padding: 4px 0;"><strong>ব্যাচ বছর:</strong></td><td style="color: #1e293b;">L-5 (2015)</td></tr><tr><td style="color: #64748b; padding: 4px 0;"><strong>ডিগ্রি:</strong></td><td style="color: #1e293b;">MPH in Virology</td></tr></table></div><p>মেম্বারের তথ্য যাচাই ও অনুমোদন (Approve/Verify) করার জন্য অ্যাডমিন প্যানেলে লগইন করুন।</p>',
                'action_text' => 'Review Alumni Profile',
                'action_url' => $siteUrl . '/admin/alumni'
            ],

            'contact_form_admin_notice' => [
                'name' => '8. Contact Form Admin Notice (কন্টাক্ট ফর্মে রেসপন্সে অ্যাডমিন অ্যালার্ট)',
                'trigger' => 'ওয়েবসাইটে যেকোনো কনটাক্ট ফর্মে বার্তা জমা পড়লে অ্যাডমিনকে প্রেরিত ইমেইল',
                'badge' => 'ADMIN ALERT - CONTACT FORM',
                'subject' => '📩 [Admin Notification] New General Inquiry Submitted on Contact Form',
                'title' => 'কন্টাক্ট ফর্মে নতুন রেসপন্স জমা হয়েছে!',
                'content' => '<p>প্রিয় অ্যাডমিন,</p><p>পাবলিক ওয়েবসাইটের কন্টাক্ট ফর্ম থেকে নতুন একটি রেসপন্স রেকর্ড করা হয়েছে:</p><div style="background: #f1f5f9; border: 1px solid #cbd5e1; padding: 18px 20px; border-radius: 16px; margin: 20px 0; color: #1e293b;"><table style="width: 100%; font-size: 13.5px;"><tr><td style="color: #64748b; padding: 4px 0;"><strong>প্রেরক:</strong></td><td style="font-weight: 700; color: #1e293b;">Engr. Kamrul Ahsan</td></tr><tr><td style="color: #64748b; padding: 4px 0;"><strong>ইমেইল:</strong></td><td style="color: #1e293b;">kamrul@example.com</td></tr><tr><td style="color: #64748b; padding: 4px 0;"><strong>ফোন:</strong></td><td style="color: #1e293b;">+880 1912-889900</td></tr><tr><td style="color: #64748b; padding: 4px 0;"><strong>বিষয়:</strong></td><td style="color: #800020; font-weight: 600;">Sponsorship Enquiry for IPH Alumni Event</td></tr></table></div><p>বিস্তারিত পর্যালোচনা ও উত্তর দেওয়ার জন্য অ্যাডমিন প্যানেলে লগইন করুন।</p>',
                'action_text' => 'View Admin Dashboard',
                'action_url' => $siteUrl . '/admin/dashboard'
            ],

            'broadcast_email' => [
                'name' => '9. Email Broadcast & Mass Mail (অ্যাডমিন ব্রডকাস্ট ইমেইল)',
                'trigger' => 'অ্যাডমিন থেকে সকল বা নির্দিষ্ট ব্যাচের অ্যালামনাই সদস্যকে একযোগে প্রেরিত ইমেইল',
                'badge' => 'OFFICIAL ANNOUNCEMENT',
                'subject' => '📢 [IPH Alumni Broadcast] Annual Alumni Reunion 2026 Announcement',
                'title' => 'Annual Alumni Reunion 2026',
                'content' => '<p>প্রিয় সম্মানিত অ্যালামনাই সদস্য,</p><p>আমরা অত্যন্ত আনন্দের সাথে জানাচ্ছি যে ইনস্টিটিউট অব পাবলিক হেলথ (IPH) অ্যালামনাই অ্যাসোসিয়েশনের <strong>বাৎসরিক পুনর্মিলনী ২০২৬</strong> আগামী <strong>২৫ ডিসেম্বর ২০২৬</strong> তারিখে আইপিএইচ অডিটোরিয়ামে অনুষ্ঠিত হবে।</p><div style="background: #fdf2f8; border-left: 4px solid #db2777; padding: 18px 20px; border-radius: 0 16px 16px 0; margin: 20px 0;"><h4 style="color: #9d174d; font-size: 15px; margin-bottom: 6px; font-weight: 700;">অনুষ্ঠানের আকর্ষণসমূহ:</h4><p style="margin: 0; color: #374151; font-size: 13.5px; line-height: 1.7;">✦ স্মৃতিচারণ ও সিনিয়র-জুনিয়র প্রীতি সম্মেলন<br>✦ পাবলিক হেলথ রিসার্চ পেপার প্রেজেন্টেশন ও অ্যাওয়ার্ড প্রদান<br>✦ আকর্ষণীয় সাংস্কৃতিক অনুষ্ঠান ও গালা ডিনার</p></div><p>আমাদের অনলাইন পোর্টাল থেকে আজই আপনার ও পরিবারের টিকিট নিশ্চিত করুন।</p>',
                'action_text' => 'Register for Event',
                'action_url' => $siteUrl . '/events'
            ],

            'job_application_alert' => [
                'name' => '10. Job Application Notification (চাকরির সার্কুলার আবেদন)',
                'trigger' => 'কোনো চাকরির সার্কুলারে মেম্বার আবেদন করলে পোস্টদাতাকে পাঠানো অ্যালার্ট ইমেইল',
                'badge' => 'JOB PORTAL ALERT',
                'subject' => '💼 [IPH Job Portal] New Application for Senior Research Officer',
                'title' => 'চাকরির নতুন আবেদন জমা হয়েছে!',
                'content' => '<p>প্রিয় নিয়োগকারী,</p><p>আইপিএইচ জব পোর্টালে আপনার পোস্ট করা <strong>Senior Research Officer</strong> পদে নতুন একটি আবেদন জমা হয়েছে:</p><div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 16px; padding: 18px 20px; margin: 20px 0;"><table style="width: 100%; font-size: 13.5px;"><tr><td style="color: #64748b; padding: 4px 0;"><strong>প্রার্থীর নাম:</strong></td><td style="font-weight: 700; color: #1e293b;">ড. সাদিয়া রহমান</td></tr><tr><td style="color: #64748b; padding: 4px 0;"><strong>ব্যাচ:</strong></td><td style="color: #1e293b;">L-4 (2014)</td></tr><tr><td style="color: #64748b; padding: 4px 0;"><strong>বর্তমান পদবি:</strong></td><td style="color: #1e293b;">Research Fellow, IPH</td></tr><tr><td style="color: #64748b; padding: 4px 0;"><strong>ইমেইল:</strong></td><td style="color: #800020;">sadia.rahman@example.com</td></tr></table></div><p>প্রার্থীর বিস্তারিত সিভি ও কভার লেটার দেখতে নিচের বোতামে ক্লিক করুন।</p>',
                'action_text' => 'View Application & CV',
                'action_url' => $siteUrl . '/portal/jobs'
            ],

            'new_story_published_alert' => [
                'name' => '11. New Story Published Alert (নতুন ব্লগ/স্টোরি পাবলিশ অ্যালার্ট)',
                'trigger' => 'পোর্টালে নতুন কোনো Success Story বা ব্লগ পাবলিশ হলে সকল অ্যালামনাই মেম্বারদের পাঠানো ইমেইল',
                'badge' => 'NEW ALUMNI STORY',
                'subject' => '📖 [IPH Stories] "Pioneering Public Health Research in Bangladesh" by Dr. Anisur Rahman',
                'title' => 'নতুন অনুপ্রেরণাদায়ক স্টোরি প্রকাশিত হয়েছে!',
                'content' => '<p>প্রিয় অ্যালামনাই সদস্য,</p><p>আমাদের অ্যালামনাই পোর্টালে একটি নতুন অনুপ্রেরণাদায়ক আর্টিকল ও স্টোরি প্রকাশিত হয়েছে:</p><div style="background: linear-gradient(135deg, #fff1f2 0%, #ffe4e6 100%); border: 1px solid #fecdd3; border-radius: 16px; padding: 20px; margin: 20px 0;"><h3 style="color: #800020; font-size: 16px; font-weight: 700; margin-bottom: 6px;">Pioneering Public Health Research in Bangladesh</h3><p style="color: #9f1239; font-size: 12.5px; font-weight: 600; margin-bottom: 10px;">লেখক: ড. আনিসুর রহমান (ব্যাচ L-2, চিফ রিসার্চার)</p><p style="color: #475569; font-size: 13.5px; margin: 0; line-height: 1.6;">"পাবলিক হেলথ গবেষণায় বাংলাদেশের অভাবনীয় অর্জন, আন্তর্জাতিক জার্নালে অবদান এবং ভবিষ্যৎ সুযোগ নিয়ে এক সমৃদ্ধ আলোচনা..."</p></div><p>সম্পূর্ণ স্টোরিটি পড়তে ও মতামত প্রদান করতে নিচের লিঙ্কে ভিজিট করুন।</p>',
                'action_text' => 'Read Full Story',
                'action_url' => $siteUrl . '/stories'
            ],

            'contact_form' => [
                'name' => '12. Contact Form User Message (ওয়েবসাইট কন্টাক্ট ফর্ম)',
                'trigger' => 'যখন কোনো ব্যবহারকারী ওয়েবসাইট কন্টাক্ট ফর্মে বার্তা পাঠান',
                'badge' => 'WEBSITE INQUIRY',
                'subject' => '📩 [IPH Alumni] New Contact Inquiry from Dr. Rafiqul Islam',
                'title' => 'নতুন কন্টাক্ট বার্তা এসেছে!',
                'content' => '<p>ওয়েবসাইট কন্টাক্ট ফর্ম থেকে নতুন একটি বার্তা জমা পড়েছে:</p><div style="background: #f8fafc; border-left: 4px solid #800020; padding: 18px 20px; border-radius: 0 16px 16px 0; margin: 20px 0;"><p style="margin-bottom: 6px; font-size: 13px; color: #64748b;"><strong>প্রেরকের নাম:</strong> Dr. Rafiqul Islam</p><p style="margin-bottom: 6px; font-size: 13px; color: #64748b;"><strong>ইমেইল:</strong> rafiqul@example.com</p><p style="margin-bottom: 12px; font-size: 13px; color: #64748b;"><strong>বিষয়:</strong> International Public Health Conference Details</p><p style="color: #1e293b; font-style: italic; background: #ffffff; padding: 12px 14px; border-radius: 10px; border: 1px solid #e2e8f0; margin: 0;">"Hello, I would like to know more about the upcoming IPH International Public Health Conference 2026 and registration details."</p></div><p>বার্তাটির উত্তর প্রদান করতে অ্যাডমিন প্যানেলে লগইন করুন।</p>',
                'action_text' => 'View Contact Submissions',
                'action_url' => $siteUrl . '/admin/settings'
            ]
        ];
    }

    /**
     * Configure runtime SMTP settings from database Settings
     */
    public static function configureSmtp(): void
    {
        try {
            $setting     = new Setting();
            $host        = (string) $setting->get('mail_host', env('MAIL_HOST', ''));
            $port        = (int) ($setting->get('mail_port', env('MAIL_PORT', 587)) ?: 587);
            $encryption  = strtolower((string) $setting->get('mail_encryption', env('MAIL_ENCRYPTION', 'tls')));
            $username    = (string) $setting->get('mail_username', env('MAIL_USERNAME', ''));
            $password    = (string) $setting->get('mail_password', env('MAIL_PASSWORD', ''));
            $fromAddress = (string) $setting->get('mail_from_address', env('MAIL_FROM_ADDRESS', 'contact@iphalumni.dev.cv'));
            $fromName    = (string) $setting->get('mail_from_name', env('MAIL_FROM_NAME', 'IPH Alumni Association'));

            if (!empty($host)) {
                $scheme = null;
                $mailEncryption = null;
                if ($encryption === 'ssl' || $port === 465) {
                    $scheme = 'smtps';
                    $mailEncryption = 'ssl';
                } elseif ($encryption === 'tls' || $port === 587) {
                    $scheme = null;
                    $mailEncryption = 'tls';
                } elseif ($encryption === 'none' || $port === 25) {
                    $scheme = null;
                    $mailEncryption = null;
                } else {
                    $scheme = null;
                    $mailEncryption = $encryption ?: null;
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
                Mail::clearResolvedInstances();
            }
        } catch (\Throwable $e) {
            Log::warning('SMTP Config dynamic setup error: ' . $e->getMessage());
        }
    }

    /**
     * Send email using configured SMTP / Driver
     *
     * @param string $to
     * @param string $subject
     * @param array|string $templateParamsOrHtml
     * @return array ['success' => bool, 'message' => string]
     */
    public static function send(string $to, string $subject, array|string $templateParamsOrHtml): array
    {
        self::configureSmtp();

        try {
            if (is_array($templateParamsOrHtml)) {
                $html = self::renderHtml($templateParamsOrHtml);
            } else {
                $html = self::renderHtml([
                    'title'   => $subject,
                    'badge'   => 'VERIFICATION / NOTIFICATION',
                    'content' => $templateParamsOrHtml,
                ]);
            }

            $settingModel = new Setting();
            $fromAddress  = (string) $settingModel->get('mail_from_address', env('MAIL_FROM_ADDRESS', 'no-reply@iphalumni.dev.cv'));
            $fromName     = (string) $settingModel->get('mail_from_name', env('MAIL_FROM_NAME', 'IPH Alumni Association'));

            Mail::html($html, function ($message) use ($to, $subject, $fromAddress, $fromName) {
                $message->to($to)
                        ->subject($subject)
                        ->from($fromAddress, $fromName);
            });

            return [
                'success' => true,
                'message' => "ইমেইলটি সফলভাবে '{$to}' ঠিকানায় পাঠানো হয়েছে!",
            ];
        } catch (\Throwable $e) {
            Log::error('MailService send error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'ইমেইল পাঠাতে ত্রুটি হয়েছে: ' . $e->getMessage(),
            ];
        }
    }
}
