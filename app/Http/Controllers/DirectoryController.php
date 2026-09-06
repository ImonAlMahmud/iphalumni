<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\AlumniProfile;
use App\Models\Setting;
use App\Services\MailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DirectoryController extends BaseController
{
    public function index(Request $request)
    {
        $settingModel      = new Setting();
        $requireMembership = $settingModel->get('directory_require_membership', '0') === '1';

        $model   = new AlumniProfile();
        $filters = [
            'q'                  => trim((string)$request->input('q', '')),
            'batch'              => trim((string)$request->input('batch', '')),
            'university'         => trim((string)$request->input('university', '')),
            'programme'          => trim((string)$request->input('programme', '')),
            'phone'              => trim((string)$request->input('phone', '')),
            'designation'        => trim((string)$request->input('designation', '')),
            'organization'       => trim((string)$request->input('organization', '')),
            'location'           => trim((string)$request->input('location', '')),
            'country'            => trim((string)$request->input('country', '')),
            'location_type'      => trim((string)$request->input('location_type', '')),
            'is_featured'        => !empty($request->input('is_featured')) ? 1 : 0,
            'require_membership' => $requireMembership,
        ];
        $page   = max(1, (int)$request->input('page', 1));

        $result = $model->search($filters, $page, 12);

        $batchQuery = DB::table('alumni_profiles as ap')
            ->whereNotNull('ap.batch_year')
            ->whereIn('ap.status', ['approved', 'verified', 'active'])
            ->whereNull('ap.deleted_at');

        if ($requireMembership) {
            $batchQuery->join('memberships as m', function ($join) {
                $join->on('m.alumni_profile_id', '=', 'ap.id')
                     ->where('m.status', '=', 'active')
                     ->whereNull('m.deleted_at');
            });
        }

        $batches = $batchQuery
            ->distinct()
            ->orderBy('ap.batch_year', 'desc')
            ->pluck('ap.batch_year')
            ->toArray();

        return $this->legacyView('directory/index', compact('result', 'batches', 'filters'), 'main', 'Alumni Directory');
    }

    public function show(Request $request, $id)
    {
        $id = (int)$id;
        $alumni = DB::table('alumni_profiles as ap')
            ->join('users as u', 'u.id', '=', 'ap.user_id')
            ->select('ap.*', 'u.name', 'u.email')
            ->where('ap.id', $id)
            ->whereIn('ap.status', ['approved', 'verified', 'active'])
            ->whereNull('ap.deleted_at')
            ->first();

        if (!$alumni) {
            abort(404, 'Profile not found');
        }

        $alumni = (array)$alumni;

        $settingModel      = new Setting();
        $requireMembership = $settingModel->get('directory_require_membership', '0') === '1';

        if ($requireMembership) {
            $hasActiveMembership = DB::table('memberships')
                ->where('alumni_profile_id', $id)
                ->where('status', 'active')
                ->whereNull('deleted_at')
                ->exists();

            $currentUser = auth();
            $isOwner = $currentUser && (int)($currentUser['id'] ?? 0) === (int)$alumni['user_id'];
            $isAdmin = is_admin();

            if (!$hasActiveMembership && !$isOwner && !$isAdmin) {
                abort(404, 'Profile not found');
            }
        }

        $model      = new AlumniProfile();
        $education  = $model->getEducation($id);
        $employment = $model->getEmployment($id);

        $committeeMember = DB::table('committee_members as cm')
            ->leftJoin('committees as c', 'c.id', '=', 'cm.committee_id')
            ->where('cm.user_id', $alumni['user_id'])
            ->where('cm.is_active', 1)
            ->whereNull('cm.deleted_at')
            ->select('cm.designation', 'cm.committee_type', 'c.name as committee_name')
            ->orderBy('cm.sort_order', 'asc')
            ->first();

        return $this->legacyView('directory/profile', compact('alumni', 'education', 'employment', 'committeeMember'), 'main', (string)$alumni['name']);
    }

    public function sendContactRequest(Request $request, $id)
    {
        $alumniId = (int)$id;
        $name     = trim((string)$request->input('requester_name', ''));
        $email    = trim((string)$request->input('requester_email', ''));
        $phone    = trim((string)$request->input('requester_phone', ''));
        $topic    = trim((string)$request->input('discussion_topic', ''));
        $brief    = trim((string)$request->input('brief_message', ''));

        // Validate Math Captcha
        $captchaInput    = trim((string)$request->input('captcha_input', ''));
        $expectedCaptcha = session('captcha_answer');
        if ($expectedCaptcha === null || (int)$captchaInput !== (int)$expectedCaptcha) {
            return back()->with('error', 'ক্যাপচা (Security Question) উত্তর সঠিক নয়। দয়া করে আবার চেষ্টা করুন।')->withInput();
        }

        if (empty($name) || empty($email) || empty($topic) || empty($brief)) {
            return back()->with('error', 'দয়া করে আপনার নাম, ইমেইল, বিষয় এবং বার্তার সারসংক্ষেপ প্রদান করুন।')->withInput();
        }

        $alumni = DB::table('alumni_profiles as ap')
            ->join('users as u', 'u.id', '=', 'ap.user_id')
            ->select('ap.id', 'u.id as user_id', 'u.name', 'u.email')
            ->where('ap.id', $alumniId)
            ->first();

        if (!$alumni) {
            return back()->with('error', 'সদস্য প্রোফাইল পাওয়া যায়নি।');
        }
        $alumni = (array)$alumni;

        // Insert Request
        $requestId = DB::table('contact_requests')->insertGetId([
            'alumni_profile_id' => $alumniId,
            'requester_name'    => $name,
            'requester_email'   => $email,
            'requester_phone'   => $phone,
            'discussion_topic'  => $topic,
            'brief_message'     => $brief,
            'status'            => 'pending',
            'created_at'        => now(),
        ]);

        // Send Email Alert to Alumni Member
        $mailService = new MailService();
        $portalUrl   = url('/portal/contact-requests');
        $htmlBody = '<p>প্রিয় ' . e($alumni['name']) . ',</p>' .
                    '<p>আইপিএইচ অ্যালামনাই অ্যাসোসিয়েশন ডিরেক্টরি থেকে <strong>' . e($name) . '</strong> (' . e($email) . ') আপনার সাথে যোগাযোগের জন্য একটি অনুরোধ পাঠিয়েছেন:</p>' .
                    '<blockquote style="border-left:4px solid #800020;padding-left:12px;margin:16px 0;color:#333;">' .
                    '<strong>বিষয় (Topic):</strong> ' . e($topic) . '<br>' .
                    '<strong>বার্তার সারসংক্ষেপ:</strong> ' . e($brief) .
                    '</blockquote>' .
                    '<p>আপনার পোর্টালে লগইন করে এই অনুরোধটি Accept করলে আপনার পছন্দের যোগাযোগ মাধ্যম (Email/WhatsApp/Phone) উক্ত ইমেইলে পাঠিয়ে দেওয়া হবে।</p>';

        $mailService->send(
            $alumni['email'],
            '[IPH Alumni] নতুন যোগাযোগ অনুরোধ: ' . mb_substr($topic, 0, 40),
            $htmlBody
        );

        // In-app Notification for Member
        DB::table('notifications')->insert([
            'user_id'    => $alumni['user_id'],
            'title'      => 'নতুন যোগাযোগ অনুরোধ: ' . mb_substr($name, 0, 20),
            'message'    => $name . ' আপনার সাথে ডিরেক্টরি থেকে যোগাযোগের অনুরোধ করেছেন।',
            'link'       => '/portal/contact-requests',
            'is_read'    => 0,
            'created_at' => now(),
        ]);

        return back()->with('success', 'আপনার যোগাযোগের অনুরোধ সফলভাবে পাঠানো হয়েছে! অ্যালামনাই সদস্য অনুরোধটি এপ্রুভ (Accept) করলে আপনার ইমেইলে তার সাথে যোগাযোগের প্রয়োজনীয় মাধ্যম পৌছে যাবে।');
    }
}
