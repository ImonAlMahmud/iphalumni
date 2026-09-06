<?php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class ApiToken extends Model
{
    protected $table = 'api_tokens';

    protected $fillable = [
        'user_id',
        'token',
        'name',
        'device_name',
        'last_used_at',
        'expires_at',
    ];

    protected $casts = [
        'last_used_at' => 'datetime',
        'expires_at'   => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Create a new API token for a user.
     */
    public static function createToken(User $user, string $name = 'mobile_app', ?string $deviceName = null, ?int $expiryDays = 90): array
    {
        // 64 random characters hex token
        $rawToken = bin2hex(random_bytes(32));

        $tokenRecord = self::create([
            'user_id'      => $user->id,
            'token'        => hash('sha256', $rawToken),
            'name'         => $name,
            'device_name'  => $deviceName,
            'expires_at'   => $expiryDays ? now()->addDays($expiryDays) : null,
            'last_used_at' => now(),
        ]);

        return [
            'plain_token' => $rawToken,
            'token_id'    => $tokenRecord->id,
            'expires_at'  => $tokenRecord->expires_at?->toIso8601String(),
        ];
    }

    /**
     * Find a valid token by plain string.
     */
    public static function findValidToken(string $rawToken): ?self
    {
        $hashed = hash('sha256', $rawToken);
        $record = self::where('token', $hashed)->first();

        if (!$record) {
            // Fallback: check if rawToken is stored directly (legacy or test tokens)
            $record = self::where('token', $rawToken)->first();
        }

        if (!$record) {
            return null;
        }

        // Check expiry if set
        if ($record->expires_at && $record->expires_at->isPast()) {
            return null;
        }

        return $record;
    }
}
