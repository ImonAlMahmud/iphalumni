<?php
declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Models\ApiToken;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MobileApiController extends BaseController
{
    /**
     * Display the Mobile API Documentation & Testing Hub
     */
    public function index(Request $request)
    {
        $tokenCount = DB::table('api_tokens')->count();
        $recentTokens = DB::table('api_tokens as at')
            ->join('users as u', 'u.id', '=', 'at.user_id')
            ->select('at.*', 'u.name as user_name', 'u.email as user_email')
            ->orderBy('at.created_at', 'desc')
            ->limit(10)
            ->get();

        // Check if current admin already has an active token
        $currentUserId = Auth::id() ?? 1;
        $currentUser = User::find($currentUserId);
        $adminToken = DB::table('api_tokens')
            ->where('user_id', $currentUserId)
            ->where('name', 'admin_hub_tester')
            ->first();

        $tokenString = null;
        if (!$adminToken && $currentUser) {
            $created = ApiToken::createToken($currentUser, 'admin_hub_tester', 'Admin Console Test', 180);
            $tokenString = $created['plain_token'];
        }

        // Detailed API Registry
        $endpoints = [
            [
                'group'       => 'Authentication',
                'title'       => 'Mobile App Login',
                'method'      => 'POST',
                'path'        => '/api/v1/auth/login',
                'auth'        => false,
                'description' => 'Authenticates an alumni by email and password. Returns Bearer token, user object, and profile summary.',
                'headers'     => ['Content-Type' => 'application/json', 'Accept' => 'application/json'],
                'request_body'=> [
                    'email'       => 'alumni@iphalumni.org',
                    'password'    => 'password123',
                    'device_name' => 'Pixel 8 Pro (Android 14)',
                ],
                'response'    => [
                    'success' => true,
                    'message' => 'Login successful.',
                    'data'    => [
                        'token_type'   => 'Bearer',
                        'access_token' => '7e9c5f82a1d4... (64 hex characters)',
                        'expires_at'   => '2026-12-05T18:00:00+06:00',
                        'user'         => ['id' => 12, 'name' => 'Dr. Rafiqul Islam', 'email' => 'alumni@iphalumni.org', 'role' => 'alumni'],
                        'profile'      => ['batch_year' => '2015', 'blood_group' => 'B+', 'designation' => 'Senior Health Officer'],
                    ],
                ],
            ],
            [
                'group'       => 'Authentication',
                'title'       => 'Member Registration',
                'method'      => 'POST',
                'path'        => '/api/v1/auth/register',
                'auth'        => false,
                'description' => 'Registers a new alumni profile and creates a direct login session with Bearer token.',
                'headers'     => ['Content-Type' => 'application/json', 'Accept' => 'application/json'],
                'request_body'=> [
                    'name'        => 'Farhana Yeasmin',
                    'email'       => 'farhana@example.com',
                    'password'    => 'secretPassword@123',
                    'batch_year'  => '2019',
                    'phone'       => '01711223344',
                    'blood_group' => 'O+',
                ],
                'response'    => [
                    'success' => true,
                    'message' => 'Registration completed successfully.',
                    'data'    => ['token_type' => 'Bearer', 'access_token' => '...', 'user' => ['name' => 'Farhana Yeasmin']],
                ],
            ],
            [
                'group'       => 'Authentication',
                'title'       => 'Get Current Authenticated User (Me)',
                'method'      => 'GET',
                'path'        => '/api/v1/auth/me',
                'auth'        => true,
                'description' => 'Fetches profile, current active membership, role, and verification status for current token.',
                'headers'     => ['Authorization' => 'Bearer <YOUR_TOKEN>', 'Accept' => 'application/json'],
                'request_body'=> null,
                'response'    => [
                    'success' => true,
                    'message' => 'Profile retrieved successfully.',
                    'data'    => ['user' => ['name' => 'Dr. Rafiqul Islam', 'role' => 'alumni'], 'profile' => ['blood_group' => 'B+']],
                ],
            ],
            [
                'group'       => 'Authentication',
                'title'       => 'Logout & Invalidate Token',
                'method'      => 'POST',
                'path'        => '/api/v1/auth/logout',
                'auth'        => true,
                'description' => 'Revokes and deletes the current mobile device API token from database.',
                'headers'     => ['Authorization' => 'Bearer <YOUR_TOKEN>', 'Accept' => 'application/json'],
                'request_body'=> null,
                'response'    => [
                    'success' => true,
                    'message' => 'Successfully logged out and session revoked.',
                ],
            ],
            [
                'group'       => 'Member & Digital Pass',
                'title'       => 'Full Member Profile',
                'method'      => 'GET',
                'path'        => '/api/v1/member/profile',
                'auth'        => true,
                'description' => 'Retrieves complete profile including education milestones, work experiences, and active membership subscription.',
                'headers'     => ['Authorization' => 'Bearer <YOUR_TOKEN>', 'Accept' => 'application/json'],
                'request_body'=> null,
                'response'    => [
                    'success' => true,
                    'data'    => ['user' => [], 'profile' => [], 'education' => [], 'employment' => [], 'membership' => []],
                ],
            ],
            [
                'group'       => 'Member & Digital Pass',
                'title'       => 'Digital ID Card Dataset',
                'method'      => 'GET',
                'path'        => '/api/v1/member/id-card',
                'auth'        => true,
                'description' => 'Supplies all cryptographic and visual fields needed to render the IPH Alumni Association Membership Card on mobile screen with QR code.',
                'headers'     => ['Authorization' => 'Bearer <YOUR_TOKEN>', 'Accept' => 'application/json'],
                'request_body'=> null,
                'response'    => [
                    'success' => true,
                    'data'    => [
                        'card_title'       => 'IPH ALUMNI ASSOCIATION MEMBERSHIP CARD',
                        'member_name'      => 'Dr. Rafiqul Islam',
                        'membership_no'    => 'IPH-M-00002',
                        'batch_year'       => '2015',
                        'blood_group'      => 'B+',
                        'membership_type'  => 'Lifetime Member',
                        'is_active_member' => true,
                        'valid_until'      => 'Lifetime / Active',
                        'qr_payload'       => '{"type":"iph_membership_pass","member_no":"IPH-M-00002"...}',
                        'verification_url' => 'https://iphalumni.dev.cv/directory/2',
                    ],
                ],
            ],
            [
                'group'       => 'Member & Digital Pass',
                'title'       => 'Event Smart Passes (Gate Entry)',
                'method'      => 'GET',
                'path'        => '/api/v1/member/smart-pass',
                'auth'        => true,
                'description' => 'Returns list of upcoming registered event tickets with pass_code and gate check-in status.',
                'headers'     => ['Authorization' => 'Bearer <YOUR_TOKEN>', 'Accept' => 'application/json'],
                'request_body'=> null,
                'response'    => [
                    'success' => true,
                    'data'    => [
                        ['registration_id' => 10, 'pass_code' => 'PASS-2026-9812', 'event_title' => 'Annual Grand Reunion 2026', 'venue' => 'IPH Auditorium', 'is_checked_in' => false],
                    ],
                ],
            ],
            [
                'group'       => 'Directory',
                'title'       => 'Search Alumni Directory',
                'method'      => 'GET',
                'path'        => '/api/v1/directory?search=&batch=&blood_group=&location=&has_membership=1&page=1',
                'auth'        => false,
                'description' => 'Live searchable directory with filters for batch, blood group, city/location, and premium member flag.',
                'headers'     => ['Accept' => 'application/json'],
                'request_body'=> null,
                'response'    => [
                    'success' => true,
                    'data'    => [
                        ['profile_id' => 2, 'name' => 'Dr. Rafiqul Islam', 'batch_year' => '2015', 'blood_group' => 'B+', 'is_premium_member' => true],
                    ],
                    'meta'    => ['current_page' => 1, 'per_page' => 15, 'total' => 120, 'last_page' => 8],
                ],
            ],
            [
                'group'       => 'Directory',
                'title'       => 'Public Alumni Profile Details',
                'method'      => 'GET',
                'path'        => '/api/v1/directory/{id}',
                'auth'        => false,
                'description' => 'Fetches verified public profile information, academic records, and career timeline.',
                'headers'     => ['Accept' => 'application/json'],
                'request_body'=> null,
                'response'    => [
                    'success' => true,
                    'data'    => ['profile_id' => 2, 'name' => '...', 'is_premium_member' => true, 'education' => [], 'employment' => []],
                ],
            ],
            [
                'group'       => 'Notices & Circulars',
                'title'       => 'Published Notices & Bulletins',
                'method'      => 'GET',
                'path'        => '/api/v1/notices?page=1&search=',
                'auth'        => false,
                'description' => 'Official circulars with reference numbers (IPH-AA/NOT/YYYY/0000) and excerpts.',
                'headers'     => ['Accept' => 'application/json'],
                'request_body'=> null,
                'response'    => [
                    'success' => true,
                    'data'    => [
                        ['id' => 5, 'ref_no' => 'IPH-AA/NOT/2026/0005', 'title' => 'Reunion Registration Notice', 'published_at' => '2026-09-01'],
                    ],
                ],
            ],
            [
                'group'       => 'Notices & Circulars',
                'title'       => 'Notice Details & Signatories Verification',
                'method'      => 'GET',
                'path'        => '/api/v1/notices/{idOrRef}',
                'auth'        => false,
                'description' => 'Complete notice article with formatted reference number, committee signatories, and digital signatures.',
                'headers'     => ['Accept' => 'application/json'],
                'request_body'=> null,
                'response'    => [
                    'success' => true,
                    'data'    => [
                        'id'           => 5,
                        'ref_no'       => 'IPH-AA/NOT/2026/0005',
                        'title'        => 'Notice',
                        'signatories'  => [['name' => 'President Name', 'designation' => 'President', 'signature_image' => '...']],
                    ],
                ],
            ],
            [
                'group'       => 'Events & Reunion',
                'title'       => 'Events List (Upcoming / Past)',
                'method'      => 'GET',
                'path'        => '/api/v1/events?type=upcoming',
                'auth'        => false,
                'description' => 'All published association events with dates, venue, ticket price, and registration counts.',
                'headers'     => ['Accept' => 'application/json'],
                'request_body'=> null,
                'response'    => [
                    'success' => true,
                    'data'    => [['id' => 3, 'title' => 'Reunion 2026', 'venue' => 'Dhaka', 'ticket_fee' => 1500]],
                ],
            ],
            [
                'group'       => 'Events & Reunion',
                'title'       => 'Event Single Details',
                'method'      => 'GET',
                'path'        => '/api/v1/events/{id}',
                'auth'        => false,
                'description' => 'Full event overview, itinerary schedule, crowdfunding target, and registration links.',
                'headers'     => ['Accept' => 'application/json'],
                'request_body'=> null,
                'response'    => [
                    'success' => true,
                    'data'    => ['id' => 3, 'title' => 'Reunion 2026', 'venue' => '...', 'total_registered' => 45],
                ],
            ],
            [
                'group'       => 'Innovative Mobile Features',
                'title'       => 'Gate Pass QR Scanner & Attendance Check-in',
                'method'      => 'POST',
                'path'        => '/api/v1/verify/scan',
                'auth'        => true,
                'description' => 'Camera QR code scanner endpoint for reunion entrance gates. Instantly validates pass code or member card and marks real-time attendance.',
                'headers'     => ['Authorization' => 'Bearer <YOUR_TOKEN>', 'Content-Type' => 'application/json', 'Accept' => 'application/json'],
                'request_body'=> [
                    'code'     => 'PASS-2026-9812',
                    'event_id' => 3,
                ],
                'response'    => [
                    'success' => true,
                    'message' => 'Entry Approved! Attendance marked successfully.',
                    'data'    => [
                        'type'          => 'EVENT_PASS',
                        'status'        => 'APPROVED',
                        'is_valid'      => true,
                        'is_checked_in' => true,
                        'checked_in_at' => '2026-09-06T18:30:00+06:00',
                        'attendee'      => ['name' => 'Dr. Rafiqul Islam', 'batch_year' => '2015', 'blood_group' => 'B+'],
                    ],
                ],
            ],
            [
                'group'       => 'Innovative Mobile Features',
                'title'       => 'Emergency Blood Donors Locator',
                'method'      => 'GET',
                'path'        => '/api/v1/blood-donors?blood_group=O+&location=Dhaka',
                'auth'        => false,
                'description' => 'Emergency feature allowing alumni to quickly find nearby blood donors with 1-tap direct call phone links.',
                'headers'     => ['Accept' => 'application/json'],
                'request_body'=> null,
                'response'    => [
                    'success' => true,
                    'data'    => [
                        ['name' => 'Alumni Donor', 'blood_group' => 'O+', 'phone' => '01700000000', 'call_uri' => 'tel:01700000000', 'current_location' => 'Mohakhali, Dhaka'],
                    ],
                ],
            ],
            [
                'group'       => 'Innovative Mobile Features',
                'title'       => 'Remote App Configuration & Branding',
                'method'      => 'GET',
                'path'        => '/api/v1/config',
                'auth'        => false,
                'description' => 'Remote configuration for the mobile client. Handles version gating, emergency announcement bar, hotlines, and color theme tokens.',
                'headers'     => ['Accept' => 'application/json'],
                'request_body'=> null,
                'response'    => [
                    'success' => true,
                    'data'    => [
                        'app_info'         => ['current_version' => '1.0.0', 'force_update' => false],
                        'theme'            => ['primary_color' => '#d4af37', 'dark_bg' => '#0d1117'],
                        'broadcast_banner' => ['enabled' => true, 'title' => 'Reunion 2026 Registration Open!'],
                        'contact'          => ['support_email' => 'support@iphalumni.org', 'emergency_hotline' => '+880 1800-000000'],
                    ],
                ],
            ],
        ];

        $stats = [
            'total_endpoints' => count($endpoints),
            'auth_endpoints'  => count(array_filter($endpoints, fn($e) => $e['auth'])),
            'public_endpoints'=> count(array_filter($endpoints, fn($e) => !$e['auth'])),
            'active_tokens'   => $tokenCount,
        ];

        return $this->legacyView(
            'admin/api/index',
            compact('endpoints', 'stats', 'recentTokens', 'tokenString'),
            'admin',
            'Mobile App & REST API Hub'
        );
    }

    /**
     * Generate Admin Testing Token via AJAX
     */
    public function generateToken(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = Auth::user() ?? User::first();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'No administrative user found.'], 404);
        }

        $tokenData = ApiToken::createToken($user, 'admin_developer_console', 'Admin Portal Web Tester', 90);

        return response()->json([
            'success'      => true,
            'message'      => 'New Bearer Token created successfully.',
            'access_token' => $tokenData['plain_token'],
            'expires_at'   => $tokenData['expires_at'],
            'user'         => $user->name . ' (' . $user->email . ')',
        ]);
    }

    /**
     * Revoke Token
     */
    public function revokeToken(Request $request): JsonResponse
    {
        $id = $request->input('token_id');
        if ($id) {
            DB::table('api_tokens')->where('id', $id)->delete();
            return response()->json(['success' => true, 'message' => 'Token revoked successfully.']);
        }

        return response()->json(['success' => false, 'message' => 'Token ID required.'], 400);
    }
}
