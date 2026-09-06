<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api\v1;

use App\Models\ApiToken;
use App\Models\User;
use App\Models\AlumniProfile;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends BaseApiController
{
    /**
     * Mobile App Login
     */
    public function login(Request $request): JsonResponse
    {
        $input = $this->getPayload($request);

        $validator = Validator::make($input, [
            'email'       => 'required|email',
            'password'    => 'required|string',
            'device_name' => 'nullable|string|max:100',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validation failed', 422, $validator->errors(), 'VALIDATION_FAILED');
        }

        $user = User::where('email', $input['email'] ?? '')->first();

        if (!$user || !Hash::check($request->input('password'), $user->password)) {
            return $this->errorResponse('Invalid email or password credentials.', 401, null, 'INVALID_CREDENTIALS');
        }

        if ($user->status !== 'active') {
            return $this->errorResponse('Your account is currently ' . ($user->status ?: 'pending approval') . '.', 403, null, 'ACCOUNT_INACTIVE');
        }

        // Update login metadata
        $user->forceFill([
            'last_login_at' => now(),
            'last_login_ip' => $request->ip(),
        ])->saveQuietly();

        // Create API Token
        $tokenData = ApiToken::createToken(
            $user,
            'mobile_app',
            $request->input('device_name', 'Mobile Client'),
            120 // 120 days validity
        );

        $profile = AlumniProfile::where('user_id', $user->id)->first();
        $membership = null;
        if ($profile) {
            $membership = DB::table('memberships as m')
                ->leftJoin('membership_types as mt', 'mt.id', '=', 'm.membership_type_id')
                ->where('m.alumni_profile_id', $profile->id)
                ->where('m.status', 'active')
                ->select('m.id', 'm.membership_number', 'mt.name as membership_type', 'm.start_date', 'm.end_date')
                ->first();
        }

        return $this->successResponse([
            'token_type'   => 'Bearer',
            'access_token' => $tokenData['plain_token'],
            'expires_at'   => $tokenData['expires_at'],
            'user'         => [
                'id'         => $user->id,
                'name'       => $user->name,
                'email'      => $user->email,
                'role'       => $user->role,
                'avatar_url' => $user->avatar ? url($user->avatar) : ($profile?->avatar ? url($profile->avatar) : null),
            ],
            'profile'      => $profile ? [
                'profile_id'   => $profile->id,
                'batch_year'   => $profile->batch_year,
                'phone'        => $profile->phone,
                'blood_group'  => $profile->blood_group,
                'designation'  => $profile->specialization,
                'location'     => $profile->current_location,
            ] : null,
            'membership'   => $membership,
        ], 'Login successful.');
    }

    /**
     * Mobile App Registration
     */
    public function register(Request $request): JsonResponse
    {
        $input = $this->getPayload($request);

        $validator = Validator::make($input, [
            'name'        => 'required|string|max:190',
            'email'       => 'required|email|max:190|unique:users,email',
            'password'    => 'required|string|min:6',
            'batch_year'  => 'nullable|string|max:50',
            'phone'       => 'nullable|string|max:30',
            'blood_group' => 'nullable|string|max:10',
            'device_name' => 'nullable|string|max:100',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Registration validation failed', 422, $validator->errors(), 'VALIDATION_FAILED');
        }

        DB::beginTransaction();
        try {
            $user = User::create([
                'name'     => $input['name'],
                'email'    => $input['email'],
                'password' => Hash::make($input['password']),
                'role'     => 'alumni',
                'status'   => 'active',
            ]);

            $profile = AlumniProfile::create([
                'user_id'     => $user->id,
                'batch_year'  => $input['batch_year'] ?? null,
                'phone'       => $input['phone'] ?? null,
                'blood_group' => $input['blood_group'] ?? null,
                'status'      => 'approved',
                'is_public'   => 1,
            ]);

            $tokenData = ApiToken::createToken($user, 'mobile_app', $input['device_name'] ?? 'Mobile App', 120);

            DB::commit();

            return $this->successResponse([
                'token_type'   => 'Bearer',
                'access_token' => $tokenData['plain_token'],
                'expires_at'   => $tokenData['expires_at'],
                'user'         => [
                    'id'    => $user->id,
                    'name'  => $user->name,
                    'email' => $user->email,
                    'role'  => $user->role,
                ],
                'profile'      => [
                    'profile_id'  => $profile->id,
                    'batch_year'  => $profile->batch_year,
                    'phone'       => $profile->phone,
                    'blood_group' => $profile->blood_group,
                ],
            ], 'Registration completed successfully.', 201);
        } catch (\Throwable $e) {
            DB::rollBack();
            return $this->errorResponse('Failed to create account: ' . $e->getMessage(), 500, null, 'REGISTRATION_ERROR');
        }
    }

    /**
     * Current Authenticated User Details
     */
    public function me(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        $profile = AlumniProfile::where('user_id', $user->id)->first();
        $membership = null;
        if ($profile) {
            $membership = DB::table('memberships as m')
                ->leftJoin('membership_types as mt', 'mt.id', '=', 'm.membership_type_id')
                ->where('m.alumni_profile_id', $profile->id)
                ->where('m.status', 'active')
                ->select('m.id', 'm.membership_number', 'mt.name as membership_type', 'm.start_date', 'm.end_date')
                ->first();
        }

        return $this->successResponse([
            'user'       => [
                'id'              => $user->id,
                'name'            => $user->name,
                'email'           => $user->email,
                'secondary_email' => $user->secondary_email,
                'role'            => $user->role,
                'avatar_url'      => $user->avatar ? url($user->avatar) : ($profile?->avatar ? url($profile->avatar) : null),
                'status'          => $user->status,
                'created_at'      => $user->created_at?->toIso8601String(),
            ],
            'profile'    => $profile,
            'membership' => $membership,
        ], 'Profile retrieved successfully.');
    }

    /**
     * Mobile App Logout
     */
    public function logout(Request $request): JsonResponse
    {
        $tokenRecord = $request->attributes->get('api_token_record');

        if ($tokenRecord instanceof ApiToken) {
            $tokenRecord->delete();
        } else {
            // Revoke all tokens for this user
            ApiToken::where('user_id', $request->user()?->id)->delete();
        }

        return $this->successResponse(null, 'Successfully logged out and session revoked.');
    }
}
