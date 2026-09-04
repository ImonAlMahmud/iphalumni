<?php
declare(strict_types=1);

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\BaseController;
use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class JobController extends BaseController
{
    private Job $jobModel;

    public function __construct()
    {
        $this->jobModel = new Job();
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $myJobsResult = $this->jobModel->search(['user_id' => $user->id, 'status' => ''], 1, 50);
        $myJobs       = $myJobsResult['items'];

        return $this->legacyView('portal/jobs/index', compact('myJobs'), 'portal', 'Job Postings');
    }

    public function create(Request $request)
    {
        return $this->legacyView('portal/jobs/create', [], 'portal', 'Post a New Job');
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $title       = trim((string)$request->input('title', ''));
        $company     = trim((string)$request->input('company_name', ''));
        $jobType     = trim((string)$request->input('job_type', 'Full-time'));
        $location    = trim((string)$request->input('location', ''));
        $salary      = trim((string)$request->input('salary_range', ''));
        $deadline    = trim((string)$request->input('deadline', ''));
        $description = trim((string)$request->input('description', ''));
        $reqs        = trim((string)$request->input('requirements', ''));
        $howToApply  = trim((string)$request->input('how_to_apply', ''));
        $visibility  = trim((string)$request->input('visibility', 'members'));
        $applyType   = trim((string)$request->input('apply_type', 'portal'));
        $applyLink   = trim((string)$request->input('apply_link', ''));
        $applyEmail  = trim((string)$request->input('apply_email', ''));

        if ($title === '' || $company === '' || $description === '') {
            return redirect('/portal/jobs/create')->with('error', 'শিরোনাম, প্রতিষ্ঠানের নাম এবং বিবরণ পূরণ করা আবশ্যক।')->withInput();
        }

        if (!in_array($visibility, ['members', 'public'], true)) {
            $visibility = 'members';
        }
        if (!in_array($applyType, ['portal', 'external_link', 'email'], true)) {
            $applyType = 'portal';
        }

        $jobId = DB::table('jobs')->insertGetId([
            'user_id'      => (int)$user->id,
            'title'        => $title,
            'company_name' => $company,
            'job_type'     => $jobType,
            'location'     => $location,
            'salary_range' => $salary ?: null,
            'deadline'     => $deadline ?: null,
            'description'  => $description,
            'requirements' => $reqs ?: null,
            'how_to_apply' => $howToApply ?: null,
            'visibility'   => $visibility,
            'apply_type'   => $applyType,
            'apply_link'   => $applyLink ?: null,
            'apply_email'  => $applyEmail ?: null,
            'status'       => 'active',
            'created_at'   => now(),
            'updated_at'   => now(),
        ]);

        if ($jobId) {
            return redirect('/portal/jobs')->with('success', 'জব সার্কুলারটি সফলভাবে পোস্ট করা হয়েছে!');
        }

        return redirect('/portal/jobs/create')->with('error', 'জব সার্কুলার পোস্ট করতে সমস্যা হয়েছে।');
    }

    public function edit(Request $request, $id)
    {
        $user  = Auth::user();
        $jobId = (int)$id;
        $job   = $this->jobModel->findWithPoster($jobId);

        $isAdmin = in_array($user->role, ['admin', 'super_admin', 'editor']);
        $isCreator = $job && (int)$job['user_id'] === (int)$user->id;

        if (!$job || (!$isCreator && !$isAdmin)) {
            return redirect('/portal/jobs')->with('error', 'জবটি সম্পাদনা করার অনুমতি নেই।');
        }

        return $this->legacyView('portal/jobs/edit', compact('job'), 'portal', 'Edit Job Posting');
    }

    public function update(Request $request, $id)
    {
        $user  = Auth::user();
        $jobId = (int)$id;
        $job   = $this->jobModel->findWithPoster($jobId);

        $isAdmin = in_array($user->role, ['admin', 'super_admin', 'editor']);
        $isCreator = $job && (int)$job['user_id'] === (int)$user->id;

        if (!$job || (!$isCreator && !$isAdmin)) {
            return redirect('/portal/jobs')->with('error', 'জবটি আপডেট করার অনুমতি নেই।');
        }

        $title       = trim((string)$request->input('title', ''));
        $company     = trim((string)$request->input('company_name', ''));
        $jobType     = trim((string)$request->input('job_type', 'Full-time'));
        $location    = trim((string)$request->input('location', ''));
        $salary      = trim((string)$request->input('salary_range', ''));
        $deadline    = trim((string)$request->input('deadline', ''));
        $description = trim((string)$request->input('description', ''));
        $reqs        = trim((string)$request->input('requirements', ''));
        $howToApply  = trim((string)$request->input('how_to_apply', ''));
        $visibility  = trim((string)$request->input('visibility', 'members'));
        $applyType   = trim((string)$request->input('apply_type', 'portal'));
        $applyLink   = trim((string)$request->input('apply_link', ''));
        $applyEmail  = trim((string)$request->input('apply_email', ''));
        $status      = trim((string)$request->input('status', 'active'));

        if ($title === '' || $company === '' || $description === '') {
            return redirect('/portal/jobs/' . $jobId . '/edit')->with('error', 'শিরোনাম, প্রতিষ্ঠানের নাম এবং বিবরণ পূরণ করা আবশ্যক।');
        }

        DB::table('jobs')->where('id', $jobId)->update([
            'title'        => $title,
            'company_name' => $company,
            'job_type'     => $jobType,
            'location'     => $location,
            'salary_range' => $salary ?: null,
            'deadline'     => $deadline ?: null,
            'description'  => $description,
            'requirements' => $reqs ?: null,
            'how_to_apply' => $howToApply ?: null,
            'visibility'   => $visibility,
            'apply_type'   => $applyType,
            'apply_link'   => $applyLink ?: null,
            'apply_email'  => $applyEmail ?: null,
            'status'       => in_array($status, ['active', 'closed'], true) ? $status : 'active',
            'updated_at'   => now(),
        ]);

        return redirect('/portal/jobs')->with('success', 'জব সার্কুলারটি সফলভাবে আপডেট করা হয়েছে!');
    }

    public function applications(Request $request, $id)
    {
        $user  = Auth::user();
        $jobId = (int)$id;
        $job   = $this->jobModel->findWithPoster($jobId);

        $isAdmin = in_array($user->role, ['admin', 'super_admin', 'editor']);
        $isCreator = $job && (int)$job['user_id'] === (int)$user->id;

        if (!$job || (!$isCreator && !$isAdmin)) {
            return redirect('/portal/jobs')->with('error', 'জবটি পাওয়া যায়নি বা দেখার অনুমতি নেই।');
        }

        $applications = $this->jobModel->getApplicationsForJob($jobId);

        return $this->legacyView('portal/jobs/applications', compact('job', 'applications'), 'portal', 'Job Applications');
    }

    public function toggleStatus(Request $request)
    {
        $user  = Auth::user();
        $jobId = (int)$request->input('job_id', 0);
        $job   = $this->jobModel->findWithPoster($jobId);

        $isAdmin = in_array($user->role, ['admin', 'super_admin', 'editor']);
        $isCreator = $job && (int)$job['user_id'] === (int)$user->id;

        if ($job && ($isCreator || $isAdmin)) {
            $newStatus = $job['status'] === 'active' ? 'closed' : 'active';
            DB::table('jobs')->where('id', $jobId)->update(['status' => $newStatus, 'updated_at' => now()]);
            return redirect('/portal/jobs')->with('success', 'জব স্ট্যাটাস আপডেট করা হয়েছে।');
        }

        return redirect('/portal/jobs');
    }
}
