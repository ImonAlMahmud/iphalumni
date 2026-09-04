<?php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Gallery extends Model
{
    use SoftDeletes;

    protected $table = 'gallery_albums';

    protected $fillable = [
        'title', 'description', 'status', 'created_by',
    ];

    public function getAlbumsWithCover(int $limit = 12): array
    {
        $sql = "SELECT ga.*,
                    (SELECT gp.filename FROM gallery_photos gp WHERE gp.album_id = ga.id LIMIT 1) as cover,
                    (SELECT COUNT(*) FROM gallery_photos gp WHERE gp.album_id = ga.id) as photo_count
                FROM gallery_albums ga
                WHERE ga.deleted_at IS NULL AND ga.status = 'published'
                ORDER BY ga.created_at DESC LIMIT ?";

        return array_map(fn($r) => (array)$r, DB::select($sql, [$limit]));
    }

    public function getPhotos(int $albumId): array
    {
        $sql = "SELECT gp.*, u.name as uploader_name
                FROM gallery_photos gp
                LEFT JOIN users u ON u.id = gp.uploaded_by
                WHERE gp.album_id = ?
                ORDER BY gp.sort_order ASC, gp.created_at ASC";

        return array_map(fn($r) => (array)$r, DB::select($sql, [$albumId]));
    }
}
