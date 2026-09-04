<?php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Job extends Model
{
    use SoftDeletes;

    protected $table = 'jobs';

    protected $fillable = [
        'title', 'company_name', 'location', 'description', 'requirements',
        'job_type', 'salary_range', 'deadline', 'status', 'visibility',
        'user_id', 'contact_email', 'contact_phone',
    ];

    public function search(array $filters = [], int $page = 1, int $perPage = 12): array
    {
        $page    = max(1, $page);
        $perPage = max(1, $perPage);
        $offset  = ($page - 1) * $perPage;

        $where  = ['j.deleted_at IS NULL'];
        $params = [];

        if (!empty($filters['visibility'])) { $where[] = 'j.visibility = ?'; $params[] = $filters['visibility']; }
        if (array_key_exists('status', $filters)) {
            if ($filters['status'] !== '') { $where[] = 'j.status = ?'; $params[] = $filters['status']; }
        } else {
            $where[] = "j.status = 'active'";
        }
        if (!empty($filters['q'])) {
            $where[] = '(j.title LIKE ? OR j.company_name LIKE ? OR j.location LIKE ? OR j.description LIKE ?)';
            $like    = '%' . trim($filters['q']) . '%';
            $params[] = $like; $params[] = $like; $params[] = $like; $params[] = $like;
        }
        if (!empty($filters['job_type'])) { $where[] = 'j.job_type = ?'; $params[] = $filters['job_type']; }
        if (!empty($filters['user_id'])) { $where[] = 'j.user_id = ?'; $params[] = (int)$filters['user_id']; }

        $whereStr  = implode(' AND ', $where);
        $total     = (int) DB::selectOne("SELECT COUNT(*) as cnt FROM jobs j WHERE {$whereStr}", $params)->cnt;

        $sql = "SELECT j.*, u.name as poster_name, ap.avatar as poster_avatar
                FROM jobs j
                LEFT JOIN users u ON u.id = j.user_id
                LEFT JOIN alumni_profiles ap ON ap.user_id = u.id
                WHERE {$whereStr}
                ORDER BY j.id DESC
                LIMIT {$perPage} OFFSET {$offset}";

        $items = array_map(fn($r) => (array)$r, DB::select($sql, $params));

        return [
            'items'        => $items,
            'total'        => $total,
            'current_page' => $page,
            'per_page'     => $perPage,
            'last_page'    => (int)ceil($total / $perPage),
        ];
    }

    public function findWithPoster(int $id): ?array
    {
        $sql = "SELECT j.*, u.name as poster_name, u.email as poster_email, ap.avatar as poster_avatar, ap.phone as poster_phone
                FROM jobs j
                LEFT JOIN users u ON u.id = j.user_id
                LEFT JOIN alumni_profiles ap ON ap.user_id = u.id
                WHERE j.id = ? AND j.deleted_at IS NULL LIMIT 1";

        $result = DB::selectOne($sql, [$id]);
        return $result ? (array)$result : null;
    }

    public function isUserVerifiedStudent(int $userId, ?string $inputName = null, ?string $inputPhone = null): ?array
    {
        $uResult = DB::selectOne(
            "SELECT u.*, ap.batch_year, ap.phone FROM users u LEFT JOIN alumni_profiles ap ON ap.user_id = u.id WHERE u.id = ? AND u.deleted_at IS NULL LIMIT 1",
            [$userId]
        );
        if (!$uResult) return null;
        $user = (array)$uResult;

        $cleanName = function(string $n): string {
            $n = mb_strtolower(trim($n));
            $n = preg_replace('/^(md|mst|dr|prof|mr|mrs|ms)\b\.?\s*/i', '', $n);
            return trim(str_replace(['.', '-', ' '], '', $n));
        };
        $cleanPhone = function(?string $p): string {
            if (!$p) return '';
            $p = preg_replace('/[^\d]/', '', $p);
            if (str_starts_with($p, '880')) $p = substr($p, 2);
            return $p;
        };

        $targetName  = $cleanName($inputName ?: $user['name']);
        $targetPhone = $cleanPhone($inputPhone ?: ($user['phone'] ?? ''));

        $candidates = array_map(fn($r) => (array)$r, DB::table('students_reference')->get()->toArray());

        foreach ($candidates as $cand) {
            $candEng = $cleanName($cand['name_english'] ?? '');
            $candBng = $cleanName($cand['name_bangla'] ?? '');
            $candMob = $cleanPhone($cand['mobile'] ?? '');
            $candGrd = $cleanPhone($cand['guardian_mobile'] ?? '');

            $nameMatches  = ($targetName !== '' && ($targetName === $candEng || $targetName === $candBng));
            $phoneMatches = ($targetPhone !== '' && ($targetPhone === $candMob || $targetPhone === $candGrd));

            if ($nameMatches && $phoneMatches) return $cand;
            if ($nameMatches && empty($inputPhone)) return $cand;
            if (!empty($user['email']) && !empty($cand['email']) && strtolower($user['email']) === strtolower($cand['email'])) return $cand;
        }

        return null;
    }

    public function getApplicationsForJob(int $jobId): array
    {
        $sql = "SELECT ja.*, u.name as applicant_user_name, ap.avatar as applicant_avatar
                FROM job_applications ja
                LEFT JOIN users u ON u.id = ja.user_id
                LEFT JOIN alumni_profiles ap ON ap.user_id = u.id
                WHERE ja.job_id = ? AND ja.deleted_at IS NULL
                ORDER BY ja.id DESC";
        return array_map(fn($r) => (array)$r, DB::select($sql, [$jobId]));
    }

    public function hasApplied(int $jobId, int $userId): bool
    {
        return DB::table('job_applications')
            ->where('job_id', $jobId)
            ->where('user_id', $userId)
            ->whereNull('deleted_at')
            ->exists();
    }

    public function apply(array $data): int|bool
    {
        return DB::table('job_applications')->insertGetId([
            'job_id'                 => $data['job_id'],
            'user_id'                => $data['user_id'],
            'student_reference_id'   => $data['student_reference_id'] ?? null,
            'applicant_name'         => $data['applicant_name'],
            'applicant_email'        => $data['applicant_email'],
            'applicant_phone'        => $data['applicant_phone'] ?? null,
            'resume_path'            => $data['resume_path'] ?? null,
            'cover_letter'           => $data['cover_letter'] ?? null,
            'status'                 => 'submitted',
            'created_at'             => now(),
            'updated_at'             => now(),
        ]);
    }
}
