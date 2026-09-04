<?php
declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CommitteeController extends BaseController
{
    public function index(Request $request)
    {
        $members = DB::table('committee_members as cm')
            ->join('users as u', 'u.id', '=', 'cm.user_id')
            ->leftJoin('alumni_profiles as ap', 'ap.user_id', '=', 'cm.user_id')
            ->select('cm.*', 'u.name', 'ap.avatar')
            ->whereNull('cm.deleted_at')
            ->orderBy('cm.committee_type')
            ->orderBy('cm.sort_order')
            ->get()
            ->map(fn($r) => (array)$r)
            ->toArray();

        return $this->legacyView('admin/committee/index', compact('members'), 'admin', 'Committee Management');
    }

    public function create(Request $request)
    {
        $alumni = DB::table('users as u')
            ->join('alumni_profiles as ap', 'ap.user_id', '=', 'u.id')
            ->select('u.id', 'u.name', 'u.email', 'ap.batch_year')
            ->whereIn('ap.status', ['approved', 'verified', 'active'])
            ->whereNull('u.deleted_at')
            ->orderBy('u.name')
            ->get()
            ->map(fn($r) => (array)$r)
            ->toArray();

        $member = null;
        return $this->legacyView('admin/committee/form', compact('alumni', 'member'), 'admin', 'Add Committee Member');
    }

    public function store(Request $request)
    {
        DB::table('committee_members')->insert([
            'user_id'            => $request->input('user_id'),
            'committee_type'     => $request->input('committee_type', 'executive'),
            'designation'        => $request->input('designation'),
            'sort_order'         => (int)$request->input('sort_order', 0),
            'can_manage_finance' => $request->input('can_manage_finance') ? 1 : 0,
            'is_active'          => 1,
            'from_date'          => $request->input('from_date') ?: null,
            'created_at'         => now(),
            'updated_at'         => now(),
        ]);

        return redirect('/admin/committee')->with('success', 'Committee member added.');
    }

    public function edit(Request $request, $id)
    {
        $id     = (int)$id;
        $member = DB::table('committee_members')->where('id', $id)->first();
        if (!$member) {
            abort(404);
        }
        $member = (array)$member;

        $alumni = DB::table('users as u')
            ->join('alumni_profiles as ap', 'ap.user_id', '=', 'u.id')
            ->select('u.id', 'u.name', 'u.email', 'ap.batch_year')
            ->whereIn('ap.status', ['approved', 'verified', 'active'])
            ->whereNull('u.deleted_at')
            ->orderBy('u.name')
            ->get()
            ->map(fn($r) => (array)$r)
            ->toArray();

        return $this->legacyView('admin/committee/form', compact('member', 'alumni'), 'admin', 'Edit Committee Member');
    }

    public function update(Request $request, $id)
    {
        $id = (int)$id;
        DB::table('committee_members')->where('id', $id)->update([
            'committee_type'     => $request->input('committee_type'),
            'designation'        => $request->input('designation'),
            'sort_order'         => (int)$request->input('sort_order', 0),
            'can_manage_finance' => $request->input('can_manage_finance') ? 1 : 0,
            'from_date'          => $request->input('from_date') ?: null,
            'updated_at'         => now(),
        ]);

        return redirect('/admin/committee')->with('success', 'Member updated.');
    }

    public function toggleFinance(Request $request, $id)
    {
        $id = (int)$id;
        $curr = (int) DB::table('committee_members')->where('id', $id)->value('can_manage_finance');
        $newStatus = $curr ? 0 : 1;

        DB::table('committee_members')->where('id', $id)->update([
            'can_manage_finance' => $newStatus,
            'updated_at'         => now(),
        ]);

        $msg = $newStatus ? 'কমিটি সদস্যকে আর্থিক অ্যাক্সেস (Finance Access) প্রদান করা হয়েছে।' : 'আর্থিক অ্যাক্সেস (Finance Access) বাতিল করা হয়েছে।';
        return redirect('/admin/committee')->with('success', $msg);
    }
}
