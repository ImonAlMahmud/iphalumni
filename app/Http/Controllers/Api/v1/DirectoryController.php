<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api\v1;

use App\Models\AlumniProfile;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DirectoryController extends BaseApiController
{
    /**
     * Search Alumni Directory with filters
     */
    public function index(Request $request): JsonResponse
    {
        $search     = trim((string)$request->input('search', ''));
        $batch      = trim((string)$request->input('batch', ''));
        $bloodGroup = trim((string)$request->input('blood_group', ''));
        $location   = trim((string)$request->input('location', ''));
        $hasMember  = $request->input('has_membership');
        $perPage    = min(50, max(1, (int)$request->input('per_page', 15)));

        $query = DB::table('alumni_profiles as ap')
            ->join('users as u', 'u.id', '=', 'ap.user_id')
            ->leftJoin('memberships as m', function ($join) {
                $join->on('m.alumni_profile_id', '=', 'ap.id')
                    ->where('m.status', '=', 'active');
            })
            ->leftJoin('membership_types as mt', 'mt.id', '=', 'm.membership_type_id')
            ->whereIn('ap.status', ['approved', 'verified'])
            ->where('ap.is_public', 1)
            ->whereNull('u.deleted_at')
            ->whereNull('ap.deleted_at');

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('u.name', 'like', "%{$search}%")
                  ->orWhere('ap.batch_year', 'like', "%{$search}%")
                  ->orWhere('ap.specialization', 'like', "%{$search}%")
                  ->orWhere('ap.skills', 'like', "%{$search}%")
                  ->orWhere('ap.current_location', 'like', "%{$search}%");
            });
        }

        if ($batch !== '') {
            $query->where('ap.batch_year', $batch);
        }

        if ($bloodGroup !== '') {
            $query->where('ap.blood_group', $bloodGroup);
        }

        if ($location !== '') {
            $query->where('ap.current_location', 'like', "%{$location}%");
        }

        if ($hasMember === '1' || $hasMember === 'true') {
            $query->whereNotNull('m.id');
        }

        $paginator = $query->select(
            'ap.id as profile_id',
            'u.id as user_id',
            'u.name',
            'u.email',
            'u.avatar as user_avatar',
            'ap.avatar as profile_avatar',
            'ap.batch_year',
            'ap.blood_group',
            'ap.specialization',
            'ap.current_location',
            'ap.is_mentor',
            'm.id as membership_id',
            'm.membership_number',
            'mt.name as membership_type'
        )
        ->orderByRaw('CASE WHEN m.id IS NOT NULL THEN 0 ELSE 1 END')
        ->orderBy('ap.id', 'asc')
        ->paginate($perPage);

        $items = collect($paginator->items())->map(function ($item) {
            $avatar = $item->user_avatar ? url($item->user_avatar) : ($item->profile_avatar ? url($item->profile_avatar) : null);
            return [
                'profile_id'         => $item->profile_id,
                'user_id'            => $item->user_id,
                'name'               => $item->name,
                'email'              => $item->email,
                'avatar_url'         => $avatar,
                'batch_year'         => $item->batch_year,
                'blood_group'        => $item->blood_group,
                'specialization'     => $item->specialization,
                'current_location'   => $item->current_location,
                'is_mentor'          => (bool)$item->is_mentor,
                'is_premium_member'  => !empty($item->membership_id),
                'membership_type'    => $item->membership_type,
                'membership_number'  => $item->membership_number,
                'profile_url'        => url('/directory/' . $item->profile_id),
            ];
        });

        return $this->successResponse($items, 'Alumni directory retrieved.', 200, [
            'current_page' => $paginator->currentPage(),
            'per_page'     => $paginator->perPage(),
            'total'        => $paginator->total(),
            'last_page'    => $paginator->lastPage(),
        ]);
    }

    /**
     * Detailed Public Profile for an Alumni
     */
    public function show(int|string $id): JsonResponse
    {
        $profile = AlumniProfile::where('id', $id)
            ->whereIn('status', ['approved', 'verified'])
            ->first();

        if (!$profile) {
            return $this->errorResponse('Alumni profile not found.', 404, null, 'PROFILE_NOT_FOUND');
        }

        $user = DB::table('users')->where('id', $profile->user_id)->first();

        $membership = DB::table('memberships as m')
            ->leftJoin('membership_types as mt', 'mt.id', '=', 'm.membership_type_id')
            ->where('m.alumni_profile_id', $profile->id)
            ->where('m.status', 'active')
            ->select('m.id', 'm.membership_number', 'mt.name as membership_type', 'm.start_date')
            ->first();

        $education = DB::table('alumni_education')
            ->where('alumni_profile_id', $profile->id)
            ->orderBy('graduation_year', 'desc')
            ->get();

        $employment = DB::table('alumni_employment')
            ->where('alumni_profile_id', $profile->id)
            ->orderBy('is_current', 'desc')
            ->get();

        $avatar = $user?->avatar ? url($user->avatar) : ($profile->avatar ? url($profile->avatar) : null);

        return $this->successResponse([
            'profile_id'        => $profile->id,
            'name'              => $user?->name,
            'email'             => $user?->email,
            'avatar_url'        => $avatar,
            'batch_year'        => $profile->batch_year,
            'blood_group'       => $profile->blood_group,
            'current_location'  => $profile->current_location,
            'bio'               => $profile->bio,
            'specialization'    => $profile->specialization,
            'skills'            => $profile->skills,
            'linkedin_url'      => $profile->linkedin_url,
            'facebook_url'      => $profile->facebook_url,
            'website'           => $profile->website,
            'is_mentor'         => (bool)$profile->is_mentor,
            'is_premium_member' => !empty($membership),
            'membership'        => $membership,
            'education'         => $education,
            'employment'        => $employment,
        ], 'Alumni details retrieved.');
    }
}
