<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EventController extends BaseApiController
{
    /**
     * List Published Events (Upcoming & Recent)
     */
    public function index(Request $request): JsonResponse
    {
        $filter = $request->input('type', 'all'); // 'upcoming', 'past', 'all'
        $perPage = min(50, max(1, (int)$request->input('per_page', 10)));

        $query = DB::table('events')
            ->where('status', 'published')
            ->whereNull('deleted_at');

        if ($filter === 'upcoming') {
            $query->where('event_date', '>=', now()->toDateString());
        } elseif ($filter === 'past') {
            $query->where('event_date', '<', now()->toDateString());
        }

        $paginator = $query->orderBy('event_date', $filter === 'past' ? 'desc' : 'asc')
            ->paginate($perPage);

        $items = collect($paginator->items())->map(function ($ev) {
            $regCount = DB::table('event_registrations')->where('event_id', $ev->id)->count();

            return [
                'id'                 => $ev->id,
                'title'              => $ev->title,
                'slug'               => $ev->slug,
                'venue'              => $ev->venue,
                'event_date'         => $ev->event_date,
                'end_date'           => $ev->end_date,
                'cover_url'          => $ev->cover_image ? url($ev->cover_image) : null,
                'ticket_fee'         => (float)($ev->ticket_fee ?? $ev->registration_fee ?? 0),
                'is_online'          => (bool)$ev->is_online,
                'online_link'        => $ev->is_online ? $ev->online_link : null,
                'total_registered'   => $regCount,
                'max_attendees'      => $ev->max_attendees,
                'is_featured'        => (bool)$ev->is_featured,
                'is_crowdfunding'    => (bool)$ev->is_crowdfunding,
                'crowdfunding_goal'  => $ev->crowdfunding_goal ? (float)$ev->crowdfunding_goal : null,
                'share_url'          => url('/events/' . $ev->slug),
            ];
        });

        return $this->successResponse($items, 'Events retrieved successfully.', 200, [
            'current_page' => $paginator->currentPage(),
            'per_page'     => $paginator->perPage(),
            'total'        => $paginator->total(),
            'last_page'    => $paginator->lastPage(),
        ]);
    }

    /**
     * Get Detailed Event Info
     */
    public function show(int|string $id): JsonResponse
    {
        $ev = DB::table('events')
            ->where(is_numeric($id) ? 'id' : 'slug', $id)
            ->where('status', 'published')
            ->whereNull('deleted_at')
            ->first();

        if (!$ev) {
            return $this->errorResponse('Event not found.', 404, null, 'EVENT_NOT_FOUND');
        }

        $regCount = DB::table('event_registrations')->where('event_id', $ev->id)->count();

        return $this->successResponse([
            'id'                 => $ev->id,
            'title'              => $ev->title,
            'slug'               => $ev->slug,
            'description_html'   => $ev->description,
            'venue'              => $ev->venue,
            'event_date'         => $ev->event_date,
            'end_date'           => $ev->end_date,
            'cover_url'          => $ev->cover_image ? url($ev->cover_image) : null,
            'ticket_fee'         => (float)($ev->ticket_fee ?? $ev->registration_fee ?? 0),
            'registration_type'  => $ev->registration_type,
            'is_online'          => (bool)$ev->is_online,
            'online_link'        => $ev->is_online ? $ev->online_link : null,
            'total_registered'   => $regCount,
            'max_attendees'      => $ev->max_attendees,
            'is_crowdfunding'    => (bool)$ev->is_crowdfunding,
            'crowdfunding_goal'  => $ev->crowdfunding_goal ? (float)$ev->crowdfunding_goal : null,
            'share_url'          => url('/events/' . $ev->slug),
        ], 'Event details retrieved.');
    }
}
