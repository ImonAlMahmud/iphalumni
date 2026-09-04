<?php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'status',
        'alumni_profile_id',
        'avatar',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
    ];

    // ── Soft Delete support ──────────────────────────────────────────────────
    use \Illuminate\Database\Eloquent\SoftDeletes;

    // ── Relationships ────────────────────────────────────────────────────────

    public function alumniProfile()
    {
        return $this->hasOne(AlumniProfile::class, 'user_id');
    }

    // ── Custom Queries (ported from BaseModel/PDO pattern) ──────────────────

    public function findByEmail(string $email): ?array
    {
        return DB::table('users')
            ->where('email', $email)
            ->whereNull('deleted_at')
            ->first()?->toArray() ?? DB::table('users')
            ->where('email', $email)
            ->whereNull('deleted_at')
            ->first() ? (array) DB::table('users')->where('email', $email)->whereNull('deleted_at')->first() : null;
    }

    public function findWithProfile(int $id): ?array
    {
        $result = DB::table('users as u')
            ->leftJoin('alumni_profiles as ap', 'ap.user_id', '=', 'u.id')
            ->select(
                'u.*',
                'ap.batch_year',
                'ap.phone',
                'ap.bio',
                'ap.avatar',
                'ap.status as profile_status',
                'ap.id as profile_id'
            )
            ->where('u.id', $id)
            ->whereNull('u.deleted_at')
            ->first();

        return $result ? (array) $result : null;
    }

    public function updatePassword(int $id, string $hashedPassword): bool
    {
        return DB::table('users')->where('id', $id)->update(['password' => $hashedPassword, 'updated_at' => now()]) > 0;
    }

    public function getAdmins(): array
    {
        return DB::table('users')
            ->whereIn('role', ['super_admin', 'admin', 'editor'])
            ->whereNull('deleted_at')
            ->get()
            ->map(fn($u) => (array) $u)
            ->toArray();
    }

    // ── Helper: check if user is admin ───────────────────────────────────────
    public function isAdmin(): bool
    {
        return in_array($this->role, ['super_admin', 'admin', 'editor']);
    }
}
