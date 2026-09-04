<?php
declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;

class AuditLogger
{
    public static function log(string $action, ?string $description = null): void
    {
        try {
            $userId   = Auth::id();
            $userName = Auth::user()?->name;
            $userRole = Auth::user()?->role;

            DB::table('activity_logs')->insert([
                'user_id'     => $userId,
                'user_name'   => $userName,
                'user_role'   => $userRole,
                'action'      => $action,
                'description' => $description,
                'ip_address'  => Request::ip(),
                'user_agent'  => mb_substr(Request::userAgent() ?? '', 0, 250),
                'created_at'  => now(),
            ]);
        } catch (\Exception $e) {
            // Silence logger errors to prevent breaking application flow
        }
    }
}
