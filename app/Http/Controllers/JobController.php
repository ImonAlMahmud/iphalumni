<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Job;
use App\Services\UploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobController extends BaseController
{
    private Job $jobModel;

    public function __construct()
    {
        $this->jobModel = new Job();
    }

    public function index(Request $request)
    {
        $page = max(1, (int)$request->input('page', 1));
        $q    = trim((string)$request->input('q', ''));
        $type = trim((string)$request->input('job_type', ''));

        $isLoggedIn = Auth::check();

        $filters = [
            'q'        => $q,
            'job_type' => $type,
            'status'   => 'active',
        ];

        if (!$isLoggedIn) {
            $filters['visibility'] = 'public';
        }

        $result = $this->jobModel->search($filters, $page, 10);

        $subCaptcha = '';
        if (!$isLoggedIn) {
            $s1 = rand(2, 9);
            $s2 = rand(1, 9);
            session(['sub_captcha_ans' => ($s1 + $s2)]);
            $subCaptcha = "{$s1} + {$s2}";
        }

        return $this->legacyView('jobs/index', compact('result', 'filters', 'q', 'type', 'isLoggedIn', 'subCaptcha'), 'main', 'Job Circulars');
    }

    public function show(Request $request, $id)
    {
        $id  = (int)$id;
        $job = $this->jobModel->findWithPoster($id);

        if (!$job) {
            return redirect('/jobs')->with('error', 'Job posting not found.');
        }

        $user       = Auth::user();
        $isLoggedIn = !empty($user);
        $userArr    = $user ? $user->toArray() : null;

        // Visibility check
        if ($job['visibility'] === 'members' && !$isLoggedIn) {
            return redirect('/login')->with('error', 'এই জব সার্কুলারটি দেখতে এবং আবেদন করতে অনুগ্রহ করে লগইন করুন।');
        }

        $isVerifiedStudent = false;
        $studentInfo       = null;
        $hasApplied        = false;

        if ($isLoggedIn) {
            $studentInfo       = $this->jobModel->isUserVerifiedStudent((int)$user->id);
            $isVerifiedStudent = !empty($studentInfo);
            $hasApplied        = $this->jobModel->hasApplied($id, (int)$user->id);
        }

        $captchaQuestion = '';
        if (!$isLoggedIn) {
            $num1 = rand(2, 9);
            $num2 = rand(1, 9);
            session(['job_captcha_ans' => ($num1 + $num2)]);
            $captchaQuestion = "{$num1} + {$num2}";
        }

        return $this->legacyView('jobs/show', [
            'job'               => $job,
            'user'              => $userArr,
            'isLoggedIn'        => $isLoggedIn,
            'isVerifiedStudent' => $isVerifiedStudent,
            'studentInfo'       => $studentInfo,
            'hasApplied'        => $hasApplied,
            'captchaQuestion'   => $captchaQuestion,
        ], 'main', (string)$job['title']);
    }

    public function apply(Request $request)
    {
        $jobId = (int)$request->input('job_id', 0);
        $job   = $this->jobModel->findWithPoster($jobId);

        if (!$job || $job['status'] !== 'active') {
            return redirect('/jobs')->with('error', 'জব সার্কুলারটি পাওয়া যায়নি বা বন্ধ হয়ে গেছে।');
        }

        $user = Auth::user();

        // If members only, require login
        if ($job['visibility'] === 'members' && !$user) {
            return redirect('/login')->with('error', 'এই মেম্বার-অনলি পদে আবেদন করতে আপনাকে লগইন করতে হবে।');
        }

        // Math captcha check for guest applicants
        if (!$user) {
            $userCaptcha = trim((string)$request->input('captcha_answer', ''));
            $expectedCaptcha = session('job_captcha_ans');

            if ($expectedCaptcha === null || $userCaptcha === '' || (int)$userCaptcha !== (int)$expectedCaptcha) {
                return redirect('/jobs/' . $jobId)->with('error', 'স্প্যাম ও রোবট প্রতিরোধে ম্যাথ ক্যাপচা উত্তরটি সঠিক হয়নি। দয়া করে সঠিক হিসাব করে পুনরায় চেষ্টা করুন।');
            }
        }

        $applicantName  = trim((string)$request->input('applicant_name', $user->name ?? ''));
        $applicantEmail = trim((string)$request->input('applicant_email', $user->email ?? ''));
        $applicantPhone = trim((string)$request->input('applicant_phone', ''));

        if (empty($applicantName) || empty($applicantEmail)) {
            return redirect('/jobs/' . $jobId)->with('error', 'নাম এবং ইমেইল ঠিকানা প্রদান করা বাধ্যতামূলক।');
        }

        $studentInfo = null;
        if ($job['visibility'] === 'public') {
            $studentInfo = $this->jobModel->isUserVerifiedStudent($user ? (int)$user->id : null, $applicantName, $applicantPhone, $applicantEmail);
            if (!$studentInfo) {
                return redirect('/jobs/' . $jobId)->with('error', 'আবেদন ব্যর্থ হয়েছে: আপনার প্রদানকৃত নাম এবং মোবাইল / গার্ডিয়ান মোবাইল নম্বরটি "Student Reference Database" এর সাথে হুবহু মিলেনি। অনুগ্রহ করে আইপিএইচ ভর্তি ফরমে দেওয়া সঠিক নামের বানান ও ফোন নম্বরটি ব্যবহার করুন।');
            }
        }

        if ($this->jobModel->hasApplied($jobId, $user ? (int)$user->id : null, $applicantEmail)) {
            return redirect('/jobs/' . $jobId)->with('error', 'আপনি ইতিমধ্যে এই জবে আবেদন করেছেন।');
        }

        // Handle Resume Upload
        $resumePath = null;
        $file       = $request->file('resume');
        if ($file && $file->isValid()) {
            $uploader   = new UploadService();
            $resumePath = $uploader->uploadDocument($file, 'job_resumes');
        }

        $appData = [
            'job_id'               => $jobId,
            'user_id'              => $user ? (int)$user->id : null,
            'student_reference_id' => $studentInfo['id'] ?? null,
            'applicant_name'       => $applicantName,
            'applicant_email'      => $applicantEmail,
            'applicant_phone'      => $applicantPhone,
            'resume_path'          => $resumePath,
            'cover_letter'         => trim((string)$request->input('cover_letter', '')),
        ];

        $inserted = $this->jobModel->apply($appData);

        if ($inserted) {
            session()->forget('job_captcha_ans');
            return redirect('/jobs/' . $jobId)->with('success', 'আপনার আবেদনটি সফলভাবে জমা হয়েছে!');
        }

        return redirect('/jobs/' . $jobId)->with('error', 'আবেদন জমা দিতে সমস্যা হয়েছে। পুনরায় চেষ্টা করুন।');
    }

    public function subscribe(Request $request)
    {
        $user  = Auth::user();
        $email = trim((string)$request->input('email', $user->email ?? ''));
        $name  = trim((string)$request->input('name', $user->name ?? ''));
        $jobTypes = $request->input('job_types', []);
        $jobTypesStr = is_array($jobTypes) ? implode(', ', array_filter($jobTypes)) : (string)$jobTypes;

        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return back()->with('error', 'অনুগ্রহ করে একটি সঠিক ইমেইল ঠিকানা প্রদান করুন।')->withInput();
        }

        // Math captcha check for guest subscribers
        if (!$user) {
            $userCaptcha = trim((string)$request->input('sub_captcha_answer', ''));
            $expectedCaptcha = session('sub_captcha_ans');

            if ($expectedCaptcha === null || $userCaptcha === '' || (int)$userCaptcha !== (int)$expectedCaptcha) {
                return back()->with('error', 'স্প্যাম প্রতিরোধে ক্যাপচা উত্তরটি সঠিক হয়নি। দয়া করে সঠিক যোগফলটি লিখুন।')->withInput();
            }
            session()->forget('sub_captcha_ans');
        }

        $existing = \Illuminate\Support\Facades\DB::table('job_alert_subscriptions')->where('email', $email)->first();
        $token = bin2hex(random_bytes(24));

        if ($existing) {
            \Illuminate\Support\Facades\DB::table('job_alert_subscriptions')
                ->where('id', $existing->id)
                ->update([
                    'name'       => $name ?: $existing->name,
                    'job_types'  => $jobTypesStr ?: $existing->job_types,
                    'status'     => 'active',
                    'updated_at' => now(),
                ]);
            $token = $existing->token;
        } else {
            \Illuminate\Support\Facades\DB::table('job_alert_subscriptions')->insert([
                'email'      => $email,
                'name'       => $name ?: null,
                'job_types'  => $jobTypesStr ?: null,
                'token'      => $token,
                'status'     => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Send confirmation email via MailService
        try {
            $unsubUrl = url('/jobs/unsubscribe/' . $token);
            $emailContent = '
                <div style="line-height: 1.7; color: #1e293b;">
                    <p style="font-size: 16px;">প্রিয় <strong>' . htmlspecialchars($name ?: 'মেম্বার / আবেদনকারী') . '</strong>,</p>
                    <p>আইপিএইচ অ্যালামনাই অ্যাসোসিয়েশন জব পোর্টালে আপনার ইমেইল (<code>' . htmlspecialchars($email) . '</code>) সফলভাবে সাবস্ক্রাইব করা হয়েছে।</p>
                    <p>এখন থেকে নতুন কোনো সরকারি, বেসরকারি বা আন্তর্জাতিক স্বাস্থ্য সংস্থার জব সার্কুলার প্রকাশিত হলে আপনি সবার আগে সরাসরি আপনার ইনবক্সে নোটিফিকেশন পাবেন।</p>
                    <div style="margin: 20px 0; padding: 16px; background: #f8fafc; border-left: 4px solid #800020; border-radius: 8px;">
                        <strong style="color: #800020;">আপনার সাবস্ক্রিপশন পছন্দ:</strong> ' . htmlspecialchars($jobTypesStr ?: 'সকল প্রকার চাকরি') . '
                    </div>
                    <p style="font-size: 13px; color: #64748b; margin-top: 25px;">ভবিষ্যতে আর এলার্ট পেতে না চাইলে যেকোনো সময় <a href="' . $unsubUrl . '" style="color: #800020; text-decoration: underline;">আনসাবস্ক্রাইব করতে এখানে ক্লিক করুন</a>।</p>
                </div>
            ';

            \App\Services\MailService::send($email, 'IPH Alumni Job Alerts — সাবস্ক্রিপশন সফল হয়েছে!', [
                'title'       => 'জব এলার্ট সাবস্ক্রিপশন সম্পন্ন',
                'subtitle'    => 'আইপিএইচ নতুন চাকরির খবর ও বিজ্ঞপ্তি সার্ভিস',
                'badge'       => 'JOB ALERT NOTIFICATION',
                'content'     => $emailContent,
                'action_text' => 'চলমান জব সার্কুলারগুলো দেখুন',
                'action_url'  => url('/jobs'),
            ]);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('Job alert subscription email send error: ' . $e->getMessage());
        }

        return back()->with('success', 'অভিনন্দন! আপনার জব এলার্ট সাবস্ক্রিপশন সফলভাবে সম্পন্ন হয়েছে। নতুন সার্কুলার এলে আপনার ইমেইলে নোটিফিকেশন পৌঁছে যাবে।');
    }

    public function unsubscribe(Request $request, $token)
    {
        $token = trim((string)$token);
        $sub = \Illuminate\Support\Facades\DB::table('job_alert_subscriptions')->where('token', $token)->first();

        if (!$sub) {
            return redirect('/jobs')->with('error', 'সাবস্ক্রিপশন রেকর্ডটি পাওয়া যায়নি বা ইতিমধ্যে বাতিল হয়েছে।');
        }

        \Illuminate\Support\Facades\DB::table('job_alert_subscriptions')
            ->where('id', $sub->id)
            ->update(['status' => 'unsubscribed', 'updated_at' => now()]);

        return redirect('/jobs')->with('success', 'আপনার জব এলার্ট সাবস্ক্রিপশনটি সফলভাবে আনসাবস্ক্রাইব করা হয়েছে।');
    }
}
