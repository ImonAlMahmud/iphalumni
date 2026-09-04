<?php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Event extends Model
{
    use SoftDeletes;

    protected $table = 'events';

    protected $fillable = [
        'title', 'slug', 'description', 'event_date', 'event_time',
        'venue', 'cover_image', 'status', 'organizer_id', 'capacity',
        'registration_fee',
    ];

    public function getUpcoming(int $limit = 6): array
    {
        return array_map(fn($r) => (array)$r, DB::table('events')
            ->whereDate('event_date', '>=', now()->toDateString())
            ->where('status', 'published')
            ->whereNull('deleted_at')
            ->orderBy('event_date', 'ASC')
            ->limit($limit)
            ->get()->toArray());
    }

    public function getPast(int $limit = 6): array
    {
        return array_map(fn($r) => (array)$r, DB::table('events')
            ->whereDate('event_date', '<', now()->toDateString())
            ->where('status', 'published')
            ->whereNull('deleted_at')
            ->orderBy('event_date', 'DESC')
            ->limit($limit)
            ->get()->toArray());
    }

    public function findBySlug(string $slug): ?array
    {
        $result = DB::table('events')->where('slug', $slug)->whereNull('deleted_at')->first();
        return $result ? (array)$result : null;
    }

    public function isRegistered(int $eventId, int $userId): bool
    {
        return DB::table('event_registrations')
            ->where('event_id', $eventId)
            ->where('user_id', $userId)
            ->exists();
    }
}
