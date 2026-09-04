<?php
declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudentsController extends BaseController
{
    public function index(Request $request)
    {
        $page        = max(1, (int)$request->input('page', 1));
        $batch       = $request->input('batch', '');
        $session     = $request->input('session', '');
        $dept        = $request->input('dept', '');
        $search      = $request->input('q', '');
        $missingInfo = $request->input('missing_info', '');

        $query = DB::table('students_reference');

        if ($batch) $query->where('batch', $batch);
        if ($session) $query->where('session', $session);
        if ($dept) $query->where('department', $dept);

        if ($missingInfo === '1') {
            $query->where(function ($q) {
                $q->whereNull('roll')->orWhere('roll', '')
                  ->orWhereNull('name_english')->orWhere('name_english', '')
                  ->orWhereNull('name_bangla')->orWhere('name_bangla', '')
                  ->orWhereNull('mobile')->orWhere('mobile', '')
                  ->orWhereNull('guardian_mobile')->orWhere('guardian_mobile', '')
                  ->orWhereNull('batch')->orWhere('batch', '')
                  ->orWhereNull('session')->orWhere('session', '')
                  ->orWhereNull('department')->orWhere('department', '');
            });
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('roll', 'like', "%{$search}%")
                  ->orWhere('name_english', 'like', "%{$search}%")
                  ->orWhere('name_bangla', 'like', "%{$search}%")
                  ->orWhere('mobile', 'like', "%{$search}%")
                  ->orWhere('guardian_mobile', 'like', "%{$search}%");
            });
        }

        $total = $query->count();
        $perPage = 30;
        $offset  = ($page - 1) * $perPage;

        $students = $query->orderByRaw("
            CASE WHEN batch LIKE 'L-%' THEN 1 WHEN batch LIKE 'F-%' THEN 2 ELSE 3 END ASC,
            CAST(SUBSTRING(batch, 3) AS UNSIGNED) ASC,
            roll ASC
        ")->offset($offset)->limit($perPage)->get()->map(fn($r) => (array)$r)->toArray();

        $batches = DB::table('students_reference')->distinct()->orderByRaw("
            CASE WHEN batch LIKE 'L-%' THEN 1 WHEN batch LIKE 'F-%' THEN 2 ELSE 3 END ASC,
            CAST(SUBSTRING(batch, 3) AS UNSIGNED) ASC
        ")->pluck('batch')->toArray();

        $sessions = DB::table('students_reference')->distinct()->orderBy('session', 'asc')->pluck('session')->toArray();
        $depts = DB::table('students_reference')->distinct()->orderBy('department', 'asc')->pluck('department')->toArray();

        $pagination = [
            'total'        => $total,
            'per_page'     => $perPage,
            'current_page' => $page,
            'last_page'    => (int)ceil($total / $perPage),
        ];

        return $this->legacyView(
            'admin/students/index',
            compact('students', 'batches', 'sessions', 'depts', 'pagination', 'batch', 'session', 'dept', 'search', 'missingInfo'),
            'admin',
            'Student Reference Database'
        );
    }

    public function exportCsv(Request $request)
    {
        $batch   = $request->input('batch', '');
        $session = $request->input('session', '');
        $dept    = $request->input('dept', '');
        $search  = $request->input('q', '');

        $query = DB::table('students_reference');
        if ($batch) $query->where('batch', $batch);
        if ($session) $query->where('session', $session);
        if ($dept) $query->where('department', $dept);
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('roll', 'like', "%{$search}%")
                  ->orWhere('name_english', 'like', "%{$search}%")
                  ->orWhere('name_bangla', 'like', "%{$search}%")
                  ->orWhere('mobile', 'like', "%{$search}%")
                  ->orWhere('guardian_mobile', 'like', "%{$search}%");
            });
        }

        $students = $query->select('roll', 'name_english', 'name_bangla', 'mobile', 'guardian_mobile', 'batch', 'session', 'department')
            ->orderByRaw("
                CASE WHEN batch LIKE 'L-%' THEN 1 WHEN batch LIKE 'F-%' THEN 2 ELSE 3 END ASC,
                CAST(SUBSTRING(batch, 3) AS UNSIGNED) ASC,
                roll ASC
            ")->get()->map(fn($r) => (array)$r)->toArray();

        return response()->streamDownload(function () use ($students) {
            $out = fopen('php://output', 'w');
            fputs($out, "\xEF\xBB\xBF");
            fputcsv($out, ['Roll', 'Name (English)', 'Name (Bangla)', 'Mobile', 'Guardian Mobile', 'Batch', 'Session', 'Department']);
            foreach ($students as $row) {
                fputcsv($out, array_values($row));
            }
            fclose($out);
        }, 'students_reference_export_' . date('Ymd') . '.csv', ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    public function exportPrint(Request $request)
    {
        $batch   = $request->input('batch', '');
        $session = $request->input('session', '');
        $dept    = $request->input('dept', '');
        $search  = $request->input('q', '');

        $query = DB::table('students_reference');
        if ($batch) $query->where('batch', $batch);
        if ($session) $query->where('session', $session);
        if ($dept) $query->where('department', $dept);
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('roll', 'like', "%{$search}%")
                  ->orWhere('name_english', 'like', "%{$search}%")
                  ->orWhere('name_bangla', 'like', "%{$search}%")
                  ->orWhere('mobile', 'like', "%{$search}%")
                  ->orWhere('guardian_mobile', 'like', "%{$search}%");
            });
        }

        $students = $query->orderByRaw("
            CASE WHEN batch LIKE 'L-%' THEN 1 WHEN batch LIKE 'F-%' THEN 2 ELSE 3 END ASC,
            CAST(SUBSTRING(batch, 3) AS UNSIGNED) ASC,
            roll ASC
        ")->get()->map(fn($r) => (array)$r)->toArray();

        extract(compact('students'));
        $viewFile = resource_path('views/admin/students/print.php');
        if (file_exists($viewFile)) {
            ob_start();
            require $viewFile;
            return response(ob_get_clean());
        }
        abort(404);
    }

    public function update(Request $request, $id)
    {
        $id = (int)$id;
        if (!$id) {
            return redirect('/admin/students')->with('error', 'Invalid student ID.');
        }

        $roll            = trim((string)$request->input('roll', ''));
        $name_english    = trim((string)$request->input('name_english', ''));
        $name_bangla     = trim((string)$request->input('name_bangla', ''));
        $mobile          = trim((string)$request->input('mobile', ''));
        $guardian_mobile = trim((string)$request->input('guardian_mobile', ''));
        $batch           = trim((string)$request->input('batch', ''));
        $session         = trim((string)$request->input('session', ''));
        $department      = trim((string)$request->input('department', ''));

        DB::table('students_reference')->where('id', $id)->update([
            'roll'            => $roll !== '' ? $roll : null,
            'name_english'    => $name_english,
            'name_bangla'     => $name_bangla !== '' ? $name_bangla : null,
            'mobile'          => $mobile !== '' ? $mobile : null,
            'guardian_mobile' => $guardian_mobile !== '' ? $guardian_mobile : null,
            'batch'           => $batch,
            'session'         => $session,
            'department'      => $department,
        ]);

        return back()->with('success', 'Student record updated successfully.');
    }

    public function delete(Request $request, $id)
    {
        $id = (int)$id;
        if (!$id) {
            return redirect('/admin/students')->with('error', 'Invalid student ID.');
        }

        DB::table('students_reference')->where('id', $id)->delete();

        return back()->with('success', 'Student record deleted successfully from reference database.');
    }
}
