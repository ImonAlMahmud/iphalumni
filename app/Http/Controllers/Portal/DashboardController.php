<?php
declare(strict_types=1);

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\BaseController;
use App\Models\AlumniProfile;
use App\Models\Event;
use App\Models\Membership;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends BaseController
{
    public function index(Request $request)
    {
        $user    = Auth::user();
        $profile = (new AlumniProfile())->getByUserId((int)$user->id);
        $membership = $profile ? (new Membership())->getByAlumni((int)$profile['id']) : null;
        $upcomingEvents = (new Event())->getUpcoming(3);

        // Profile completion %
        $fields    = ['phone', 'bio', 'current_location', 'batch_year', 'avatar'];
        $filled    = 0;
        foreach ($fields as $f) {
            if (!empty($profile[$f])) $filled++;
        }
        $completion = $profile ? (int)(($filled / count($fields)) * 100) : 0;

        // Notifications count
        $notifCount = 0;
        if ($profile) {
            $notifCount = DB::table('notifications')
                ->where('user_id', $user->id)
                ->where('is_read', 0)
                ->count();
        }

        return $this->legacyView(
            'portal/dashboard',
            compact('user', 'profile', 'membership', 'upcomingEvents', 'completion', 'notifCount'),
            'portal',
            'My Dashboard'
        );
    }

    public function idCard(Request $request)
    {
        $user       = Auth::user();
        $profile    = (new AlumniProfile())->getByUserId((int)$user->id);
        $membership = $profile ? (new Membership())->getByAlumni((int)$profile['id']) : null;

        return $this->legacyView('portal/id_card', compact('user', 'profile', 'membership'), 'portal', 'Digital Alumni ID Card');
    }

    public function notifications(Request $request)
    {
        $user = Auth::user();
        $notifications = DB::table('notifications')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get()
            ->map(fn($r) => (array)$r)
            ->toArray();

        // Mark all as read
        DB::table('notifications')->where('user_id', $user->id)->update(['is_read' => 1]);

        return $this->legacyView('portal/notifications', compact('notifications'), 'portal', 'Notifications');
    }
}
