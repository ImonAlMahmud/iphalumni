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
