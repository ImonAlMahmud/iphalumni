<?php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class News extends Model
{
    use SoftDeletes;

    protected $table = 'news';

    protected $fillable = [
        'title', 'slug', 'content', 'excerpt', 'cover_image',
        'status', 'published_at', 'author_id',
    ];

    public function getPublished(int $page = 1, int $perPage = 9): array
    {
        $offset = ($page - 1) * $perPage;
        $total  = DB::table('news')->where('status', 'published')->whereNull('deleted_at')->count();
        $items  = DB::table('news')
            ->where('status', 'published')
            ->whereNull('deleted_at')
            ->orderBy('published_at', 'DESC')
            ->limit($perPage)
            ->offset($offset)
            ->get();

        return [
            'items'        => array_map(fn($r) => (array)$r, $items->toArray()),
            'total'        => $total,
            'per_page'     => $perPage,
            'current_page' => $page,
            'last_page'    => (int)ceil($total / $perPage),
        ];
    }

    public function getLatest(int $limit = 5): array
    {
        return array_map(fn($r) => (array)$r, DB::table('news')
            ->where('status', 'published')
            ->whereNull('deleted_at')
            ->orderBy('published_at', 'DESC')
            ->limit($limit)
            ->get()->toArray());
    }

    public function findBySlug(string $slug): ?array
    {
        $result = DB::table('news')->where('slug', $slug)->whereNull('deleted_at')->first();
        return $result ? (array)$result : null;
    }
}
