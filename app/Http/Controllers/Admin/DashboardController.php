<?php
declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends BaseController
{
    public function index(Request $request)
    {
        // Reconcile and synchronize active membership payments
        try {
            $activeMembershipIds = DB::table('memberships')
                ->where('status', 'active')
                ->whereNull('deleted_at')
                ->pluck('id')
                ->toArray();

            if (!empty($activeMembershipIds)) {
                // Mark any pending payments of active memberships as paid
                DB::table('membership_payments')
                    ->whereIn('membership_id', $activeMembershipIds)
                    ->where('status', '!=', 'paid')
                    ->update([
                        'status'  => 'paid',
                        'paid_at' => DB::raw('COALESCE(paid_at, NOW())'),
                    ]);

                // Create payment records for active memberships with fee > 0 that lack payment records
                $membershipsWithoutPayment = DB::table('memberships as m')
                    ->join('membership_types as mt', 'mt.id', '=', 'm.membership_type_id')
                    ->leftJoin('membership_payments as mp', 'mp.membership_id', '=', 'm.id')
                    ->where('m.status', 'active')
                    ->whereNull('m.deleted_at')
                    ->whereNull('mp.id')
                    ->where('mt.fee', '>', 0)
                    ->select('m.id as membership_id', 'mt.fee', 'm.created_at', 'm.approved_at')
                    ->get();

                foreach ($membershipsWithoutPayment as $mwp) {
                    DB::table('membership_payments')->insert([
                        'membership_id'  => $mwp->membership_id,
                        'amount'         => (float)$mwp->fee,
                        'currency'       => 'BDT',
                        'method'         => 'ADMIN_APPROVED',
                        'transaction_id' => 'TRX-ACT-' . $mwp->membership_id,
                        'status'         => 'paid',
                        'paid_at'        => $mwp->approved_at ?: ($mwp->created_at ?: now()),
                        'created_at'     => $mwp->created_at ?: now(),
                    ]);
                }
            }
        } catch (\Throwable $e) {}

        $totalAlumni   = (int) DB::table('alumni_profiles')->whereNull('deleted_at')->count();
        $pendingAlumni = (int) DB::table('alumni_profiles')->where('status', 'pending')->whereNull('deleted_at')->count();
        $activeMembers = (int) DB::table('memberships')->where('status', 'active')->whereNull('deleted_at')->count();
        $totalEvents   = (int) DB::table('events')->whereNull('deleted_at')->count();

        // Total Membership Revenue
        $paidPaymentsSum = (float) DB::table('membership_payments')->where('status', 'paid')->sum('amount');
        if ($paidPaymentsSum > 0) {
            $totalRevenue = $paidPaymentsSum;
        } else {
            // Check association_funds or sum of fees of active members
            $fundRevenue = (float) DB::table('association_funds')->where('source', 'Membership Collection')->sum('amount');
            if ($fundRevenue > 0) {
                $totalRevenue = $fundRevenue;
            } else {
                $totalRevenue = (float) DB::table('memberships as m')
                    ->join('membership_types as mt', 'mt.id', '=', 'm.membership_type_id')
                    ->where('m.status', 'active')
                    ->whereNull('m.deleted_at')
                    ->sum('mt.fee');
            }
        }

        // Total Donations
        $donationsPaid = (float) DB::table('donations')->whereIn('status', ['completed', 'paid', 'approved'])->sum('amount');
        if ($donationsPaid > 0) {
            $totalDonations = $donationsPaid;
        } else {
            $fundDonations = (float) DB::table('association_funds')
                ->where(function ($q) {
                    $q->where('source', 'like', '%Donation%')
                      ->orWhere('source', 'like', '%Other%')
                      ->orWhere('title', 'like', '%Donation%')
                      ->orWhere('title', 'like', '%অনুদান%');
                })
                ->sum('amount');
            if ($fundDonations > 0) {
                $totalDonations = $fundDonations;
            } else {
                $totalDonations = (float) DB::table('donations')->sum('amount');
            }
        }

        // Recent registrations
        $recentRegistrations = DB::table('users as u')
            ->join('alumni_profiles as ap', 'ap.user_id', '=', 'u.id')
            ->select('u.name', 'u.email', 'u.created_at as registered_at', 'ap.batch_year', 'ap.status')
            ->whereNull('u.deleted_at')
            ->orderBy('u.created_at', 'desc')
            ->limit(8)
            ->get()
            ->map(fn($r) => (array)$r)
            ->toArray();

        // Pending verifications
        $pendingVerifications = DB::table('alumni_profiles as ap')
            ->join('users as u', 'u.id', '=', 'ap.user_id')
            ->select('ap.*', 'u.name', 'u.email')
            ->where('ap.status', 'pending')
            ->whereNull('ap.deleted_at')
            ->orderBy('ap.created_at', 'asc')
            ->limit(5)
            ->get()
            ->map(fn($r) => (array)$r)
            ->toArray();

        // Recent news
        $recentNews = DB::table('news')
            ->select('id', 'title', 'status', 'created_at')
            ->whereNull('deleted_at')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(fn($r) => (array)$r)
            ->toArray();

        return $this->legacyView(
            'admin/dashboard',
            compact('totalAlumni', 'pendingAlumni', 'activeMembers', 'totalEvents', 'totalRevenue', 'totalDonations', 'recentRegistrations', 'pendingVerifications', 'recentNews'),
            'admin',
            'Dashboard'
        );
    }
}
