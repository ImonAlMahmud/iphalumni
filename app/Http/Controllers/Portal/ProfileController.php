<?php
declare(strict_types=1);

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\BaseController;
use App\Models\AlumniProfile;
use App\Services\MailService;
use App\Services\UploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ProfileController extends BaseController
{
    public function index(Request $request)
    {
        $user    = Auth::user();
        $model   = new AlumniProfile();
        $profile = $model->getByUserId((int)$user->id);
        $education  = $profile ? $model->getEducation((int)$profile['id']) : [];
        $employment = $profile ? $model->getEmployment((int)$profile['id']) : [];

        $primaryEdu = !empty($education) ? (current(array_filter($education, fn($e) => !empty($e['is_primary']))) ?: $education[0]) : null;
        $currentEmp = !empty($employment) ? (current(array_filter($employment, fn($e) => !empty($e['is_current']))) ?: $employment[0]) : null;

        $allUniversities = DB::table('universities')->select('country', 'name')->orderBy('country', 'asc')->orderBy('name', 'asc')->get()->map(fn($r) => (array)$r)->toArray();

        return $this->legacyView(
            'portal/profile',
            compact('user', 'profile', 'education', 'employment', 'primaryEdu', 'currentEmp', 'allUniversities'),
            'portal',
            'My Profile'
        );
    }

    public function update(Request $request)
    {
        $user    = Auth::user();
        $model   = new AlumniProfile();
        $profile = $model->getByUserId((int)$user->id);
        if (!$profile) {
            return redirect('/portal/profile')->with('error', 'Profile not found.');
        }

        $locationType = $request->input('location_type', 'bangladesh');
        $countryName  = trim((string)$request->input('country', ''));

        $newEmail     = strtolower(trim((string)$request->input('email', '')));
        $isEmailChange = !empty($newEmail) && $newEmail !== strtolower((string)$user->email);

        if ($isEmailChange) {
            if (!filter_var($newEmail, FILTER_VALIDATE_EMAIL)) {
                return redirect('/portal/profile')->with('error', 'অনুগ্রহ করে একটি সঠিক ইমেইল ঠিকানা প্রদান করুন।');
            }
            $existing = DB::table('users')->where('email', $newEmail)->where('id', '!=', $user->id)->first();
            if ($existing) {
                return redirect('/portal/profile')->with('error', 'এই ইমেইল ঠিকানাটি ইতোমধ্যে অন্য একজন সদস্যের অ্যাকাউন্টে ব্যবহৃত হচ্ছে। অনুগ্রহ করে ভিন্ন ইমেইল ব্যবহার করুন।');
            }

            // Require OTP session verification before applying email change
            $sessionKey  = 'email_change_verified_' . $user->id;
            $sessionData = $request->session()->get($sessionKey);

            $otpVerified = $sessionData
                && isset($sessionData['email'], $sessionData['expires_at'])
                && $sessionData['email'] === $newEmail
                && now()->timestamp < $sessionData['expires_at'];

            if (!$otpVerified) {
                return redirect('/portal/profile')
                    ->with('error', 'ইমেইল পরিবর্তন করতে হলে প্রথমে নতুন ইমেইলে পাঠানো OTP কোডটি দিয়ে যাচাই করুন।')
                    ->withInput();
            }

            // OTP verified — clear the session flag
            $request->session()->forget($sessionKey);
        }

        $dobInput   = trim((string)$request->input('dob', ''));
        $dob        = (!empty($dobInput) && strtotime($dobInput)) ? date('Y-m-d', strtotime($dobInput)) : null;
        $gender     = trim((string)$request->input('gender', '')) ?: null;
        $bloodGroup = trim((string)$request->input('blood_group', '')) ?: null;
        $batchYear  = trim((string)$request->input('batch_year', '')) ?: null;

            // Secondary email validation
            $secondaryEmail = strtolower(trim((string)$request->input('secondary_email', '')));
            if (!empty($secondaryEmail)) {
                if (!filter_var($secondaryEmail, FILTER_VALIDATE_EMAIL)) {
                    return redirect('/portal/profile')->with('error', 'অনুগ্রহ করে একটি সঠিক সেকেন্ডারি (বিকল্প) ইমেইল ঠিকানা প্রদান করুন।')->withInput();
                }
                $currentPrimary = $isEmailChange ? $newEmail : strtolower((string)$user->email);
                if ($secondaryEmail === $currentPrimary) {
                    return redirect('/portal/profile')->with('error', 'সেকেন্ডারি ইমেইল এবং প্রাইমারি ইমেইল একই হতে পারবে না। অনুগ্রহ করে ভিন্ন ইমেইল ব্যবহার করুন।')->withInput();
                }
            } else {
                $secondaryEmail = null;
            }

        try {
            DB::table('alumni_profiles')->where('id', (int)$profile['id'])->update([
                'phone'            => trim((string)$request->input('phone', '')),
                'secondary_email'  => $secondaryEmail,
                'nid_number'       => trim((string)$request->input('nid_number', '')),
                'dob'              => $dob,
                'gender'           => $gender,
                'blood_group'      => $bloodGroup,
                'batch_year'       => $batchYear,
                'bio'              => trim((string)$request->input('bio', '')),
                'location_type'    => $locationType,
                'current_location' => trim((string)$request->input('current_location', '')),
                'thana_upazila'    => trim((string)$request->input('thana_upazila', '')),
                'country'          => $countryName,
                'province_city'    => trim((string)$request->input('province_city', '')),
                'activity_type'    => $request->input('activity_type', 'work'),
                'website'          => trim((string)$request->input('website', '')),
                'linkedin_url'     => trim((string)$request->input('linkedin_url', '')),
                'facebook_url'     => trim((string)$request->input('facebook_url', '')),
                'session_years'    => trim((string)$request->input('session_years', '')),
                'specialization'   => trim((string)$request->input('specialization', '')),
                'skills'           => trim((string)$request->input('skills', '')),
                'experience_years' => trim((string)$request->input('experience_years', '')),
                'willing_to_mentor'=> (int)$request->input('willing_to_mentor', 0),
                'job_referral'     => (int)$request->input('job_referral', 0),
                'contribution_areas' => trim((string)$request->input('contribution_areas', '')),
                'google_scholar_url' => trim((string)$request->input('google_scholar_url', '')),
                'researchgate_url' => trim((string)$request->input('researchgate_url', '')),
                'permanent_location' => trim((string)$request->input('permanent_location', '')),
                'permanent_district' => trim((string)$request->input('permanent_district', '')),
                'permanent_upazila'  => trim((string)$request->input('permanent_upazila', '')),
                'emergency_contact_name'  => trim((string)$request->input('emergency_contact_name', '')),
                'emergency_contact_phone' => trim((string)$request->input('emergency_contact_phone', '')),
                'publications'        => trim((string)$request->input('publications', '')),
                'awards_recognition'  => trim((string)$request->input('awards_recognition', '')),
                'updated_at'          => now(),
            ]);

            // Update name and email in users table
            $userUpdates = [
                'secondary_email' => $secondaryEmail,
            ];
            if ($request->filled('name')) {
                $userUpdates['name'] = trim((string)$request->input('name'));
            }
            if ($isEmailChange) {
                $userUpdates['email'] = $newEmail;
            }
            $userUpdates['updated_at'] = now();
            DB::table('users')->where('id', $user->id)->update($userUpdates);

            // Save / Update Education (Study Info)
            $university = trim((string)$request->input('university', ''));
            $programme  = trim((string)$request->input('programme', ''));
            $subject    = trim((string)$request->input('subject', ''));

            if (!empty($university)) {
                $targetCountry = ($locationType === 'abroad' && !empty($countryName)) ? $countryName : 'Bangladesh';
                $checkUniv = DB::table('universities')->whereRaw('LOWER(country) = LOWER(?)', [$targetCountry])->whereRaw('LOWER(name) = LOWER(?)', [$university])->count();
                if ($checkUniv === 0) {
                    DB::table('universities')->insert([
                        'country'    => $targetCountry,
                        'name'       => $university,
                        'created_by' => $user->id,
                        'created_at' => now(),
                    ]);
                }
            }

            if (!empty($university) || !empty($programme) || !empty($subject)) {
                $eduId = DB::table('alumni_education')->where('alumni_profile_id', $profile['id'])->where('is_primary', 1)->value('id');

                if ($eduId) {
                    DB::table('alumni_education')->where('id', $eduId)->update([
                        'degree'         => $programme,
                        'institution'    => $university,
                        'field_of_study' => $subject,
                        'updated_at'     => now(),
                    ]);
                } else {
                    DB::table('alumni_education')->insert([
                        'alumni_profile_id' => $profile['id'],
                        'degree'            => $programme,
                        'institution'       => $university,
                        'field_of_study'    => $subject,
                        'is_primary'        => 1,
                        'created_at'        => now(),
                        'updated_at'        => now(),
                    ]);
                }
            }

            // Save / Update Employment (Work Info)
            $designation  = trim((string)$request->input('designation', ''));
            $organization = trim((string)$request->input('organization', ''));
            $department   = trim((string)$request->input('department', ''));

            if (!empty($designation) || !empty($organization) || !empty($department)) {
                $empId = DB::table('alumni_employment')->where('alumni_profile_id', $profile['id'])->where('is_current', 1)->value('id');

                if ($empId) {
                    DB::table('alumni_employment')->where('id', $empId)->update([
                        'job_title'    => $designation,
                        'organization' => $organization,
                        'department'   => $department,
                        'updated_at'   => now(),
                    ]);
                } else {
                    DB::table('alumni_employment')->insert([
                        'alumni_profile_id' => $profile['id'],
                        'job_title'         => $designation,
                        'organization'      => $organization,
                        'department'        => $department,
                        'is_current'        => 1,
                        'created_at'        => now(),
                        'updated_at'        => now(),
                    ]);
                }
            }

            return redirect('/portal/profile')->with('success', 'প্রোফাইল সফলভাবে আপডেট করা হয়েছে।');
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Profile update failed: ' . $e->getMessage(), ['exception' => $e]);
            return redirect('/portal/profile')->with('error', 'প্রোফাইল সংরক্ষণ করার সময় একটি সমস্যা হয়েছে। অনুগ্রহ করে আবার চেষ্টা করুন।');
        }
    }

    /**
     * Step 1: Send OTP to the new email before allowing the email change.
     * Called via AJAX from the profile page.
     */
    public function sendEmailChangeOtp(Request $request): \Illuminate\Http\JsonResponse
    {
        $user     = Auth::user();
        $newEmail = strtolower(trim((string)$request->input('email', '')));

        if (empty($newEmail) || !filter_var($newEmail, FILTER_VALIDATE_EMAIL)) {
            return response()->json(['success' => false, 'message' => 'সঠিক ইমেইল ঠিকানা দিন।']);
        }
        if ($newEmail === strtolower((string)$user->email)) {
            return response()->json(['success' => false, 'message' => 'এটি আপনার বর্তমান ইমেইল।']);
        }
        if (DB::table('users')->where('email', $newEmail)->where('id', '!=', $user->id)->exists()) {
            return response()->json(['success' => false, 'message' => 'এই ইমেইলটি অন্য অ্যাকাউন্টে ব্যবহৃত হচ্ছে।']);
        }

        // Generate 6-digit OTP
        $otp = (string)random_int(100000, 999999);

        // Store pending OTP in session with 10-minute expiry
        $request->session()->put('email_change_otp_' . $user->id, [
            'email'      => $newEmail,
            'otp'        => $otp,
            'expires_at' => now()->addMinutes(10)->timestamp,
        ]);

        $result = MailService::send($newEmail, 'Email Change Verification — IPH Alumni', [
            'title'   => 'ইমেইল পরিবর্তনের OTP',
            'badge'   => 'EMAIL CHANGE OTP',
            'content' => "<p>আপনার IPH Alumni পোর্টাল অ্যাকাউন্টের ইমেইল পরিবর্তনের অনুরোধ করা হয়েছে।</p>
                <div style='text-align:center;margin:24px 0;'>
                    <span style='display:inline-block;padding:12px 32px;background:#800020;color:#fff;font-size:26px;font-weight:bold;letter-spacing:6px;border-radius:12px;'>{$otp}</span>
                </div>
                <p style='font-size:13px;color:#64748b;'>এই কোডটির মেয়াদ ১০ মিনিট। আপনি যদি এই পরিবর্তনের অনুরোধ না করে থাকেন, নিরাপত্তার জন্য পাসওয়ার্ড পরিবর্তন করুন।</p>",
        ]);

        return response()->json([
            'success' => (bool)($result['success'] ?? false),
            'message' => ($result['success'] ?? false)
                ? 'নতুন ইমেইলে OTP পাঠানো হয়েছে।'
                : 'OTP পাঠাতে সমস্যা হয়েছে। SMTP সেটিংস যাচাই করুন।',
        ]);
    }

    /**
     * Step 2: Validate OTP and mark email change as verified in session.
     * Called via AJAX from the profile page.
     */
    public function verifyEmailChangeOtp(Request $request): \Illuminate\Http\JsonResponse
    {
        $user     = Auth::user();
        $newEmail = strtolower(trim((string)$request->input('email', '')));
        $code     = trim((string)$request->input('otp', ''));

        $sessionKey  = 'email_change_otp_' . $user->id;
        $sessionData = $request->session()->get($sessionKey);

        if (
            !$sessionData ||
            !isset($sessionData['email'], $sessionData['otp'], $sessionData['expires_at']) ||
            $sessionData['email'] !== $newEmail ||
            now()->timestamp > $sessionData['expires_at']
        ) {
            return response()->json(['success' => false, 'message' => 'OTP মেয়াদ শেষ হয়েছে বা তথ্য মেলেনি। আবার চেষ্টা করুন।']);
        }

        // Timing-safe comparison to prevent timing attacks
        if (!hash_equals($sessionData['otp'], $code)) {
            return response()->json(['success' => false, 'message' => 'OTP কোডটি সঠিক নয়।']);
        }

        // Clear OTP, write verified flag with 15-min window to submit the form
        $request->session()->forget($sessionKey);
        $request->session()->put('email_change_verified_' . $user->id, [
            'email'      => $newEmail,
            'expires_at' => now()->addMinutes(15)->timestamp,
        ]);

        return response()->json(['success' => true, 'message' => 'ইমেইল যাচাই সফল হয়েছে! এখন প্রোফাইল আপডেট করুন।']);
    }

    public function uploadAvatar(Request $request)
    {
        $user = Auth::user();
        $file = $request->file('avatar');
        if (!$file || !$file->isValid()) {
            return redirect('/portal/profile')->with('error', 'No file selected.');
        }

        $uploader = new UploadService();
        $filename = $uploader->uploadAvatar($file, (int)$user->id);
        if (!$filename) {
            return redirect('/portal/profile')->with('error', 'Upload failed. Use JPG/PNG/WebP under 2MB.');
        }

        DB::table('alumni_profiles')->where('user_id', $user->id)->update(['avatar' => $filename]);
        DB::table('users')->where('id', $user->id)->update(['avatar' => $filename]);

        return redirect('/portal/profile')->with('success', 'Profile photo updated.');
    }

    public function uploadSignature(Request $request)
    {
        $user = Auth::user();
        $file = $request->file('signature');
        if (!$file || !$file->isValid()) {
            return redirect('/portal/profile')->with('error', 'No file selected.');
        }

        $uploader = new UploadService();
        $filename = $uploader->uploadSignature($file, (int)$user->id);
        if (!$filename) {
            return redirect('/portal/profile')->with('error', 'Upload failed.');
        }

        DB::table('users')->where('id', $user->id)->update(['signature_image' => $filename]);

        return redirect('/portal/profile')->with('success', 'আপনার ডিজিটাল স্বাক্ষর (Signature) আপলোড হয়েছে।');
    }

    public function education(Request $request)
    {
        $user    = Auth::user();
        $model   = new AlumniProfile();
        $profile = $model->getByUserId((int)$user->id);
        $education = $profile ? $model->getEducation((int)$profile['id']) : [];

        return $this->legacyView('portal/education', compact('user', 'profile', 'education'), 'portal', 'Education');
    }

    public function saveEducation(Request $request)
    {
        $user    = Auth::user();
        $model   = new AlumniProfile();
        $profile = $model->getByUserId((int)$user->id);
        if (!$profile) return redirect('/portal/profile');

        DB::table('alumni_education')->insert([
            'alumni_profile_id' => $profile['id'],
            'degree'            => $request->input('degree'),
            'institution'       => $request->input('institution'),
            'field_of_study'    => $request->input('field_of_study'),
            'graduation_year'   => $request->input('graduation_year') ?: null,
            'created_at'        => now(),
            'updated_at'        => now(),
        ]);

        return redirect('/portal/profile/education')->with('success', 'Education added.');
    }

    public function deleteEducation(Request $request)
    {
        $user    = Auth::user();
        $model   = new AlumniProfile();
        $profile = $model->getByUserId((int)$user->id);
        if (!$profile) return redirect('/portal/profile');

        $id = (int)$request->input('id');
        if ($id > 0) {
            DB::table('alumni_education')->where('id', $id)->where('alumni_profile_id', $profile['id'])->delete();
        }

        return redirect('/portal/profile/education')->with('success', 'Education record deleted successfully.');
    }

    public function employment(Request $request)
    {
        $user    = Auth::user();
        $model   = new AlumniProfile();
        $profile = $model->getByUserId((int)$user->id);
        $employment = $profile ? $model->getEmployment((int)$profile['id']) : [];

        return $this->legacyView('portal/employment', compact('user', 'profile', 'employment'), 'portal', 'Employment');
    }

    public function saveEmployment(Request $request)
    {
        $user    = Auth::user();
        $model   = new AlumniProfile();
        $profile = $model->getByUserId((int)$user->id);
        if (!$profile) return redirect('/portal/profile');

        $isCurrent = $request->input('is_current') ? 1 : 0;
        DB::table('alumni_employment')->insert([
            'alumni_profile_id' => $profile['id'],
            'job_title'         => $request->input('job_title'),
            'organization'      => $request->input('organization'),
            'department'        => $request->input('department'),
            'location'          => $request->input('location'),
            'start_year'        => $request->input('start_year') ?: null,
            'end_year'          => $isCurrent ? null : ($request->input('end_year') ?: null),
            'is_current'        => $isCurrent,
            'description'       => $request->input('description'),
            'created_at'        => now(),
            'updated_at'        => now(),
        ]);

        return redirect('/portal/profile/employment')->with('success', 'Employment added.');
    }

    public function deleteEmployment(Request $request)
    {
        $user    = Auth::user();
        $model   = new AlumniProfile();
        $profile = $model->getByUserId((int)$user->id);
        if (!$profile) return redirect('/portal/profile');

        $id = (int)$request->input('id');
        if ($id > 0) {
            DB::table('alumni_employment')->where('id', $id)->where('alumni_profile_id', $profile['id'])->delete();
        }

        return redirect('/portal/profile/employment')->with('success', 'Employment record deleted successfully.');
    }

    public function settings(Request $request)
    {
        $user    = Auth::user();
        $model   = new AlumniProfile();
        $profile = $model->getByUserId((int)$user->id);
        return $this->legacyView('portal/settings', compact('user', 'profile'), 'portal', 'Account Settings');
    }

    public function updateSettings(Request $request)
    {
        $user = Auth::user();

        // Change password
        if ($request->input('current_password') && $request->input('new_password')) {
            if (!Hash::check($request->input('current_password'), $user->password)) {
                return redirect('/portal/settings')->with('error', 'Current password is incorrect.');
            }
            if (strlen($request->input('new_password')) < 8) {
                return redirect('/portal/settings')->with('error', 'New password must be at least 8 characters.');
            }
            DB::table('users')->where('id', $user->id)->update([
                'password'   => Hash::make($request->input('new_password')),
                'updated_at' => now(),
            ]);
        }

        // Privacy
        $model   = new AlumniProfile();
        $profile = $model->getByUserId((int)$user->id);
        if ($profile) {
            DB::table('alumni_profiles')->where('id', $profile['id'])->update([
                'is_public'  => $request->input('is_public') ? 1 : 0,
                'updated_at' => now(),
            ]);
        }

        return redirect('/portal/settings')->with('success', 'Settings updated.');
    }

    public function contactRequests(Request $request)
    {
        $user    = Auth::user();
        $model   = new AlumniProfile();
        $profile = $model->getByUserId((int)$user->id);

        $requests = DB::table('contact_requests')
            ->where('alumni_profile_id', $profile['id'] ?? 0)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(fn($r) => (array)$r)
            ->toArray();

        return $this->legacyView('portal/contact_requests', compact('requests', 'user', 'profile'), 'portal', 'Contact Requests');
    }

    public function acceptContactRequest(Request $request, $id)
    {
        $id      = (int)$id;
        $user    = Auth::user();
        $model   = new AlumniProfile();
        $profile = $model->getByUserId((int)$user->id);

        $requestData = DB::table('contact_requests')->where('id', $id)->where('alumni_profile_id', $profile['id'] ?? 0)->first();

        if (!$requestData) {
            return redirect('/portal/contact-requests')->with('error', 'Request not found.');
        }
        $requestData = (array)$requestData;

        $method  = trim((string)$request->input('accepted_contact_method', 'Email'));
        $details = trim((string)$request->input('accepted_contact_details', ''));
        $note    = trim((string)$request->input('instruction_note', ''));

        DB::table('contact_requests')->where('id', $id)->update([
            'status'                  => 'accepted',
            'accepted_contact_method' => $method,
            'accepted_contact_details'=> $details,
            'instruction_note'        => $note,
            'updated_at'              => now(),
        ]);

        $mailService = new MailService();
        $htmlBody = '<p>প্রিয় ' . e($requestData['requester_name']) . ',</p>' .
                    '<p>শুভ সংবাদ! আইপিএইচ অ্যালামনাই সদস্য <strong>' . e($user->name) . '</strong> আপনার যোগাযোগের অনুরোধটি গ্রহণ (Accept) করেছেন।</p>' .
                    '<div style="background:#f0fdf4;border:1px solid #bbf7d0;padding:16px;border-radius:12px;margin:16px 0;color:#166534;">' .
                    '<strong>পছন্দের মাধ্যম (Method):</strong> ' . e($method) . '<br>' .
                    '<strong>যোগাযোগের তথ্য (Contact Info):</strong> ' . e($details) . '<br>' .
                    (!empty($note) ? '<strong>নির্দেশনা / সময়সূচি:</strong> ' . e($note) . '<br>' : '') .
                    '</div>' .
                    '<p>সদস্যের নির্দেশনা অনুসরণ করে উক্ত মাধ্যমে যোগাযোগ করার জন্য অনুরোধ করা হলো।</p>';

        $mailService->send(
            $requestData['requester_email'],
            '[IPH Alumni] যোগাযোগের অনুরোধ গৃহীত হয়েছে: ' . $user->name,
            $htmlBody
        );

        return redirect('/portal/contact-requests')->with('success', 'যোগাযোগের অনুরোধ সফলভাবে এপ্রুভ করা হয়েছে এবং অনুরোধকারীর ইমেইলে কন্টাক্ট ইনফো ও নির্দেশনা পাঠিয়ে দেওয়া হয়েছে!');
    }

    public function rejectContactRequest(Request $request, $id)
    {
        $id      = (int)$id;
        $user    = Auth::user();
        $model   = new AlumniProfile();
        $profile = $model->getByUserId((int)$user->id);

        DB::table('contact_requests')->where('id', $id)->where('alumni_profile_id', $profile['id'] ?? 0)->update([
            'status'     => 'rejected',
            'updated_at' => now(),
        ]);

        return redirect('/portal/contact-requests')->with('success', 'অনুরোধটি প্রত্যাখ্যান করা হয়েছে।');
    }

    public function deleteContactRequest(Request $request, $id)
    {
        $id      = (int)$id;
        $user    = Auth::user();
        $model   = new AlumniProfile();
        $profile = $model->getByUserId((int)$user->id);

        DB::table('contact_requests')->where('id', $id)->where('alumni_profile_id', $profile['id'] ?? 0)->delete();

        return redirect('/portal/contact-requests')->with('success', 'যোগাযোগের অনুরোধের রেকর্ডটি সফলভাবে মুছে ফেলা হয়েছে।');
    }
}
