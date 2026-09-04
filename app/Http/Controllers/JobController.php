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

        return $this->legacyView('jobs/index', compact('result', 'filters', 'q', 'type'), 'main', 'Job Circulars');
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

        return $this->legacyView('jobs/show', compact('job', 'isVerifiedStudent', 'studentInfo', 'hasApplied'), 'main', (string)$job['title']);
    }

    public function apply(Request $request)
    {
        if (!Auth::check()) {
            return redirect('/login')->with('error', 'আবেদন করতে আপনাকে লগইন করতে হবে।');
        }

        $user  = Auth::user();
        $jobId = (int)$request->input('job_id', 0);
        $job   = $this->jobModel->findWithPoster($jobId);

        if (!$job || $job['status'] !== 'active') {
            return redirect('/jobs')->with('error', 'জব সার্কুলারটি পাওয়া যায়নি বা বন্ধ হয়ে গেছে।');
        }

        $applicantName  = trim((string)$request->input('applicant_name', $user->name));
        $applicantPhone = trim((string)$request->input('applicant_phone', ''));

        $studentInfo = $this->jobModel->isUserVerifiedStudent((int)$user->id, $applicantName, $applicantPhone);

        if ($job['visibility'] === 'public' && !$studentInfo) {
            return redirect('/jobs/' . $jobId)->with('error', 'আবেদন ব্যর্থ হয়েছে: আপনার প্রদানকৃত নাম এবং ফোন নম্বর / গার্ডিয়ান ফোন নম্বরটি "Student Reference Database" এর সাথে হুবহু মিলেনি। অনুগ্রহ করে সঠিক বানান ও ভর্তি ফরমে দেয়া নম্বরটি ব্যবহার করুন।');
        }

        if ($this->jobModel->hasApplied($jobId, (int)$user->id)) {
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
            'user_id'              => (int)$user->id,
            'student_reference_id' => $studentInfo['id'] ?? null,
            'applicant_name'       => trim((string)$request->input('applicant_name', $user->name)),
            'applicant_email'      => trim((string)$request->input('applicant_email', $user->email)),
            'applicant_phone'      => trim((string)$request->input('applicant_phone', '')),
            'resume_path'          => $resumePath,
            'cover_letter'         => trim((string)$request->input('cover_letter', '')),
        ];

        $inserted = $this->jobModel->apply($appData);

        if ($inserted) {
            return redirect('/jobs/' . $jobId)->with('success', 'আপনার আবেদনটি সফলভাবে জমা হয়েছে!');
        }

        return redirect('/jobs/' . $jobId)->with('error', 'আবেদন জমা দিতে সমস্যা হয়েছে। পুনরায় চেষ্টা করুন।');
    }
}
