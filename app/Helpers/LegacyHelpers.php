<?php
declare(strict_types=1);

if (!function_exists('initials')) {
    /**
     * Get initials from a person's name (e.g., "John Doe" -> "JD", "Mahmud" -> "M")
     */
    function initials(?string $name): string
    {
        if (empty($name)) {
            return 'A';
        }
        $words = preg_split('/\s+/', trim($name));
        $initials = '';
        foreach ($words as $w) {
            if (!empty($w)) {
                $initials .= mb_substr($w, 0, 1);
            }
            if (mb_strlen($initials) >= 2) {
                break;
            }
        }
        return mb_strtoupper($initials ?: 'A');
    }
}

if (!function_exists('to_bn_number')) {
    /**
     * Convert English digits to Bengali digits
     */
    function to_bn_number(int|string|float $num): string
    {
        $bn = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];
        $en = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
        return str_replace($en, $bn, (string)$num);
    }
}

if (!function_exists('__')) {
    /**
     * Bilingual translation helper compatible with both legacy and Laravel usage:
     * - Legacy bilingual usage: __($bn, $en)
     * - Laravel translation: __($key, array $replace = [], $locale = null)
     */
    function __($key = null, $replace = [], $locale = null): mixed
    {
        if (is_null($key)) {
            return $key;
        }

        // If 2nd parameter is a string, it's legacy bilingual translation __($bn, $en)
        if (is_string($replace)) {
            $lang = session('locale', session('lang', 'bn'));
            return ($lang === 'en' && !empty($replace)) ? $replace : $key;
        }

        if (!is_array($replace)) {
            $replace = [];
        }

        try {
            return app('translator')->get($key, $replace, $locale);
        } catch (\Throwable) {
            return $key;
        }
    }
}

if (!function_exists('view_path')) {
    /**
     * Get the full path to a view file (used in legacy views)
     */
    function view_path(string $path): string
    {
        return resource_path('views/' . $path);
    }
}

if (!function_exists('auth_user')) {
    /**
     * Get the current authenticated user as an array (legacy compat)
     */
    function auth_user(): ?array
    {
        $user = \Illuminate\Support\Facades\Auth::user();
        return $user ? $user->toArray() : null;
    }
}

if (!function_exists('auth')) {
    /**
     * Legacy auth() helper — returns array if called with no args, or AuthManager
     */
    function auth($guard = null): mixed
    {
        if (is_null($guard)) {
            $user = \Illuminate\Support\Facades\Auth::user();
            return $user ? $user->toArray() : null;
        }
        return app('auth')->guard($guard);
    }
}

if (!function_exists('is_admin')) {
    /**
     * Check if current user is admin (legacy helper)
     */
    function is_admin(): bool
    {
        $user = \Illuminate\Support\Facades\Auth::user();
        return $user && in_array($user->role, ['super_admin', 'admin', 'editor']);
    }
}

if (!function_exists('is_logged_in')) {
    /**
     * Check if user is authenticated (legacy helper)
     */
    function is_logged_in(): bool
    {
        return \Illuminate\Support\Facades\Auth::check();
    }
}

if (!function_exists('flash')) {
    /**
     * Flash a message to session or get flashed message (legacy helper)
     */
    function flash(string $key, mixed $value = null): mixed
    {
        if (func_num_args() === 1) {
            return session($key);
        }
        session()->flash($key, $value);
        return null;
    }
}

if (!function_exists('has_flash')) {
    /**
     * Check if session has a flashed message
     */
    function has_flash(string $key): bool
    {
        return session()->has($key);
    }
}

if (!function_exists('get_flash')) {
    /**
     * Get flashed message from session
     */
    function get_flash(string $key, mixed $default = null): mixed
    {
        return session($key, $default);
    }
}

if (!function_exists('old_value')) {
    /**
     * Get old form input value (legacy helper, Laravel old() is already available)
     */
    function old_value(string $key, mixed $default = ''): mixed
    {
        return old($key, $default);
    }
}

if (!function_exists('asset_url')) {
    /**
     * Generate URL to a public asset (legacy compat, wraps Laravel asset())
     */
    function asset_url(string $path): string
    {
        return asset($path);
    }
}

if (!function_exists('current_url')) {
    /**
     * Get current full URL
     */
    function current_url(): string
    {
        return url()->current();
    }
}

if (!function_exists('csrf_token_value')) {
    function csrf_token_value(): string
    {
        return csrf_token();
    }
}

if (!function_exists('csrf_verify')) {
    function csrf_verify(): bool
    {
        return true; // Laravel handles CSRF via middleware
    }
}

if (!function_exists('csrf_field_html')) {
    /**
     * Output CSRF hidden field HTML (legacy compat)
     */
    function csrf_field_html(): string
    {
        return '<input type="hidden" name="_token" value="' . csrf_token() . '">';
    }
}

if (!function_exists('upload_url')) {
    /**
     * Get URL for uploaded file (checking public storage first, then uploads folder)
     */
    function upload_url(?string $filename, string $subDir = 'avatars'): string
    {
        if (empty($filename)) {
            return asset('images/default-avatar.png');
        }

        if (str_starts_with($filename, 'http://') || str_starts_with($filename, 'https://')) {
            return $filename;
        }

        if (file_exists(public_path("storage/{$subDir}/{$filename}"))) {
            return asset("storage/{$subDir}/{$filename}");
        }

        if (file_exists(public_path("uploads/{$subDir}/{$filename}"))) {
            return asset("uploads/{$subDir}/{$filename}");
        }

        if (file_exists(public_path("storage/{$filename}"))) {
            return asset("storage/{$filename}");
        }

        return asset("storage/{$subDir}/{$filename}");
    }
}

if (!function_exists('avatar_url')) {
    /**
     * Get URL for alumni avatar
     */
    function avatar_url(?string $filename): string
    {
        if (empty($filename)) {
            return '';
        }
        return upload_url($filename, 'avatars');
    }
}

if (!function_exists('signature_url')) {
    /**
     * Get URL for digital signature image
     */
    function signature_url(?string $filename): string
    {
        if (empty($filename)) {
            return '';
        }
        return upload_url($filename, 'signatures');
    }
}
