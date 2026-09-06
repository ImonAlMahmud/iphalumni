<?php
declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Models\Membership;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class MembershipController extends BaseController
{
    public function index(Request $request)
    {
        $model = new Membership();
        $pending = $model->getPending();
        $stats   = $model->getStats();

        $memberships = DB::table('memberships as m')
            ->join('alumni_profiles as ap', 'ap.id', '=', 'm.alumni_profile_id')
            ->join('users as u', 'u.id', '=', 'ap.user_id')
            ->join('membership_types as mt', 'mt.id', '=', 'm.membership_type_id')
            ->leftJoin('membership_payments as mp', 'mp.membership_id', '=', 'm.id')
            ->select('m.*', 'u.name', 'u.email', 'mt.name as type_name', 'mp.method as payment_method', 'mp.transaction_id', 'mp.payment_slip', 'ap.proof_document')
            ->whereNull('m.deleted_at')
            ->orderBy('m.created_at', 'desc')
            ->limit(50)
            ->get()
            ->map(fn($r) => (array)$r)
            ->toArray();

        $typesList = DB::table('membership_types')->orderBy('sort_order', 'asc')->get()->map(fn($r) => (array)$r)->toArray();

        $allAlumni = DB::table('alumni_profiles as ap')
            ->join('users as u', 'u.id', '=', 'ap.user_id')
            ->select('ap.id', 'u.name', 'u.email', 'ap.batch_year', 'ap.phone')
            ->whereIn('ap.status', ['approved', 'verified', 'active'])
            ->whereNull('ap.deleted_at')
            ->orderBy('u.name', 'asc')
            ->get()
            ->map(fn($r) => (array)$r)
            ->toArray();

        return $this->legacyView(
            'admin/membership/index',
            compact('pending', 'stats', 'memberships', 'typesList', 'allAlumni'),
            'admin',
            'Membership Management'
        );
    }

    public function logs(Request $request)
    {
        $search   = trim((string)$request->input('q', ''));
        $status   = trim((string)$request->input('status', ''));
        $typeId   = (int)$request->input('type', 0);
        $method   = trim((string)$request->input('method', ''));
        $pStatus  = trim((string)$request->input('payment_status', ''));
        $page     = max(1, (int)$request->input('page', 1));
        $perPage  = 20;

        $query = DB::table('memberships as m')
            ->join('alumni_profiles as ap', 'ap.id', '=', 'm.alumni_profile_id')
            ->join('users as u', 'u.id', '=', 'ap.user_id')
            ->join('membership_types as mt', 'mt.id', '=', 'm.membership_type_id')
            ->leftJoin('membership_payments as mp', 'mp.membership_id', '=', 'm.id')
            ->whereNull('m.deleted_at');

        if ($status !== '' && $status !== 'all') {
            $query->where('m.status', $status);
        }

        if ($typeId > 0) {
            $query->where('m.membership_type_id', $typeId);
        }

        if ($method !== '' && $method !== 'all') {
            $query->where('mp.method', $method);
        }

        if ($pStatus !== '' && $pStatus !== 'all') {
            $query->where('mp.status', $pStatus);
        }

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('u.name', 'like', "%{$search}%")
                  ->orWhere('u.email', 'like', "%{$search}%")
                  ->orWhere('ap.phone', 'like', "%{$search}%")
                  ->orWhere('m.membership_number', 'like', "%{$search}%")
                  ->orWhere('mp.transaction_id', 'like', "%{$search}%");
            });
        }

        $totalRecords = (clone $query)->count();

        $memberships = $query->select(
            'm.*',
            'u.id as user_id',
            'u.name',
            'u.email',
            'u.avatar as user_avatar',
            'ap.avatar as profile_avatar',
            'ap.phone',
            'ap.batch_year',
            'ap.student_id',
            'ap.proof_document as profile_proof',
            'mt.name as type_name',
            'mt.fee as type_fee',
            'mp.id as payment_id',
            'mp.amount as payment_amount',
            'mp.currency as payment_currency',
            'mp.method as payment_method',
            'mp.transaction_id',
            'mp.payment_slip',
            'mp.status as payment_status',
            'mp.paid_at as payment_date'
        )
        ->orderBy('m.created_at', 'desc')
        ->offset(($page - 1) * $perPage)
        ->limit($perPage)
        ->get()
        ->map(fn($r) => (array)$r)
        ->toArray();

        // Summary Stats
        $stats = [
            'total'          => DB::table('memberships')->whereNull('deleted_at')->count(),
            'active'         => DB::table('memberships')->where('status', 'active')->whereNull('deleted_at')->count(),
            'pending'        => DB::table('memberships')->where('status', 'pending')->whereNull('deleted_at')->count(),
            'total_payments' => (float)DB::table('membership_payments')->where('status', 'paid')->sum('amount'),
        ];

        $typesList = DB::table('membership_types')->orderBy('sort_order', 'asc')->get()->map(fn($r) => (array)$r)->toArray();

        $totalPages = max(1, (int)ceil($totalRecords / $perPage));

        return $this->legacyView(
            'admin/membership/logs',
            compact('memberships', 'stats', 'typesList', 'search', 'status', 'typeId', 'method', 'pStatus', 'page', 'totalPages', 'totalRecords'),
            'admin',
            'Membership & Payment Log'
        );
    }

    public function exportCsv(Request $request)
    {
        $search   = trim((string)$request->input('q', ''));
        $status   = trim((string)$request->input('status', ''));
        $typeId   = (int)$request->input('type', 0);
        $method   = trim((string)$request->input('method', ''));
        $pStatus  = trim((string)$request->input('payment_status', ''));

        $query = DB::table('memberships as m')
            ->join('alumni_profiles as ap', 'ap.id', '=', 'm.alumni_profile_id')
            ->join('users as u', 'u.id', '=', 'ap.user_id')
            ->join('membership_types as mt', 'mt.id', '=', 'm.membership_type_id')
            ->leftJoin('membership_payments as mp', 'mp.membership_id', '=', 'm.id')
            ->whereNull('m.deleted_at');

        if ($status !== '' && $status !== 'all') {
            $query->where('m.status', $status);
        }
        if ($typeId > 0) {
            $query->where('m.membership_type_id', $typeId);
        }
        if ($method !== '' && $method !== 'all') {
            $query->where('mp.method', $method);
        }
        if ($pStatus !== '' && $pStatus !== 'all') {
            $query->where('mp.status', $pStatus);
        }
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('u.name', 'like', "%{$search}%")
                  ->orWhere('u.email', 'like', "%{$search}%")
                  ->orWhere('ap.phone', 'like', "%{$search}%")
                  ->orWhere('m.membership_number', 'like', "%{$search}%")
                  ->orWhere('mp.transaction_id', 'like', "%{$search}%");
            });
        }

        $rows = $query->select(
            'm.*',
            'u.name',
            'u.email',
            'ap.secondary_email',
            'ap.phone',
            'ap.batch_year',
            'ap.student_id',
            'mt.name as type_name',
            'mt.fee as type_fee',
            'mp.amount as payment_amount',
            'mp.currency as payment_currency',
            'mp.method as payment_method',
            'mp.transaction_id',
            'mp.status as payment_status',
            'mp.paid_at as payment_date'
        )
        ->orderBy('m.created_at', 'desc')
        ->get()
        ->map(fn($r) => (array)$r)
        ->toArray();

        $filename = 'membership_payments_log_' . date('Ymd_His') . '.csv';

        return response()->streamDownload(function () use ($rows) {
            $out = fopen('php://output', 'w');
            fputs($out, "\xEF\xBB\xBF");
            fputcsv($out, [
                'SL',
                'Member Name',
                'Email',
                'Secondary Email',
                'Phone',
                'Batch Year',
                'DU Reg / Student ID',
                'Membership Number',
                'Membership Tier',
                'Plan Fee (BDT)',
                'Membership Status',
                'Start Date',
                'End Date',
                'Approved At',
                'Payment Amount',
                'Currency',
                'Payment Method',
                'Transaction ID (TrxID)',
                'Payment Status',
                'Payment Date',
            ]);

            $sl = 1;
            foreach ($rows as $row) {
                $amount = (float)($row['payment_amount'] ?? ($row['type_fee'] ?? 0));
                fputcsv($out, [
                    $sl++,
                    $row['name'] ?? '',
                    $row['email'] ?? '',
                    $row['secondary_email'] ?? '',
                    $row['phone'] ?? '',
                    $row['batch_year'] ?? '',
                    $row['student_id'] ?? '',
                    $row['membership_number'] ?? '',
                    $row['type_name'] ?? '',
                    (float)($row['type_fee'] ?? 0),
                    strtoupper((string)($row['status'] ?? '')),
                    $row['start_date'] ? date('d M Y', strtotime((string)$row['start_date'])) : '',
                    $row['end_date'] ? date('d M Y', strtotime((string)$row['end_date'])) : 'Lifetime',
                    $row['approved_at'] ? date('d M Y H:i', strtotime((string)$row['approved_at'])) : '',
                    $amount,
                    $row['payment_currency'] ?? 'BDT',
                    strtoupper((string)($row['payment_method'] ?? 'FREE/ADMIN')),
                    $row['transaction_id'] ?? '',
                    strtoupper((string)($row['payment_status'] ?? ($row['status'] === 'active' ? 'PAID' : 'PENDING'))),
                    $row['payment_date'] ? date('d M Y H:i', strtotime((string)$row['payment_date'])) : '',
                ]);
            }
            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv; charset=utf-8']);
    }

    public function exportPdf(Request $request)
    {
        $search   = trim((string)$request->input('q', ''));
        $status   = trim((string)$request->input('status', ''));
        $typeId   = (int)$request->input('type', 0);
        $method   = trim((string)$request->input('method', ''));
        $pStatus  = trim((string)$request->input('payment_status', ''));

        $query = DB::table('memberships as m')
            ->join('alumni_profiles as ap', 'ap.id', '=', 'm.alumni_profile_id')
            ->join('users as u', 'u.id', '=', 'ap.user_id')
            ->join('membership_types as mt', 'mt.id', '=', 'm.membership_type_id')
            ->leftJoin('membership_payments as mp', 'mp.membership_id', '=', 'm.id')
            ->whereNull('m.deleted_at');

        if ($status !== '' && $status !== 'all') {
            $query->where('m.status', $status);
        }
        if ($typeId > 0) {
            $query->where('m.membership_type_id', $typeId);
        }
        if ($method !== '' && $method !== 'all') {
            $query->where('mp.method', $method);
        }
        if ($pStatus !== '' && $pStatus !== 'all') {
            $query->where('mp.status', $pStatus);
        }
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('u.name', 'like', "%{$search}%")
                  ->orWhere('u.email', 'like', "%{$search}%")
                  ->orWhere('ap.phone', 'like', "%{$search}%")
                  ->orWhere('m.membership_number', 'like', "%{$search}%")
                  ->orWhere('mp.transaction_id', 'like', "%{$search}%");
            });
        }

        $memberships = $query->select(
            'm.*',
            'u.name',
            'u.email',
            'ap.phone',
            'ap.batch_year',
            'mt.name as type_name',
            'mt.fee as type_fee',
            'mp.amount as payment_amount',
            'mp.currency as payment_currency',
            'mp.method as payment_method',
            'mp.transaction_id',
            'mp.status as payment_status',
            'mp.paid_at as payment_date'
        )
        ->orderBy('m.created_at', 'desc')
        ->get()
        ->map(fn($r) => (array)$r)
        ->toArray();

        $stats = [
            'total'          => count($memberships),
            'active'         => count(array_filter($memberships, fn($m) => ($m['status'] ?? '') === 'active')),
            'total_payments' => array_sum(array_map(fn($m) => (float)($m['payment_amount'] ?? 0), $memberships)),
        ];

        $reportTitle = 'IPH Alumni Association — Membership & Payment Audit History Report';

        extract(compact('memberships', 'stats', 'reportTitle', 'search', 'status', 'method'));
        $viewFile = resource_path('views/admin/membership/print_log.php');
        if (file_exists($viewFile)) {
            ob_start();
            require $viewFile;
            return response(ob_get_clean());
        }
        abort(404);
    }

    public function grantHonorary(Request $request)
    {
        $alumniProfileId = (int)$request->input('alumni_profile_id');
        if ($alumniProfileId <= 0) {
            return redirect('/admin/membership')->with('error', 'অনুগ্রহ করে একজন অ্যালামনাই নির্বাচন করুন।');
        }

        $profile = DB::table('alumni_profiles as ap')
            ->join('users as u', 'u.id', '=', 'ap.user_id')
            ->select('ap.*', 'u.name', 'u.email')
            ->where('ap.id', $alumniProfileId)
            ->first();

        if (!$profile) {
            return redirect('/admin/membership')->with('error', 'অ্যালামনাই প্রোফাইল পাওয়া যায়নি।');
        }

        // Find or create Honorary type
        $honoraryType = DB::table('membership_types')->whereRaw('LOWER(name) = ?', ['honorary'])->first();
        if (!$honoraryType) {
            $typeId = DB::table('membership_types')->insertGetId([
                'name'            => 'Honorary',
                'description'     => 'Exclusively granted by administration to distinguished alumni.',
                'fee'             => 0,
                'duration_months' => null,
                'badge_text'      => 'HONORARY',
                'btn_text'        => 'Admin Granted',
                'features'        => "Special Recognition\nLifetime Directory Access\nExclusive Event Invitations\nDigital ID Card",
                'is_featured'     => 0,
                'is_active'       => 1,
                'sort_order'      => 99,
                'created_at'      => now(),
                'updated_at'      => now(),
            ]);
        } else {
            $typeId = $honoraryType->id;
        }

        $memNo = 'IPH-HON-' . str_pad((string)$alumniProfileId, 5, '0', STR_PAD_LEFT);

        // Check if existing membership
        $existing = DB::table('memberships')->where('alumni_profile_id', $alumniProfileId)->first();
        if ($existing) {
            DB::table('memberships')->where('id', $existing->id)->update([
                'membership_type_id' => $typeId,
                'membership_number'  => $memNo,
                'status'             => 'active',
                'start_date'         => now()->toDateString(),
                'end_date'           => null,
                'approved_at'        => now(),
                'updated_at'         => now(),
            ]);
        } else {
            DB::table('memberships')->insert([
                'alumni_profile_id'  => $alumniProfileId,
                'membership_type_id' => $typeId,
                'membership_number'  => $memNo,
                'status'             => 'active',
                'start_date'         => now()->toDateString(),
                'end_date'           => null,
                'approved_at'        => now(),
                'created_at'         => now(),
                'updated_at'         => now(),
            ]);
        }

        return redirect('/admin/membership')->with('success', $profile->name . ' কে সফলভাবে সম্মানসূচক আজীবন সদস্যপদ (Honorary Lifetime Membership) প্রদান করা হয়েছে!');
    }

    public function approve(Request $request, $id)
    {
        $id = (int)$id;

        $slip = DB::table('membership_payments')->where('membership_id', $id)->value('payment_slip');
        if (!empty($slip)) {
            if (Storage::disk('public')->exists("documents/{$slip}")) {
                Storage::disk('public')->delete("documents/{$slip}");
            }
            DB::table('membership_payments')->where('membership_id', $id)->update([
                'payment_slip' => null,
                'status'       => 'paid',
                'paid_at'      => now(),
            ]);
        }

        DB::table('memberships')->where('id', $id)->update(['status' => 'active', 'approved_at' => now(), 'updated_at' => now()]);

        // Auto-record to Association Total Funds
        $mDetail = DB::table('memberships as m')
            ->join('membership_types as mt', 'mt.id', '=', 'm.membership_type_id')
            ->join('alumni_profiles as ap', 'ap.id', '=', 'm.alumni_profile_id')
            ->join('users as u', 'u.id', '=', 'ap.user_id')
            ->select('m.*', 'mt.name as type_name', 'mt.fee', 'u.name as user_name')
            ->where('m.id', $id)
            ->first();

        if ($mDetail && (float)$mDetail->fee > 0) {
            $ref = 'MEM-' . $id;
            $checkExists = DB::table('association_funds')->where('reference_no', $ref)->exists();
            if (!$checkExists) {
                DB::table('association_funds')->insert([
                    'title'        => 'মেম্বারশিপ ফি সংগ্রহ: ' . $mDetail->user_name . ' (' . $mDetail->type_name . ')',
                    'source'       => 'Membership Collection',
                    'amount'       => (float)$mDetail->fee,
                    'fund_date'    => now()->toDateString(),
                    'reference_no' => $ref,
                    'notes'        => 'Approved Alumni Membership Fee Collection',
                    'created_at'   => now(),
                    'updated_at'   => now(),
                ]);
            }
        }

        return redirect('/admin/membership')->with('success', 'Membership approved and payment receipt file cleaned up.');
    }

    public function reject(Request $request, $id)
    {
        $id = (int)$id;
        DB::table('memberships')->where('id', $id)->update(['status' => 'rejected', 'updated_at' => now()]);
        return redirect('/admin/membership')->with('success', 'Membership status updated.');
    }

    public function updateTier(Request $request, $id)
    {
        $id          = (int)$id;
        $fee         = (float)$request->input('fee', 0);
        $badgeText   = trim((string)$request->input('badge_text', ''));
        $btnText     = trim((string)$request->input('btn_text', ''));
        $features    = trim((string)$request->input('features', ''));
        $isFeatured  = $request->input('is_featured') ? 1 : 0;
        $isActive    = $request->input('is_active') ? 1 : 0;

        DB::table('membership_types')->where('id', $id)->update([
            'fee'         => $fee,
            'badge_text'  => $badgeText,
            'btn_text'    => $btnText,
            'features'    => $features,
            'is_featured' => $isFeatured,
            'is_active'   => $isActive,
            'updated_at'  => now(),
        ]);

        return redirect('/admin/membership')->with('success', 'মেম্বারশিপ টাইয়ারের সকল ফিচার, প্রাইস ও ডিজাইন ডাটা সফলভাবে আপডেট করা হয়েছে!');
    }

    public function delete(Request $request, $id)
    {
        $id = (int)$id;
        DB::table('memberships')->where('id', $id)->update(['status' => 'cancelled', 'deleted_at' => now()]);
        return redirect('/admin/membership')->with('success', 'Membership removed successfully.');
    }
}
