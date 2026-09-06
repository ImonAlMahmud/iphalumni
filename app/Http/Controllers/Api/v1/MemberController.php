<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api\v1;

use App\Models\AlumniProfile;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MemberController extends BaseApiController
{
    /**
     * Get Complete Member Profile
     */
    public function profile(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        $profile = AlumniProfile::where('user_id', $user->id)->first();
        if (!$profile) {
            return $this->errorResponse('Alumni profile not found.', 404, null, 'PROFILE_NOT_FOUND');
        }

        $education = DB::table('alumni_education')
            ->where('alumni_profile_id', $profile->id)
            ->orderBy('graduation_year', 'desc')
            ->get();

        $employment = DB::table('alumni_employment')
            ->where('alumni_profile_id', $profile->id)
            ->orderBy('is_current', 'desc')
            ->orderBy('start_year', 'desc')
            ->get();

        $membership = DB::table('memberships as m')
            ->leftJoin('membership_types as mt', 'mt.id', '=', 'm.membership_type_id')
            ->where('m.alumni_profile_id', $profile->id)
            ->where('m.status', 'active')
            ->select('m.*', 'mt.name as type_name', 'mt.fee as type_fee', 'mt.duration_months')
            ->first();

        return $this->successResponse([
            'user'       => [
                'id'              => $user->id,
                'name'            => $user->name,
                'email'           => $user->email,
                'secondary_email' => $user->secondary_email,
                'role'            => $user->role,
                'avatar_url'      => $user->avatar ? url($user->avatar) : ($profile->avatar ? url($profile->avatar) : null),
            ],
            'profile'    => $profile,
            'education'  => $education,
            'employment' => $employment,
            'membership' => $membership,
        ], 'Member profile retrieved successfully.');
    }

    /**
     * Get Member Digital ID Card Dataset
     */
    public function idCard(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        $profile = AlumniProfile::where('user_id', $user->id)->first();
        if (!$profile) {
            return $this->errorResponse('Profile not found.', 404, null, 'PROFILE_NOT_FOUND');
        }

        $membership = DB::table('memberships as m')
            ->leftJoin('membership_types as mt', 'mt.id', '=', 'm.membership_type_id')
            ->where('m.alumni_profile_id', $profile->id)
            ->where('m.status', 'active')
            ->select('m.*', 'mt.name as membership_type_name')
            ->first();

        $membershipNumber = $membership?->membership_number ?? ('IPH-M-' . str_pad((string)$profile->id, 5, '0', STR_PAD_LEFT));
        $validUntil = $membership?->end_date ? date('d M, Y', strtotime($membership->end_date)) : 'Lifetime / Active';

        $qrData = [
            'type'        => 'iph_membership_pass',
            'member_no'   => $membershipNumber,
            'profile_id'  => $profile->id,
            'name'        => $user->name,
            'batch'       => $profile->batch_year ?? 'N/A',
            'blood'       => $profile->blood_group ?? 'N/A',
            'verify_url'  => url('/directory/' . $profile->id),
        ];

        return $this->successResponse([
            'card_title'       => 'IPH ALUMNI ASSOCIATION MEMBERSHIP CARD',
            'member_name'      => $user->name,
            'membership_no'    => $membershipNumber,
            'batch_year'       => $profile->batch_year ?? 'N/A',
            'blood_group'      => $profile->blood_group ?? 'N/A',
            'phone'            => $profile->phone ?? 'N/A',
            'email'            => $user->email,
            'avatar_url'       => $user->avatar ? url($user->avatar) : ($profile->avatar ? url($profile->avatar) : null),
            'membership_type'  => $membership?->membership_type_name ?? 'General Member',
            'is_active_member' => ($membership !== null),
            'issue_date'       => $membership?->start_date ? date('d M, Y', strtotime((string)$membership->start_date)) : ($profile->created_at ? (is_string($profile->created_at) ? date('d M, Y', strtotime($profile->created_at)) : $profile->created_at->format('d M, Y')) : date('d M, Y')),
            'valid_until'      => $validUntil,
            'qr_payload'       => json_encode($qrData),
            'verification_url' => url('/directory/' . $profile->id),
        ], 'Digital ID Card dataset retrieved.');
    }

    /**
     * Get Member Event Smart Passes
     */
    public function smartPass(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        $passes = DB::table('event_registrations as er')
            ->join('events as e', 'e.id', '=', 'er.event_id')
            ->where('er.user_id', $user->id)
            ->select(
                'er.id as registration_id',
                'er.pass_code',
                'er.payment_status',
                'er.amount',
                'er.status as ticket_status',
                'er.checked_in_at',
                'e.id as event_id',
                'e.title as event_title',
                'e.venue',
                'e.event_date',
                'e.end_date',
                'e.cover_image'
            )
            ->orderBy('e.event_date', 'desc')
            ->get()
            ->map(function ($pass) use ($user) {
                return [
                    'registration_id' => $pass->registration_id,
                    'pass_code'       => $pass->pass_code,
                    'event_id'        => $pass->event_id,
                    'event_title'     => $pass->event_title,
                    'venue'           => $pass->venue,
                    'event_date'      => $pass->event_date,
                    'end_date'        => $pass->end_date,
                    'cover_url'       => $pass->cover_image ? url($pass->cover_image) : null,
                    'payment_status'  => $pass->payment_status,
                    'ticket_status'   => $pass->ticket_status,
                    'is_checked_in'   => !empty($pass->checked_in_at),
                    'checked_in_at'   => $pass->checked_in_at,
                    'qr_payload'      => json_encode([
                        'action'    => 'event_gate_checkin',
                        'pass_code' => $pass->pass_code,
                        'event_id'  => $pass->event_id,
                        'user_id'   => $user->id,
                        'name'      => $user->name,
                    ]),
                ];
            });

        return $this->successResponse($passes, 'Smart passes retrieved successfully.');
    }
}
