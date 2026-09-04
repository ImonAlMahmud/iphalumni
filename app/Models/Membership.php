<?php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Membership extends Model
{
    use SoftDeletes;

    protected $table = 'memberships';

    protected $fillable = [
        'alumni_profile_id', 'membership_type_id', 'status',
        'start_date', 'end_date', 'membership_number',
    ];

    public function getByAlumni(int $profileId): ?array
    {
        $sql = "SELECT m.*, mt.name as type_name, mt.fee, mt.duration_months
                FROM memberships m
                JOIN membership_types mt ON mt.id = m.membership_type_id
                WHERE m.alumni_profile_id = ? AND m.deleted_at IS NULL
                ORDER BY m.created_at DESC LIMIT 1";

        $result = DB::selectOne($sql, [$profileId]);
        return $result ? (array)$result : null;
    }

    public function getStats(): array
    {
        return [
            'total'    => DB::table('memberships')->whereNull('deleted_at')->where('status', 'active')->count(),
            'annual'   => DB::table('memberships as m')->join('membership_types as mt', 'mt.id', '=', 'm.membership_type_id')->where('mt.slug', 'annual')->where('m.status', 'active')->whereNull('m.deleted_at')->count(),
            'lifetime' => DB::table('memberships as m')->join('membership_types as mt', 'mt.id', '=', 'm.membership_type_id')->where('mt.slug', 'lifetime')->where('m.status', 'active')->whereNull('m.deleted_at')->count(),
            'honorary' => DB::table('memberships as m')->join('membership_types as mt', 'mt.id', '=', 'm.membership_type_id')->where('mt.slug', 'honorary')->where('m.status', 'active')->whereNull('m.deleted_at')->count(),
            'revenue'  => (float) DB::table('membership_payments')->where('status', 'paid')->sum('amount'),
        ];
    }

    public function getPending(): array
    {
        $sql = "SELECT m.*, u.name, u.email, mt.name as type_name, mp.method as payment_method, mp.transaction_id, mp.payment_slip
                FROM memberships m
                JOIN alumni_profiles ap ON ap.id = m.alumni_profile_id
                JOIN users u ON u.id = ap.user_id
                JOIN membership_types mt ON mt.id = m.membership_type_id
                LEFT JOIN membership_payments mp ON mp.membership_id = m.id
                WHERE m.status='pending' AND m.deleted_at IS NULL
                ORDER BY m.created_at ASC";

        return array_map(fn($r) => (array)$r, DB::select($sql));
    }
}
