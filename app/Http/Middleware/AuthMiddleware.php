<?php
declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class AuthMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please log in to continue.');
        }

        // ── Block non-active users ────────────────────────────────────────────
        $user = Auth::user();
        if ($user->status !== 'active') {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            $messages = [
                'pending'  => 'আপনার অ্যাকাউন্টটি এখনো অ্যাডমিন কর্তৃক অনুমোদনের অপেক্ষায় আছে। অনুমোদিত হলে ইমেইলে জানানো হবে।',
                'rejected' => 'আপনার অ্যাকাউন্ট অনুমোদিত হয়নি। বিস্তারিত জানতে যোগাযোগ করুন।',
                'banned'   => 'আপনার অ্যাকাউন্ট সাময়িকভাবে স্থগিত করা হয়েছে।',
            ];

            $msg = $messages[$user->status] ?? 'আপনার অ্যাকাউন্ট সক্রিয় নয়। অনুগ্রহ করে অ্যাডমিনের সাথে যোগাযোগ করুন।';
            return redirect()->route('login')->with('error', $msg);
        }

        // Ensure an alumni_profile record exists for the logged-in user
        $userId = Auth::id();
        $exists = DB::table('alumni_profiles')->where('user_id', $userId)->exists();
        if (!$exists) {
            DB::table('alumni_profiles')->insert([
                'user_id'    => $userId,
                'status'     => 'approved',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return $next($request);
    }
}
