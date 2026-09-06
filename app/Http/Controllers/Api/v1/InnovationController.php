<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api\v1;

use App\Models\AlumniProfile;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InnovationController extends BaseApiController
{
    /**
     * Gate Pass QR Scanner Verification & Instant Check-in
     * Used by event staff, gatekeepers, and admins
     */
    public function scanVerify(Request $request): JsonResponse
    {
        if (\Illuminate\Support\Facades\Schema::hasTable('event_registrations') && !\Illuminate\Support\Facades\Schema::hasColumn('event_registrations', 'checked_in_at')) {
            \Illuminate\Support\Facades\Schema::table('event_registrations', function ($table) {
                $table->timestamp('checked_in_at')->nullable()->after('notes');
                $table->unsignedInteger('checked_in_by')->nullable()->after('checked_in_at');
            });
        }

        $input = $this->getPayload($request);
        $code = trim((string)($input['code'] ?? ''));
        $eventId = $input['event_id'] ?? null;

        if ($code === '') {
            return $this->errorResponse('QR code payload or pass string is required.', 422, null, 'EMPTY_CODE');
        }

        // Try decoding JSON payload if scanned as JSON string
        $passCode = $code;
        $decoded = json_decode($code, true);
        if (is_array($decoded)) {
            $passCode = $decoded['pass_code'] ?? $decoded['member_no'] ?? $code;
            if (empty($eventId) && !empty($decoded['event_id'])) {
                $eventId = $decoded['event_id'];
            }
        }

        // 1. Check if code matches an Event Registration
        $query = DB::table('event_registrations as er')
            ->join('events as e', 'e.id', '=', 'er.event_id')
            ->join('users as u', 'u.id', '=', 'er.user_id')
            ->leftJoin('alumni_profiles as ap', 'ap.user_id', '=', 'u.id')
            ->where(function ($q) use ($passCode) {
                $q->where('er.pass_code', $passCode)
                  ->orWhere('er.id', is_numeric($passCode) ? (int)$passCode : -1);
            });

        if ($eventId) {
            $query->where('er.event_id', $eventId);
        }

        $reg = $query->select(
            'er.id as registration_id',
            'er.event_id',
            'er.user_id',
            'er.pass_code',
            'er.payment_status',
            'er.status as ticket_status',
            'er.checked_in_at',
            'er.checked_in_by',
            'e.title as event_title',
            'e.event_date',
            'e.venue',
            'u.name as user_name',
            'u.email as user_email',
            'u.avatar as user_avatar',
            'ap.avatar as profile_avatar',
            'ap.batch_year',
            'ap.blood_group',
            'ap.phone'
        )->first();

        if ($reg) {
            $isAlreadyCheckedIn = !empty($reg->checked_in_at);

            // If not yet checked in, perform instant check-in
            if (!$isAlreadyCheckedIn) {
                $checkerId = $request->user()?->id;
                DB::table('event_registrations')
                    ->where('id', $reg->registration_id)
                    ->update([
                        'checked_in_at' => now(),
                        'checked_in_by' => $checkerId,
                        'updated_at'    => now(),
                    ]);
                $checkedInAt = now()->toIso8601String();
                $statusMessage = 'Entry Approved! Attendance marked successfully.';
                $statusType = 'APPROVED';
            } else {
                $checkedInAt = $reg->checked_in_at;
                $statusMessage = 'Warning: Pass was ALREADY checked in on ' . date('d M Y, h:i A', strtotime($reg->checked_in_at)) . '.';
                $statusType = 'ALREADY_CHECKED_IN';
            }

            $avatar = $reg->user_avatar ? url($reg->user_avatar) : ($reg->profile_avatar ? url($reg->profile_avatar) : null);

            return $this->successResponse([
                'type'                => 'EVENT_PASS',
                'status'              => $statusType,
                'status_message'      => $statusMessage,
                'is_valid'            => true,
                'is_checked_in'       => true,
                'checked_in_at'       => $checkedInAt,
                'attendee'            => [
                    'name'        => $reg->user_name,
                    'email'       => $reg->user_email,
                    'batch_year'  => $reg->batch_year,
                    'blood_group' => $reg->blood_group,
                    'phone'       => $reg->phone,
                    'avatar_url'  => $avatar,
                ],
                'event'               => [
                    'event_id'    => $reg->event_id,
                    'title'       => $reg->event_title,
                    'venue'       => $reg->venue,
                    'event_date'  => $reg->event_date,
                    'payment'     => $reg->payment_status,
                ],
                'pass_code'           => $reg->pass_code,
            ], $statusMessage);
        }

        // 2. Check if code matches a Member ID Card
        $membership = DB::table('memberships as m')
            ->join('alumni_profiles as ap', 'ap.id', '=', 'm.alumni_profile_id')
            ->join('users as u', 'u.id', '=', 'ap.user_id')
            ->leftJoin('membership_types as mt', 'mt.id', '=', 'm.membership_type_id')
            ->where(function ($q) use ($passCode) {
                $q->where('m.membership_number', $passCode)
                  ->orWhere('m.qr_code', $passCode)
                  ->orWhere('ap.id', is_numeric($passCode) ? (int)$passCode : -1);
            })
            ->where('m.status', 'active')
            ->whereNull('m.deleted_at')
            ->select('m.*', 'u.name', 'u.email', 'u.avatar as user_avatar', 'ap.avatar as profile_avatar', 'ap.batch_year', 'ap.blood_group', 'ap.phone', 'mt.name as type_name')
            ->first();

        if ($membership) {
            $avatar = $membership->user_avatar ? url($membership->user_avatar) : ($membership->profile_avatar ? url($membership->profile_avatar) : null);

            return $this->successResponse([
                'type'             => 'MEMBERSHIP_CARD',
                'status'           => 'VALID_MEMBER',
                'status_message'   => 'Verified Official Alumni Association Member.',
                'is_valid'         => true,
                'member'           => [
                    'name'              => $membership->name,
                    'email'             => $membership->email,
                    'membership_number' => $membership->membership_number,
                    'membership_type'   => $membership->type_name ?? 'General Member',
                    'batch_year'        => $membership->batch_year,
                    'blood_group'       => $membership->blood_group,
                    'phone'             => $membership->phone,
                    'avatar_url'        => $avatar,
                    'valid_until'       => $membership->end_date ?? 'Lifetime',
                ],
            ], 'Verified Official Member.');
        }

        // 3. Fallback: Check if code matches a Registered Alumni Profile
        $extractedId = is_numeric($passCode) ? (int)$passCode : -1;
        if ($extractedId <= 0 && preg_match('/(?:IPH[-_]M[-_]|IPHAA[-_])(\d+)/i', $passCode, $mMatch)) {
            $extractedId = (int)$mMatch[1];
        }

        $profile = DB::table('alumni_profiles as ap')
            ->join('users as u', 'u.id', '=', 'ap.user_id')
            ->where(function ($q) use ($passCode, $extractedId) {
                $q->where('ap.id', $extractedId)
                  ->orWhere('ap.student_id', $passCode)
                  ->orWhere('u.email', $passCode);
            })
            ->whereIn('ap.status', ['approved', 'verified'])
            ->whereNull('u.deleted_at')
            ->whereNull('ap.deleted_at')
            ->select('ap.*', 'u.name', 'u.email', 'u.avatar as user_avatar')
            ->first();

        if ($profile) {
            $avatar = $profile->user_avatar ? url($profile->user_avatar) : ($profile->avatar ? url($profile->avatar) : null);

            return $this->successResponse([
                'type'             => 'ALUMNI_PROFILE_PASS',
                'status'           => 'REGISTERED_ALUMNI',
                'status_message'   => 'Registered Alumni Profile Verified.',
                'is_valid'         => true,
                'member'           => [
                    'name'              => $profile->name,
                    'email'             => $profile->email,
                    'membership_number' => 'IPH-M-' . str_pad((string)$profile->id, 5, '0', STR_PAD_LEFT),
                    'membership_type'   => 'Registered Alumni',
                    'batch_year'        => $profile->batch_year,
                    'blood_group'       => $profile->blood_group,
                    'phone'             => $profile->phone,
                    'avatar_url'        => $avatar,
                    'valid_until'       => 'Lifetime Registered',
                ],
            ], 'Registered Alumni Profile Verified.');
        }

        return $this->errorResponse('Invalid or unrecognized Pass / QR code.', 404, null, 'INVALID_PASS_CODE');
    }

    /**
     * Emergency Blood Donors Directory
     * Quick search of alumni willing to donate blood
     */
    public function bloodDonors(Request $request): JsonResponse
    {
        $group = trim((string)$request->input('blood_group', ''));
        $location = trim((string)$request->input('location', ''));
        $perPage = min(50, max(1, (int)$request->input('per_page', 20)));

        $query = DB::table('alumni_profiles as ap')
            ->join('users as u', 'u.id', '=', 'ap.user_id')
            ->whereIn('ap.status', ['approved', 'verified'])
            ->where('ap.is_public', 1)
            ->whereNotNull('ap.blood_group')
            ->where('ap.blood_group', '!=', '')
            ->whereNull('u.deleted_at')
            ->whereNull('ap.deleted_at');

        if ($group !== '') {
            $query->where('ap.blood_group', $group);
        }

        if ($location !== '') {
            $query->where(function ($q) use ($location) {
                $q->where('ap.current_location', 'like', "%{$location}%")
                  ->orWhere('ap.thana_upazila', 'like', "%{$location}%")
                  ->orWhere('ap.province_city', 'like', "%{$location}%");
            });
        }

        $paginator = $query->select(
            'ap.id as profile_id',
            'u.name',
            'ap.avatar',
            'ap.blood_group',
            'ap.phone',
            'ap.current_location',
            'ap.batch_year',
            'ap.specialization'
        )->orderBy('ap.blood_group', 'asc')
         ->paginate($perPage);

        $items = collect($paginator->items())->map(function ($donor) {
            return [
                'profile_id'       => $donor->profile_id,
                'name'             => $donor->name,
                'avatar_url'       => $donor->avatar ? url($donor->avatar) : null,
                'blood_group'      => $donor->blood_group,
                'phone'            => $donor->phone,
                'call_uri'         => $donor->phone ? ('tel:' . preg_replace('/[^0-9+]/', '', $donor->phone)) : null,
                'current_location' => $donor->current_location ?: 'Bangladesh',
                'batch_year'       => $donor->batch_year,
                'designation'      => $donor->specialization,
            ];
        });

        return $this->successResponse($items, 'Blood donors retrieved.', 200, [
            'current_page' => $paginator->currentPage(),
            'per_page'     => $paginator->perPage(),
            'total'        => $paginator->total(),
            'last_page'    => $paginator->lastPage(),
            'available_groups' => ['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'],
        ]);
    }

    /**
     * Mobile App Global Remote Configuration
     */
    public function appConfig(): JsonResponse
    {
        $settings = DB::table('settings')->pluck('value', 'key')->toArray();

        $latestNotice = DB::table('news')
            ->where('status', 'published')
            ->orderBy('published_at', 'desc')
            ->select('id', 'title', 'slug', 'published_at')
            ->first();

        return $this->successResponse([
            'app_info' => [
                'name'                   => 'IPH Alumni Association',
                'current_version'        => $settings['app_version_name'] ?? '1.0.0',
                'min_supported_version'  => '1.0.0',
                'force_update'           => false,
                'play_store_url'         => !empty($settings['app_google_play_url']) ? $settings['app_google_play_url'] : null,
                'app_store_url'          => !empty($settings['app_apple_store_url']) ? $settings['app_apple_store_url'] : null,
                'apk_url'                => !empty($settings['app_apk_url']) ? $settings['app_apk_url'] : null,
            ],
            'theme' => [
                'primary_color'   => '#d4af37', // Gold
                'secondary_color' => '#10b981', // Emerald
                'dark_bg'         => '#0d1117',
                'surface_bg'      => '#161b22',
            ],
            'broadcast_banner' => $latestNotice ? [
                'enabled'      => true,
                'title'        => $latestNotice->title,
                'slug'         => $latestNotice->slug,
                'published_at' => $latestNotice->published_at,
            ] : null,
            'contact' => [
                'support_email'    => $settings['contact_email'] ?? 'support@iphalumni.org',
                'support_phone'    => $settings['contact_phone'] ?? '+880 1700-000000',
                'emergency_hotline'=> '+880 1800-000000',
                'address'          => $settings['address'] ?? 'Institute of Public Health (IPH), Mohakhali, Dhaka-1212, Bangladesh',
                'facebook_group'   => 'https://facebook.com/groups/iphalumni',
                'website'          => url('/'),
            ],
            'features_enabled' => [
                'digital_id_card' => true,
                'smart_gate_pass' => true,
                'blood_donors'    => true,
                'job_board'       => true,
                'notices'         => true,
                'events'          => true,
            ],
        ], 'App configuration loaded.');
    }
}
