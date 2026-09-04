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
        $totalAlumni   = (int) DB::table('alumni_profiles')->whereNull('deleted_at')->count();
        $pendingAlumni = (int) DB::table('alumni_profiles')->where('status', 'pending')->whereNull('deleted_at')->count();
        $activeMembers = (int) DB::table('memberships')->where('status', 'active')->whereNull('deleted_at')->count();
        $totalEvents   = (int) DB::table('events')->whereNull('deleted_at')->count();
        $totalRevenue  = (float) DB::table('membership_payments')->where('status', 'paid')->sum('amount');
        $totalDonations = (float) DB::table('donations')->where('status', 'completed')->sum('amount');

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
