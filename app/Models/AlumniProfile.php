<?php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class AlumniProfile extends Model
{
    use SoftDeletes;

    protected $table = 'alumni_profiles';

    protected $fillable = [
        'user_id', 'batch_year', 'phone', 'secondary_email', 'bio', 'avatar', 'signature',
        'gender', 'dob', 'blood_group', 'nid_number', 'student_id',
        'current_location', 'permanent_location', 'permanent_district', 'permanent_upazila',
        'thana_upazila', 'province_city', 'country',
        'location_type', 'website', 'linkedin', 'status', 'is_featured',
    ];

    // ── Relationships ────────────────────────────────────────────────────────

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ── Custom Queries (ported from PDO) ─────────────────────────────────────

    public function search(array|string $filters = [], int|string $page = 1, int|string $perPage = 12): array
    {
        if (is_string($filters)) {
            $filters = ['q' => $filters, 'batch' => is_string($page) ? $page : ''];
            $page    = is_numeric($perPage) ? (int)$perPage : 1;
            $perPage = 12;
        }

        $page    = max(1, (int)$page);
        $perPage = max(1, (int)$perPage);

        $where  = ['ap.deleted_at IS NULL'];
        $params = [];

        $q           = trim($filters['q'] ?? '');
        $batchVal    = trim($filters['batch'] ?? '');
        $university  = trim($filters['university'] ?? '');
        $programme   = trim($filters['programme'] ?? '');
        $phone       = trim($filters['phone'] ?? '');
        $designation = trim($filters['designation'] ?? '');
        $location    = trim($filters['location'] ?? '');
        $country     = trim($filters['country'] ?? '');
        $locType     = trim($filters['location_type'] ?? '');

        if ($q !== '') {
            $where[]  = "(u.name LIKE ? OR ap.phone LIKE ? OR ap.current_location LIKE ? OR ap.country LIKE ? OR ae.organization LIKE ? OR ae.job_title LIKE ?)";
            $like     = "%{$q}%";
            $params[] = $like; $params[] = $like; $params[] = $like; $params[] = $like; $params[] = $like; $params[] = $like;
        }
        if ($batchVal !== '') { $where[] = "ap.batch_year = ?"; $params[] = $batchVal; }
        if ($university !== '') { $where[] = "aedu.institution LIKE ?"; $params[] = "%{$university}%"; }
        if ($programme !== '') { $where[] = "aedu.degree LIKE ?"; $params[] = "%{$programme}%"; }
        if ($phone !== '') { $where[] = "ap.phone LIKE ?"; $params[] = "%{$phone}%"; }
        if ($designation !== '') { $where[] = "ae.job_title LIKE ?"; $params[] = "%{$designation}%"; }
        $organization = trim($filters['organization'] ?? $filters['employment_place'] ?? '');
        if ($organization !== '') {
            $where[] = "(ae.organization LIKE ? OR ae.department LIKE ?)";
            $params[] = "%{$organization}%"; $params[] = "%{$organization}%";
        }
        if ($location !== '') { $where[] = "(ap.current_location LIKE ? OR ap.thana_upazila LIKE ?)"; $params[] = "%{$location}%"; $params[] = "%{$location}%"; }
        if ($country !== '') { $where[] = "(ap.country LIKE ? OR ap.province_city LIKE ?)"; $params[] = "%{$country}%"; $params[] = "%{$country}%"; }
        if ($locType !== '') { $where[] = "ap.location_type = ?"; $params[] = $locType; }
        if (!empty($filters['is_featured'])) { $where[] = "ap.is_featured = 1"; }

        $requireMembership = isset($filters['require_membership'])
            ? !empty($filters['require_membership'])
            : ((new Setting())->get('directory_require_membership', '0') === '1');

        if ($requireMembership) {
            $where[] = "m.status = 'active' AND m.deleted_at IS NULL";
        }

        $whereStr = implode(' AND ', $where);
        $offset   = ($page - 1) * $perPage;

        $sql = "SELECT DISTINCT ap.*, u.name, u.email, u.role,
                       ae.job_title, ae.organization, ae.department,
                       aedu.degree, aedu.institution, aedu.field_of_study,
                       mt.name as membership_type, m.status as membership_status
                FROM alumni_profiles ap
                JOIN users u ON u.id = ap.user_id
                LEFT JOIN alumni_employment ae ON ae.alumni_profile_id = ap.id AND ae.is_current = 1
                LEFT JOIN alumni_education aedu ON aedu.alumni_profile_id = ap.id AND aedu.is_primary = 1
                LEFT JOIN memberships m ON m.alumni_profile_id = ap.id AND m.status = 'active' AND m.deleted_at IS NULL
                LEFT JOIN membership_types mt ON mt.id = m.membership_type_id
                WHERE {$whereStr} AND ap.status IN ('approved', 'verified', 'active')
                ORDER BY u.name ASC
                LIMIT ? OFFSET ?";

        $countSql = "SELECT COUNT(DISTINCT ap.id) as cnt FROM alumni_profiles ap
                     JOIN users u ON u.id = ap.user_id
                     LEFT JOIN alumni_employment ae ON ae.alumni_profile_id = ap.id AND ae.is_current = 1
                     LEFT JOIN alumni_education aedu ON aedu.alumni_profile_id = ap.id AND aedu.is_primary = 1
                     LEFT JOIN memberships m ON m.alumni_profile_id = ap.id AND m.status = 'active' AND m.deleted_at IS NULL
                     WHERE {$whereStr} AND ap.status IN ('approved', 'verified', 'active')";

        $total = (int) (DB::selectOne($countSql, $params)->cnt ?? 0);

        $params[] = $perPage;
        $params[] = $offset;
        $items = DB::select($sql, $params);

        return [
            'items'        => array_map(fn($r) => (array)$r, $items),
            'total'        => $total,
            'current_page' => $page,
            'per_page'     => $perPage,
            'last_page'    => (int)ceil($total / $perPage),
        ];
    }

    public function getByUserId(int $userId): ?array
    {
        $result = DB::table('alumni_profiles as ap')
            ->join('users as u', 'u.id', '=', 'ap.user_id')
            ->select('ap.*', 'u.name', 'u.email')
            ->where('ap.user_id', $userId)
            ->whereNull('ap.deleted_at')
            ->first();
        return $result ? (array)$result : null;
    }

    public function getPendingVerifications(): array
    {
        return array_map(fn($r) => (array)$r, DB::table('alumni_profiles as ap')
            ->join('users as u', 'u.id', '=', 'ap.user_id')
            ->select('ap.*', 'u.name', 'u.email', 'u.created_at as registered_at')
            ->whereIn('ap.status', ['pending', 'under_review'])
            ->whereNull('ap.deleted_at')
            ->orderBy('ap.created_at', 'ASC')
            ->get()->toArray());
    }

    public function getStats(): array
    {
        $total = DB::table('alumni_profiles')->whereNull('deleted_at')->whereIn('status', ['approved', 'verified', 'active'])->count();
        if ($total === 0) {
            $total = DB::table('users')->whereNull('deleted_at')->count();
        }

        $pending = DB::table('alumni_profiles')->whereNull('deleted_at')->whereIn('status', ['pending', 'under_review'])->count();

        // Distinct batches from students_reference matching the batch dropdown in admin/students
        $batches = DB::table('students_reference')
            ->whereNotNull('batch')
            ->where('batch', '!=', '')
            ->distinct()
            ->count('batch');

        if ($batches === 0) {
            $batches = count(DB::table('alumni_profiles')->whereNull('deleted_at')->whereNotNull('batch_year')->where('batch_year', '!=', '')->distinct()->pluck('batch_year'));
        }

        $countries = DB::table('alumni_profiles')->whereNull('deleted_at')->whereNotNull('current_location')->where('current_location', '!=', '')->distinct('current_location')->count('current_location');
        if ($countries === 0) {
            $countries = 1;
        }

        return [
            'total'     => max(1, $total),
            'pending'   => $pending,
            'batches'   => max(1, $batches),
            'countries' => max(1, $countries),
        ];
    }

    public function getEducation(int $profileId): array
    {
        $rows = DB::table('alumni_education')
            ->where('alumni_profile_id', $profileId)
            ->orderByRaw("CASE WHEN graduation_year IS NOT NULL AND graduation_year != '' THEN 0 ELSE 1 END ASC")
            ->orderBy('is_primary', 'DESC')
            ->orderBy('graduation_year', 'DESC')
            ->orderBy('id', 'ASC')
            ->get();

        $seen = [];
        $unique = [];
        foreach ($rows as $r) {
            $key = strtolower(trim((string)$r->degree)) . '|' . strtolower(trim((string)$r->institution));
            if (!isset($seen[$key])) {
                $seen[$key] = true;
                $unique[] = (array)$r;
            }
        }

        return $unique;
    }

    public function getEmployment(int $profileId): array
    {
        return array_map(fn($r) => (array)$r, DB::table('alumni_employment')
            ->where('alumni_profile_id', $profileId)
            ->orderBy('start_year', 'DESC')
            ->get()->toArray());
    }
}
