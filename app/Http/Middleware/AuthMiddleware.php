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
