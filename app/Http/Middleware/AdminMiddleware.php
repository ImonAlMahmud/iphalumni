<?php
declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please log in to continue.');
        }

        $role = Auth::user()->role ?? '';
        if (!in_array($role, ['super_admin', 'admin', 'editor'])) {
            abort(403, 'Access denied.');
        }

        return $next($request);
    }
}
