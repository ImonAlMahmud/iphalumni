<?php
declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Models\AlumniProfile;
use App\Services\AuditLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AlumniController extends BaseController
{
    private AlumniProfile $model;

    public function __construct()
    {
        $this->model = new AlumniProfile();
    }

    public function index(Request $request)
    {
        $page   = max(1, (int)$request->input('page', 1));
        $status = $request->input('status', '');
        $search = $request->input('q', '');

        $query = DB::table('alumni_profiles as ap')
            ->join('users as u', 'u.id', '=', 'ap.user_id')
            ->whereNull('ap.deleted_at');

        if ($status) {
            $query->where('ap.status', $status);
        }
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('u.name', 'like', "%{$search}%")
                  ->orWhere('u.email', 'like', "%{$search}%");
            });
        }

        $total = $query->count();
        $perPage = 15;
        $offset  = ($page - 1) * $perPage;

        $alumni = $query->select('ap.*', 'u.name', 'u.email', 'u.created_at as registered_at')
            ->orderBy('ap.created_at', 'desc')
            ->offset($offset)
            ->limit($perPage)
            ->get()
            ->map(fn($r) => (array)$r)
            ->toArray();

        $pagination = ['total' => $total, 'per_page' => $perPage, 'current_page' => $page, 'last_page' => (int)ceil($total / $perPage)];

        return $this->legacyView(
            'admin/alumni/index',
            compact('alumni', 'pagination', 'status', 'search'),
            'admin',
            'Alumni Management'
        );
    }

    public function show(Request $request, $id)
    {
        $id = (int)$id;
        $alumni = DB::table('alumni_profiles as ap')
            ->join('users as u', 'u.id', '=', 'ap.user_id')
            ->select('ap.*', 'u.name', 'u.email', 'u.role', 'u.created_at as registered_at')
            ->where('ap.id', $id)
            ->first();

        if (!$alumni) {
            abort(404, 'Alumni not found');
        }
        $alumni = (array)$alumni;

        $education  = $this->model->getEducation($id);
        $employment = $this->model->getEmployment($id);

        $approvalHistory = DB::table('approval_history as ah')
            ->join('users as u', 'u.id', '=', 'ah.actor_id')
            ->select('ah.*', 'u.name as actor')
            ->where('ah.alumni_profile_id', $id)
            ->orderBy('ah.created_at', 'desc')
            ->get()
            ->map(fn($r) => (array)$r)
            ->toArray();

        return $this->legacyView(
            'admin/alumni/view',
            compact('alumni', 'education', 'employment', 'approvalHistory'),
            'admin',
            'View Alumni: ' . ($alumni['name'] ?? '')
        );
    }

    public function edit(Request $request, $id)
    {
        $id = (int)$id;
        $alumni = DB::table('alumni_profiles as ap')
            ->join('users as u', 'u.id', '=', 'ap.user_id')
            ->select('ap.*', 'u.name', 'u.email', 'u.role', 'u.status as user_status', 'u.created_at as registered_at')
            ->where('ap.id', $id)
            ->first();

        if (!$alumni) {
            abort(404, 'Alumni profile not found');
        }
        $alumni = (array)$alumni;

        $education  = $this->model->getEducation($id);
        $employment = $this->model->getEmployment($id);

        $primaryEdu = !empty($education) ? (current(array_filter($education, fn($e) => !empty($e['is_primary']))) ?: $education[0]) : null;
        $currentEmp = !empty($employment) ? (current(array_filter($employment, fn($e) => !empty($e['is_current']))) ?: $employment[0]) : null;

        $allUniversities = DB::table('universities')->select('country', 'name')->orderBy('country', 'asc')->orderBy('name', 'asc')->get()->map(fn($r) => (array)$r)->toArray();

        return $this->legacyView(
            'admin/alumni/edit',
            compact('alumni', 'education', 'employment', 'primaryEdu', 'currentEmp', 'allUniversities'),
            'admin',
            'Edit Alumni Profile: ' . ($alumni['name'] ?? '')
        );
    }

    public function update(Request $request, $id)
    {
        $id = (int)$id;
        $alumni = DB::table('alumni_profiles as ap')
            ->join('users as u', 'u.id', '=', 'ap.user_id')
            ->select('ap.*', 'u.id as user_id', 'u.email as current_email')
            ->where('ap.id', $id)
            ->first();

        if (!$alumni) {
            abort(404, 'Alumni profile not found');
        }

        $userId = (int)$alumni->user_id;

        $name  = trim((string)$request->input('name', ''));
        $email = strtolower(trim((string)$request->input('email', '')));

        if (empty($name)) {
            return redirect('/admin/alumni/' . $id . '/edit')->with('error', 'সদস্যের নাম প্রদান করা বাধ্যতামূলক।')->withInput();
        }

        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return redirect('/admin/alumni/' . $id . '/edit')->with('error', 'একটি বৈধ ইমেইল ঠিকানা প্রদান করুন।')->withInput();
        }

        // Check email uniqueness excluding current user
        $existing = DB::table('users')->where('email', $email)->where('id', '!=', $userId)->first();
        if ($existing) {
            return redirect('/admin/alumni/' . $id . '/edit')->with('error', 'এই ইমেইল ঠিকানাটি ইতিমধ্যে অন্য অ্যাকাউন্টে ব্যবহৃত হচ্ছে।')->withInput();
        }

        $secondaryEmail = strtolower(trim((string)$request->input('secondary_email', '')));
        if (!empty($secondaryEmail)) {
            if (!filter_var($secondaryEmail, FILTER_VALIDATE_EMAIL)) {
                return redirect('/admin/alumni/' . $id . '/edit')->with('error', 'একটি বৈধ সেকেন্ডারি ইমেইল ঠিকানা প্রদান করুন।')->withInput();
            }
            if ($secondaryEmail === $email) {
                return redirect('/admin/alumni/' . $id . '/edit')->with('error', 'প্রাইমারি ও সেকেন্ডারি ইমেইল একই হতে পারবে না।')->withInput();
            }
        } else {
            $secondaryEmail = null;
        }

        $dobInput = trim((string)$request->input('dob', ''));
        $dob      = (!empty($dobInput) && strtotime($dobInput)) ? date('Y-m-d', strtotime($dobInput)) : null;

        $status = trim((string)$request->input('status', $alumni->status));
        $allowedStatuses = ['pending', 'under_review', 'verified', 'approved', 'rejected'];
        if (!in_array($status, $allowedStatuses)) {
            $status = $alumni->status;
        }

        // Handle avatar upload if provided
        $avatarFilename = $alumni->avatar;
        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            if ($file && $file->isValid()) {
                $uploader = new \App\Services\UploadService();
                $uploaded = $uploader->uploadAvatar($file, $userId);
                if ($uploaded) {
                    $avatarFilename = $uploaded;
                }
            }
        }

        // Update User
        $userUpdates = [
            'name'            => $name,
            'email'           => $email,
            'secondary_email' => $secondaryEmail,
            'avatar'          => $avatarFilename,
            'updated_at'      => now(),
        ];
        if ($request->filled('role')) {
            $role = $request->input('role');
            if (in_array($role, ['alumni', 'admin', 'super_admin', 'editor'])) {
                $userUpdates['role'] = $role;
            }
        }
        if ($status === 'approved' || $status === 'verified') {
            $userUpdates['status'] = 'active';
        }
        DB::table('users')->where('id', $userId)->update($userUpdates);

        // Update Alumni Profile
        DB::table('alumni_profiles')->where('id', $id)->update([
            'batch_year'              => trim((string)$request->input('batch_year', '')) ?: null,
            'student_id'              => trim((string)$request->input('student_id', '')) ?: null,
            'phone'                   => trim((string)$request->input('phone', '')),
            'secondary_email'         => $secondaryEmail,
            'nid_number'              => trim((string)$request->input('nid_number', '')),
            'dob'                     => $dob,
            'gender'                  => trim((string)$request->input('gender', '')) ?: null,
            'blood_group'             => trim((string)$request->input('blood_group', '')) ?: null,
            'status'                  => $status,
            'avatar'                  => $avatarFilename,
            'bio'                     => trim((string)$request->input('bio', '')),
            'location_type'           => $request->input('location_type', 'bangladesh'),
            'current_location'        => trim((string)$request->input('current_location', '')),
            'thana_upazila'           => trim((string)$request->input('thana_upazila', '')),
            'country'                 => trim((string)$request->input('country', '')),
            'province_city'           => trim((string)$request->input('province_city', '')),
            'permanent_location'      => trim((string)$request->input('permanent_location', '')),
            'permanent_district'      => trim((string)$request->input('permanent_district', '')),
            'permanent_upazila'       => trim((string)$request->input('permanent_upazila', '')),
            'emergency_contact_name'  => trim((string)$request->input('emergency_contact_name', '')),
            'emergency_contact_phone' => trim((string)$request->input('emergency_contact_phone', '')),
            'activity_type'           => $request->input('activity_type', 'work'),
            'session_years'           => trim((string)$request->input('session_years', '')),
            'specialization'          => trim((string)$request->input('specialization', '')),
            'skills'                  => trim((string)$request->input('skills', '')),
            'experience_years'        => trim((string)$request->input('experience_years', '')),
            'willing_to_mentor'       => (int)$request->input('willing_to_mentor', 0),
            'job_referral'            => (int)$request->input('job_referral', 0),
            'website'                 => trim((string)$request->input('website', '')),
            'linkedin_url'            => trim((string)$request->input('linkedin_url', '')),
            'facebook_url'            => trim((string)$request->input('facebook_url', '')),
            'google_scholar_url'      => trim((string)$request->input('google_scholar_url', '')),
            'researchgate_url'        => trim((string)$request->input('researchgate_url', '')),
            'publications'            => trim((string)$request->input('publications', '')),
            'awards_recognition'      => trim((string)$request->input('awards_recognition', '')),
            'updated_at'              => now(),
        ]);

        // Primary Education update or create
        $degree      = trim((string)$request->input('degree', ''));
        $institution = trim((string)$request->input('institution', ''));
        $fieldOfStudy = trim((string)$request->input('field_of_study', ''));
        $gradYear    = trim((string)$request->input('graduation_year', ''));

        if (!empty($degree) || !empty($institution) || !empty($fieldOfStudy)) {
            $edu = DB::table('alumni_education')->where('alumni_profile_id', $id)->where('is_primary', 1)->first();
            if ($edu) {
                DB::table('alumni_education')->where('id', $edu->id)->update([
                    'degree'          => $degree,
                    'institution'     => $institution,
                    'field_of_study'  => $fieldOfStudy,
                    'graduation_year' => $gradYear ?: null,
                    'updated_at'      => now(),
                ]);
            } else {
                DB::table('alumni_education')->insert([
                    'alumni_profile_id' => $id,
                    'degree'            => $degree,
                    'institution'       => $institution,
                    'field_of_study'    => $fieldOfStudy,
                    'graduation_year'   => $gradYear ?: null,
                    'is_primary'        => 1,
                    'created_at'        => now(),
                    'updated_at'        => now(),
                ]);
            }
        }

        // Current Employment update or create
        $organization = trim((string)$request->input('organization', ''));
        $designation  = trim((string)$request->input('designation', ''));
        $department   = trim((string)$request->input('department', ''));

        if (!empty($organization) || !empty($designation)) {
            $emp = DB::table('alumni_employment')->where('alumni_profile_id', $id)->where('is_current', 1)->first();
            if ($emp) {
                DB::table('alumni_employment')->where('id', $emp->id)->update([
                    'organization' => $organization,
                    'designation'  => $designation,
                    'department'   => $department,
                    'updated_at'   => now(),
                ]);
            } else {
                DB::table('alumni_employment')->insert([
                    'alumni_profile_id' => $id,
                    'organization'      => $organization,
                    'designation'       => $designation,
                    'department'        => $department,
                    'is_current'        => 1,
                    'created_at'        => now(),
                    'updated_at'        => now(),
                ]);
            }
        }

        $adminUser = Auth::user();
        $this->logHistory($id, 'updated', "Profile updated by admin ({$adminUser->name})", (int)$adminUser->id);
        AuditLogger::log('ALUMNI_UPDATE', "Admin updated profile ID #{$id} ({$name})");

        return redirect('/admin/alumni/' . $id)->with('success', 'মেম্বারের প্রোফাইল তথ্য সফলভাবে আপডেট করা হয়েছে।');
    }

    public function viewIdCard(Request $request, $id)
    {
        $id = (int)$id;
        $profile = DB::table('alumni_profiles as ap')
            ->join('users as u', 'u.id', '=', 'ap.user_id')
            ->select('ap.*', 'u.name', 'u.email', 'u.avatar as user_avatar')
            ->where('ap.id', $id)
            ->first();

        if (!$profile) {
            abort(404, 'Alumni profile not found');
        }
        $profile = (array)$profile;

        $membership = (new \App\Models\Membership())->getByAlumni($id);

        $refData = null;
        if (!empty($profile['student_reference_id'])) {
            $ref = DB::table('students_reference')->where('id', $profile['student_reference_id'])->first();
            if ($ref) {
                $refData = (array)$ref;
            }
        }

        $lastEdu = DB::table('alumni_education')
            ->where('alumni_profile_id', $id)
            ->orderByRaw('CAST(graduation_year AS UNSIGNED) DESC, id DESC')
            ->first();

        return $this->legacyView(
            'admin/alumni/id_card',
            compact('profile', 'membership', 'refData', 'lastEdu'),
            'admin',
            'Member Alumni ID Card: ' . ($profile['name'] ?? '')
        );
    }

    public function viewMembershipCard(Request $request, $id)
    {
        $id = (int)$id;
        $profile = DB::table('alumni_profiles as ap')
            ->join('users as u', 'u.id', '=', 'ap.user_id')
            ->select('ap.*', 'u.name', 'u.email', 'u.avatar as user_avatar')
            ->where('ap.id', $id)
            ->first();

        if (!$profile) {
            abort(404, 'Alumni profile not found');
        }
        $profile = (array)$profile;

        $membership = (new \App\Models\Membership())->getByAlumni($id);
        $membershipType = null;
        if ($membership && !empty($membership['membership_type_id'])) {
            $membershipType = DB::table('membership_types')->where('id', $membership['membership_type_id'])->first();
        }

        $committeeMember = DB::table('committee_members as cm')
            ->leftJoin('committees as c', 'c.id', '=', 'cm.committee_id')
            ->where('cm.user_id', $profile['user_id'])
            ->where('cm.is_active', 1)
            ->whereNull('cm.deleted_at')
            ->select('cm.*', 'c.name as committee_name')
            ->orderBy('cm.sort_order', 'asc')
            ->orderBy('cm.id', 'asc')
            ->first();

        $refData = null;
        if (!empty($profile['student_reference_id'])) {
            $ref = DB::table('students_reference')->where('id', $profile['student_reference_id'])->first();
            if ($ref) {
                $refData = (array)$ref;
            }
        }

        $lastEdu = DB::table('alumni_education')
            ->where('alumni_profile_id', $id)
            ->orderByRaw('CAST(graduation_year AS UNSIGNED) DESC, id DESC')
            ->first();

        return $this->legacyView(
            'admin/alumni/membership_card',
            compact('profile', 'membership', 'membershipType', 'committeeMember', 'refData', 'lastEdu'),
            'admin',
            'Membership Card: ' . ($profile['name'] ?? '')
        );
    }

    public function approve(Request $request, $id)
    {
        $id   = (int)$id;
        $user = Auth::user();
        DB::table('alumni_profiles')->where('id', $id)->update(['status' => 'approved', 'updated_at' => now()]);

        $profile = DB::table('alumni_profiles')->where('id', $id)->first();
        if ($profile) {
            DB::table('users')->where('id', $profile->user_id)->update(['status' => 'active', 'updated_at' => now()]);
        }

        $this->logHistory($id, 'approved', 'Profile approved by admin', (int)$user->id);
        AuditLogger::log('ALUMNI_APPROVE', "Approved profile ID #{$id}");

        return redirect('/admin/alumni/' . $id)->with('success', 'Alumni profile approved.');
    }

    public function reject(Request $request, $id)
    {
        $id     = (int)$id;
        $reason = $request->input('reason', 'Does not meet criteria.');
        $user   = Auth::user();

        DB::table('alumni_profiles')->where('id', $id)->update(['status' => 'rejected', 'updated_at' => now()]);
        $this->logHistory($id, 'rejected', $reason, (int)$user->id);
        AuditLogger::log('ALUMNI_REJECT', "Rejected profile ID #{$id} with reason: {$reason}");

        return redirect('/admin/alumni/' . $id)->with('success', 'Alumni profile rejected.');
    }

    public function updateStatus(Request $request, $id)
    {
        $id     = (int)$id;
        $status = $request->input('status', 'pending');
        $user   = Auth::user();
        $allowed = ['pending', 'under_review', 'verified', 'approved', 'rejected'];
        if (!in_array($status, $allowed)) {
            abort(400, 'Invalid status');
        }

        DB::table('alumni_profiles')->where('id', $id)->update(['status' => $status, 'updated_at' => now()]);

        if ($status === 'approved') {
            $profile = DB::table('alumni_profiles')->where('id', $id)->first();
            if ($profile) {
                DB::table('users')->where('id', $profile->user_id)->update(['status' => 'active', 'updated_at' => now()]);
            }
        }

        $this->logHistory($id, $status, "Status changed to $status", (int)$user->id);

        return redirect('/admin/alumni/' . $id)->with('success', 'Status updated to ' . $status);
    }

    public function toggleFeatured(Request $request, $id)
    {
        $id = (int)$id;
        $curr = (int) DB::table('alumni_profiles')->where('id', $id)->value('is_featured');
        $newVal = $curr ? 0 : 1;
        DB::table('alumni_profiles')->where('id', $id)->update(['is_featured' => $newVal, 'updated_at' => now()]);

        $msg = $newVal ? 'অ্যালামনাই মেম্বারকে সফলভাবে Featured করা হয়েছে।' : 'অ্যালামনাই মেম্বারকে Unfeatured করা হয়েছে।';
        return back()->with('success', $msg);
    }

    private function logHistory(int $profileId, string $action, string $note, int $actorId): void
    {
        try {
            DB::table('approval_history')->insert([
                'alumni_profile_id' => $profileId,
                'actor_id'          => $actorId,
                'action'            => $action,
                'note'              => $note,
                'created_at'        => now(),
            ]);
        } catch (\Exception $e) {}
    }

    public function exportExcel(Request $request)
    {
        $status = $request->input('status', '');
        $search = $request->input('q', '');

        $query = DB::table('alumni_profiles as ap')
            ->join('users as u', 'u.id', '=', 'ap.user_id')
            ->whereNull('ap.deleted_at');

        if ($status) $query->where('ap.status', $status);
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('u.name', 'like', "%{$search}%")->orWhere('u.email', 'like', "%{$search}%");
            });
        }

        $rows = $query->select(
            'ap.id', 'u.name', 'u.email', 'ap.phone', 'ap.batch_year', 'ap.current_location', 'ap.country', 'ap.status', 'u.created_at'
        )->orderBy('ap.created_at', 'desc')->get()->map(fn($r) => (array)$r)->toArray();

        $filename = 'alumni_list_' . date('Ymd_His') . '.csv';

        return response()->streamDownload(function () use ($rows) {
            $out = fopen('php://output', 'w');
            fputs($out, "\xEF\xBB\xBF");
            fputcsv($out, ['ID', 'Name', 'Email', 'Phone', 'Batch', 'Location', 'Country', 'Status', 'Registered Date']);
            foreach ($rows as $row) {
                fputcsv($out, [
                    $row['id'],
                    $row['name'],
                    $row['email'],
                    $row['phone'] ?? 'N/A',
                    $row['batch_year'] ?? 'N/A',
                    $row['current_location'] ?? 'N/A',
                    $row['country'] ?? 'N/A',
                    ucfirst(str_replace('_', ' ', (string)$row['status'])),
                    date('d M Y', strtotime((string)$row['created_at']))
                ]);
            }
            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv; charset=utf-8']);
    }

    public function exportPdf(Request $request)
    {
        $status = $request->input('status', '');
        $search = $request->input('q', '');

        $query = DB::table('alumni_profiles as ap')
            ->join('users as u', 'u.id', '=', 'ap.user_id')
            ->whereNull('ap.deleted_at');

        if ($status) $query->where('ap.status', $status);
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('u.name', 'like', "%{$search}%")->orWhere('u.email', 'like', "%{$search}%");
            });
        }

        $alumni = $query->select('ap.*', 'u.name', 'u.email', 'u.created_at as registered_at')
            ->orderBy('ap.created_at', 'desc')
            ->get()
            ->map(fn($r) => (array)$r)
            ->toArray();

        $reportTitle = 'IPH Alumni Basic Info Directory & Member List';
        extract(compact('alumni', 'reportTitle'));
        $viewFile = resource_path('views/admin/alumni/print_report.php');
        if (file_exists($viewFile)) {
            ob_start();
            require $viewFile;
            return response(ob_get_clean());
        }
        abort(404);
    }

    public function mapping(Request $request)
    {
        $filter = $request->input('filter', 'all');
        $search = trim((string)$request->input('q', ''));

        $query = DB::table('alumni_profiles as ap')
            ->join('users as u', 'u.id', '=', 'ap.user_id')
            ->leftJoin('students_reference as sr', 'ap.student_reference_id', '=', 'sr.id')
            ->whereNull('ap.deleted_at');

        if ($filter === 'mapped') {
            $query->whereNotNull('ap.student_reference_id');
        } elseif ($filter === 'unmapped') {
            $query->whereNull('ap.student_reference_id');
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('u.name', 'like', "%{$search}%")
                  ->orWhere('u.email', 'like', "%{$search}%")
                  ->orWhere('ap.phone', 'like', "%{$search}%");
            });
        }

        $alumniList = $query->select(
            'ap.*', 'u.name', 'u.email', 'sr.roll as ref_roll', 'sr.name_english as ref_name_en', 'sr.name_bangla as ref_name_bn', 'sr.batch as ref_batch', 'sr.mobile as ref_mobile'
        )->orderBy('ap.student_reference_id', 'asc')->orderBy('ap.id', 'desc')->get()->map(fn($r) => (array)$r)->toArray();

        $mappedRefIds = DB::table('alumni_profiles')->whereNotNull('student_reference_id')->pluck('student_reference_id')->toArray();

        $unmappedStudentsQuery = DB::table('students_reference')
            ->select('id', 'roll', 'name_english', 'name_bangla', 'batch', 'session', 'mobile');

        if (!empty($mappedRefIds)) {
            $unmappedStudentsQuery->whereNotIn('id', $mappedRefIds);
        }

        $unmappedStudents = $unmappedStudentsQuery
            ->orderByRaw("
                CASE WHEN batch LIKE 'L-%' THEN 1 WHEN batch LIKE 'F-%' THEN 2 ELSE 3 END ASC,
                CAST(SUBSTRING(batch, 3) AS UNSIGNED) ASC,
                roll ASC
            ")
            ->limit(50)
            ->get()
            ->map(fn($r) => (array)$r)
            ->toArray();

        return $this->legacyView(
            'admin/alumni/mapping',
            compact('alumniList', 'unmappedStudents', 'filter', 'search'),
            'admin',
            'Alumni Student Reference Mapping'
        );
    }

    public function searchStudents(Request $request)
    {
        $q = trim((string)$request->input('q', ''));
        $mappedRefIds = DB::table('alumni_profiles')
            ->whereNotNull('student_reference_id')
            ->pluck('student_reference_id')
            ->toArray();

        $query = DB::table('students_reference');

        if (!empty($mappedRefIds)) {
            $query->whereNotIn('id', $mappedRefIds);
        }

        if ($q !== '') {
            $query->where(function ($sub) use ($q) {
                $sub->where('roll', 'like', "{$q}%")
                    ->orWhere('name_english', 'like', "%{$q}%")
                    ->orWhere('name_bangla', 'like', "%{$q}%")
                    ->orWhere('batch', 'like', "%{$q}%")
                    ->orWhere('mobile', 'like', "%{$q}%")
                    ->orWhere('session', 'like', "%{$q}%");
            });
        }

        $results = $query->select('id', 'roll', 'name_english', 'name_bangla', 'batch', 'session', 'mobile')
            ->orderByRaw("CASE WHEN roll = ? THEN 1 WHEN roll LIKE ? THEN 2 ELSE 3 END", [$q, "{$q}%"])
            ->orderByRaw("
                CASE WHEN batch LIKE 'L-%' THEN 1 WHEN batch LIKE 'F-%' THEN 2 ELSE 3 END ASC,
                CAST(SUBSTRING(batch, 3) AS UNSIGNED) ASC,
                roll ASC
            ")
            ->limit(40)
            ->get();

        return response()->json($results);
    }

    public function mapStudent(Request $request)
    {
        $profileId    = (int)$request->input('profile_id');
        $studentRefId = $request->input('student_reference_id');

        if (empty($studentRefId)) {
            DB::table('alumni_profiles')->where('id', $profileId)->update(['student_reference_id' => null, 'updated_at' => now()]);
            return redirect('/admin/alumni/mapping')->with('success', 'Alumni member unmapped successfully.');
        }

        $studentRef = DB::table('students_reference')->where('id', $studentRefId)->first();
        if ($studentRef) {
            $updates = [
                'student_reference_id' => $studentRef->id,
                'student_id'           => DB::raw("COALESCE(NULLIF(student_id, ''), " . DB::getPdo()->quote((string)$studentRef->roll) . ")"),
                'phone'                => DB::raw("COALESCE(NULLIF(phone, ''), " . DB::getPdo()->quote((string)($studentRef->mobile ?? '')) . ")"),
                'status'               => 'verified',
                'updated_at'           => now(),
            ];

            if (!empty($studentRef->batch)) {
                $updates['batch_year'] = DB::raw("COALESCE(NULLIF(batch_year, ''), " . DB::getPdo()->quote((string)$studentRef->batch) . ")");
            }

            DB::table('alumni_profiles')->where('id', $profileId)->update($updates);

            $uId = DB::table('alumni_profiles')->where('id', $profileId)->value('user_id');
            if ($uId) {
                DB::table('users')->where('id', $uId)->update(['status' => 'active', 'updated_at' => now()]);
            }

            return redirect('/admin/alumni/mapping')->with('success', 'Alumni successfully mapped with Student Reference Database! Profile fields autofilled.');
        }

        return redirect('/admin/alumni/mapping');
    }

    public function contactRequests(Request $request)
    {
        $requests = DB::table('contact_requests as cr')
            ->join('alumni_profiles as ap', 'ap.id', '=', 'cr.alumni_profile_id')
            ->join('users as u', 'u.id', '=', 'ap.user_id')
            ->select('cr.*', 'ap.batch_year', 'u.name as alumni_name', 'u.email as alumni_email')
            ->orderBy('cr.created_at', 'desc')
            ->get()
            ->map(fn($r) => (array)$r)
            ->toArray();

        return $this->legacyView('admin/alumni/contact_requests', compact('requests'), 'admin', 'Contact Requests Monitoring');
    }

    public function exportCardsSvg(Request $request, \App\Services\IdCardSvgService $svgService)
    {
        $status = $request->input('status');
        $search = trim((string)$request->input('q', ''));

        $query = DB::table('alumni_profiles as ap')
            ->join('users as u', 'u.id', '=', 'ap.user_id')
            ->select('ap.id')
            ->whereNull('ap.deleted_at')
            ->whereNull('u.deleted_at');

        if (!empty($status)) {
            $query->where('ap.status', $status);
        } else {
            $query->whereIn('ap.status', ['approved', 'verified', 'active']);
        }

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('u.name', 'like', "%{$search}%")
                  ->orWhere('u.email', 'like', "%{$search}%")
                  ->orWhere('ap.phone', 'like', "%{$search}%")
                  ->orWhere('ap.batch_year', 'like', "%{$search}%");
            });
        }

        $profileIds = $query->orderBy('ap.id', 'asc')->pluck('ap.id')->toArray();

        if (empty($profileIds)) {
            return redirect('/admin/alumni')->with('error', 'কোনো মেম্বার পাওয়া যায়নি আইডি কার্ড এক্সপোর্টের জন্য।');
        }

        try {
            $zipPath  = $svgService->generateZipArchive($profileIds);
            $filename = 'IPH_Alumni_Member_Cards_SVG_' . date('Ymd_His') . '.zip';
            return response()->download($zipPath, $filename)->deleteFileAfterSend(true);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('SVG card export failed', ['exception' => $e]);
            return redirect('/admin/alumni')->with('error', 'SVG কার্ড এক্সপোর্টে সমস্যা হয়েছে। অনুগ্রহ করে সিস্টেম লগ দেখুন।');
        }
    }

    public function downloadSingleCardSvg(Request $request, $id, $side, \App\Services\IdCardSvgService $svgService)
    {
        $id   = (int)$id;
        $side = strtolower((string)$side);
        $data = $svgService->getCardData($id);

        if (!$data) {
            abort(404, 'Alumni profile not found');
        }

        $cleanName = preg_replace('/[^A-Za-z0-9_\-]/', '_', trim($data['name']));
        $cleanName = preg_replace('/_+/', '_', (string)$cleanName);
        $prefix    = $data['member_no'] . '_' . $cleanName;

        if ($side === 'front') {
            $svg = $svgService->renderFrontSvg($data);
            return response($svg, 200, [
                'Content-Type'        => 'image/svg+xml',
                'Content-Disposition' => "attachment; filename=\"{$prefix}_front.svg\"",
            ]);
        }

        if ($side === 'back') {
            $svg = $svgService->renderBackSvg($data);
            return response($svg, 200, [
                'Content-Type'        => 'image/svg+xml',
                'Content-Disposition' => "attachment; filename=\"{$prefix}_back.svg\"",
            ]);
        }

        // Default to ZIP containing both sides
        $zipPath = $svgService->generateZipArchive([$id]);
        return response()->download($zipPath, "{$prefix}_ID_Card_SVG.zip")->deleteFileAfterSend(true);
    }
}
