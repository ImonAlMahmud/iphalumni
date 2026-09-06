<?php
declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\ApiToken;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ApiTokenMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();

        if (!$token) {
            $token = $request->query('api_token') ?? $request->query('token') ?? $request->header('X-API-TOKEN');
        }

        if (!$token) {
            return response()->json([
                'success'    => false,
                'message'    => 'Unauthenticated. Valid Bearer token is required.',
                'error_code' => 'AUTH_TOKEN_MISSING',
            ], 401);
        }

        $tokenRecord = ApiToken::findValidToken((string)$token);

        if (!$tokenRecord || !$tokenRecord->user) {
            return response()->json([
                'success'    => false,
                'message'    => 'Invalid, expired, or revoked API token.',
                'error_code' => 'AUTH_TOKEN_INVALID',
            ], 401);
        }

        // Update last used time quietly
        $tokenRecord->forceFill(['last_used_at' => now()])->saveQuietly();

        // Bind user to current request & auth guard
        Auth::setUser($tokenRecord->user);
        $request->merge(['_api_user' => $tokenRecord->user]);
        $request->attributes->set('api_token_record', $tokenRecord);

        return $next($request);
    }
}
