<?php
declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends BaseController
{
    public function index(Request $request)
    {
        return $this->legacyView('admin/reports/index', [], 'admin', 'Reports');
    }

    public function alumni(Request $request)
    {
        $format = $request->input('format', 'html');
        $data = DB::table('alumni_profiles as ap')
            ->join('users as u', 'u.id', '=', 'ap.user_id')
            ->select('u.name', 'u.email', 'ap.batch_year', 'ap.phone', 'ap.current_location', 'ap.status', 'u.created_at', 'ap.avatar')
            ->whereNull('ap.deleted_at')
            ->orderBy('u.name')
            ->get()
            ->map(fn($r) => (array)$r)
            ->toArray();

        if ($format === 'csv') {
            return response()->streamDownload(function () use ($data) {
                $out = fopen('php://output', 'w');
                fputs($out, "\xEF\xBB\xBF");
                fputcsv($out, ['Name', 'Email', 'Batch', 'Phone', 'Location', 'Status', 'Registered']);
                foreach ($data as $row) {
                    fputcsv($out, [
                        $row['name'],
                        $row['email'],
                        $row['batch_year'] ?? '',
                        $row['phone'] ?? '',
                        $row['current_location'] ?? '',
                        $row['status'] ?? '',
                        $row['created_at'] ?? '',
                    ]);
                }
                fclose($out);
            }, 'alumni_report_' . date('Ymd') . '.csv', ['Content-Type' => 'text/csv; charset=utf-8']);
        }

        return $this->legacyView('admin/reports/alumni', compact('data'), 'admin', 'Alumni Report');
    }

    public function membership(Request $request)
    {
        $data = DB::table('memberships as m')
            ->join('alumni_profiles as ap', 'ap.id', '=', 'm.alumni_profile_id')
            ->join('users as u', 'u.id', '=', 'ap.user_id')
            ->join('membership_types as mt', 'mt.id', '=', 'm.membership_type_id')
            ->select('u.name', 'u.email', 'mt.name as type', 'm.status', 'm.start_date', 'm.end_date')
            ->whereNull('m.deleted_at')
            ->orderBy('m.created_at', 'desc')
            ->get()
            ->map(fn($r) => (array)$r)
            ->toArray();

        $format = $request->input('format', 'html');
        if ($format === 'csv') {
            return response()->streamDownload(function () use ($data) {
                $out = fopen('php://output', 'w');
                fputs($out, "\xEF\xBB\xBF");
                fputcsv($out, ['Name', 'Email', 'Type', 'Status', 'Start', 'End']);
                foreach ($data as $row) {
                    fputcsv($out, array_values($row));
                }
                fclose($out);
            }, 'membership_report_' . date('Ymd') . '.csv', ['Content-Type' => 'text/csv; charset=utf-8']);
        }

        return $this->legacyView('admin/reports/membership', compact('data'), 'admin', 'Membership Report');
    }

    public function donations(Request $request)
    {
        $data = DB::table('donations')->orderBy('created_at', 'desc')->get()->map(fn($r) => (array)$r)->toArray();
        $total = array_sum(array_column(array_filter($data, fn($d) => $d['status'] === 'completed'), 'amount'));

        return $this->legacyView('admin/reports/donations', compact('data', 'total'), 'admin', 'Donations Report');
    }
}
