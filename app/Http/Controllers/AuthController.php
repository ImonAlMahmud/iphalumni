<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\AlumniProfile;
use App\Services\AuditLogger;
use App\Services\MailService;
use App\Services\UploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends BaseController
{
    public function loginForm(Request $request)
    {
        if (Auth::check()) return redirect('/portal');
        return $this->legacyView('auth/login', [], 'main', 'Log In');
    }

    public function login(Request $request)
    {
        $email    = trim($request->input('email', ''));
        $password = $request->input('password', '');

        if (empty($email) || empty($password)) {
            return back()->with('error', 'Email and password are required.');
        }

        $loginAttempt = Auth::attempt(['email' => $email, 'password' => $password]);
        if (! $loginAttempt) {
            $matchedUser = \App\Models\User::where('secondary_email', $email)
                ->orWhereHas('alumniProfile', function ($q) use ($email) {
                    $q->where('secondary_email', $email);
                })
                ->first();

            if ($matchedUser && \Illuminate\Support\Facades\Hash::check($password, $matchedUser->password)) {
                Auth::login($matchedUser, $request->boolean('remember'));
                $loginAttempt = true;
            }
        }

        if ($loginAttempt) {
            $request->session()->regenerate();
            $user = Auth::user();

            if ($user->status !== 'active' && $user->status !== 'pending') {
                Auth::logout();
                return back()->with('error', 'Your account is not active. Please contact admin.');
            }

            AuditLogger::log('USER_LOGIN', "User logged in: {$email}");

            return redirect('/portal');
        }

        return back()->with('error', 'Invalid email or password, or your account is not yet active.');
    }

    public function registerForm(Request $request)
    {
        if (Auth::check()) return redirect('/portal');
        return $this->legacyView('auth/register', [], 'main', 'Register as Alumni');
    }

    public function register(Request $request)
    {
        $name       = trim((string)$request->input('name', ''));
        $email      = trim((string)$request->input('email', ''));
        $password   = (string)$request->input('password', '');
        $confirm    = (string)$request->input('password_confirm', '');
        $batch      = (string)$request->input('batch_year', '');
        $studentId  = trim((string)$request->input('student_id', ''));
        $phone      = trim((string)$request->input('phone', ''));
        $nidNumber  = trim((string)$request->input('nid_number', ''));
        $gender     = (string)$request->input('gender', '');
        $dob        = (string)$request->input('dob', '');
        $bloodGroup = (string)$request->input('blood_group', '');
        $location   = trim((string)$request->input('current_location', ''));
        $website    = trim((string)$request->input('website', ''));
        $linkedin   = trim((string)$request->input('linkedin_url', ''));
        $facebook   = trim((string)$request->input('facebook_url', ''));
        $spouseName = trim((string)$request->input('spouse_name', ''));
        $children   = trim((string)$request->input('children_info', ''));

        // Validation
        $errors = [];
        if (strlen($name) < 2) $errors[] = 'Full name is required (minimum 2 characters).';
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Please enter a valid email address.';
        if (strlen($password) < 8) $errors[] = 'Password must be at least 8 characters.';
        if ($password !== $confirm) $errors[] = 'Passwords do not match.';
        if (empty($batch)) $errors[] = 'Batch is required.';
        if (empty($studentId)) $errors[] = 'Dhaka University Registration ID is required.';

        // Document upload
        $file = $request->file('proof_document');
        if (!$file || !$file->isValid()) {
            return back()->with('error', 'Please upload a valid proof of studentship / graduation document.')->withInput();
        }

        $uploader = new UploadService();
        $proofDoc = $uploader->uploadDocument($file, 'studentship_proof');
        if (!$proofDoc) {
            return back()->with('error', 'Document upload failed. Allowed formats: PDF, JPG, PNG under 5MB.')->withInput();
        }

        if ($errors) {
            return back()->with('error', implode(' ', $errors))->withInput();
        }

        // Check if email already exists
        if (DB::table('users')->where('email', $email)->exists()) {
            return back()->with('error', 'This email is already registered. Please log in.')->withInput();
        }

        try {
            // Clean names function for auto-verification
            $cleanName = function (string $n): string {
                $n = mb_strtolower($n, 'UTF-8');
                $prefixes = ['md.', 'md', 'mst.', 'mst', 'most.', 'most', 'dr.', 'dr', 'mo:', 'মো:', 'মোঃ', 'মোহাম্মদ', 'মোসাম্মৎ'];
                foreach ($prefixes as $prefix) {
                    if (str_starts_with($n, $prefix . ' ')) {
                        $n = substr($n, strlen($prefix) + 1);
                    }
                }
                return trim(str_replace(['.', '-', ' '], '', $n));
            };

            // Auto verification check
            $autoVerified      = false;
            $matchedStudentRef = null;
            try {
                $candidates     = array_map(fn($r) => (array)$r, DB::select("SELECT * FROM students_reference WHERE session LIKE ?", [$batch . '%']));
                $inputCleanName = $cleanName($name);
                foreach ($candidates as $cand) {
                    if ($inputCleanName === $cleanName((string)($cand['name_english'] ?? '')) || $inputCleanName === $cleanName((string)($cand['name_bangla'] ?? ''))) {
                        $autoVerified      = true;
                        $matchedStudentRef = $cand;
                        break;
                    }
                }
            } catch (\Throwable $e) {}

            $userStatus    = $autoVerified ? 'active' : 'pending';
            $profileStatus = $autoVerified ? 'verified' : 'pending';

            // Create user
            $userId = DB::table('users')->insertGetId([
                'name'       => $name,
                'email'      => $email,
                'password'   => Hash::make($password),
                'role'       => 'alumni',
                'status'     => $userStatus,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Create alumni profile
            $studentRefId  = $matchedStudentRef['id'] ?? null;
            $finalStudentId = $studentId ?: ($matchedStudentRef['roll'] ?? null);
            $finalPhone     = $phone ?: ($matchedStudentRef['mobile'] ?? null);
            $finalGender    = $gender ?: ($matchedStudentRef['gender'] ?? null);
            $finalLocation  = $location ?: ($matchedStudentRef['district'] ?? null);

            DB::table('alumni_profiles')->insert([
                'user_id'              => $userId,
                'student_reference_id' => $studentRefId,
                'batch_year'           => $batch,
                'student_id'           => $finalStudentId,
                'phone'                => $finalPhone,
                'nid_number'           => $nidNumber ?: null,
                'dob'                  => $dob ?: null,
                'gender'               => $finalGender,
                'blood_group'          => $bloodGroup ?: null,
                'current_location'     => $finalLocation,
                'website'              => $website ?: null,
                'linkedin_url'         => $linkedin ?: null,
                'facebook_url'         => $facebook ?: null,
                'spouse_name'          => $spouseName ?: null,
                'children_info'        => $children ?: null,
                'proof_document'       => $proofDoc,
                'status'               => $profileStatus,
                'is_featured'          => 0,
                'created_at'           => now(),
                'updated_at'           => now(),
            ]);

            // Send Welcome Email
            try {
                $templates = MailService::getTemplates();
                if (isset($templates['new_member_welcome'])) {
                    $welcomeTpl = $templates['new_member_welcome'];
                    MailService::send($email, $welcomeTpl['subject'] ?? 'Welcome to IPH Alumni Association!', $welcomeTpl);
                }
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::warning('Welcome email sending error: ' . $e->getMessage());
            }

            $msg = $autoVerified
                ? 'Your profile has been automatically verified! You can now log in.'
                : 'Registration submitted! Your profile will be verified within 48 hours. You can log in once approved.';

            return redirect('/login')->with('success', $msg);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Registration error', ['err' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return back()->with('error', 'Registration failed. Please contact support.')->withInput();
        }
    }

    public function logout(Request $request)
    {
        AuditLogger::log('USER_LOGOUT', 'User logged out');
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/')->with('success', 'You have been logged out.');
    }

    public function forgotForm(Request $request)
    {
        return $this->legacyView('auth/forgot_password', [], 'main', 'Forgot Password');
    }

    public function forgot(Request $request)
    {
        $email = trim($request->input('email', ''));
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return back()->with('error', 'Please enter a valid email address.');
        }

        $user = DB::table('users')->where('email', $email)
            ->orWhere('secondary_email', $email)
            ->first();
        if (!$user) {
            $profileUserId = DB::table('alumni_profiles')->where('secondary_email', $email)->value('user_id');
            if ($profileUserId) {
                $user = DB::table('users')->where('id', $profileUserId)->first();
            }
        }
        if ($user) {
            // Generate plain token for the URL, store only the SHA-256 hash in DB
            $plainToken = bin2hex(random_bytes(32));
            $hashedToken = hash('sha256', $plainToken);

            DB::table('users')->where('id', $user->id)->update([
                'reset_token'      => $hashedToken,   // hashed — safe even if DB is compromised
                'reset_expires_at' => now()->addHour(),
            ]);

            $resetUrl = url('/reset-password?token=' . $plainToken);
            $mail     = new MailService();
            $mail->send($email, 'Password Reset Request — IPH Alumni',
                "<p>Hello " . e($user->name) . ",</p>
                <p>Click the link below to reset your password (valid for 1 hour):</p>
                <p><a href='{$resetUrl}'>Reset Password</a></p>"
            );
        }

        return back()->with('success', 'If that email is registered, a password reset link has been sent to your inbox.');
    }

    public function resetForm(Request $request)
    {
        $token = $request->query('token', '');
        if (empty($token)) {
            return redirect('/login')->with('error', 'Invalid password reset token.');
        }

        // Hash the plain URL token to look up in DB
        $hashedToken = hash('sha256', $token);
        $user = DB::table('users')
            ->where('reset_token', $hashedToken)
            ->where('reset_expires_at', '>', now())
            ->first();
        if (!$user) {
            return redirect('/forgot-password')->with('error', 'This password reset link is invalid or has expired.');
        }

        return $this->legacyView('auth/reset_password', compact('token', 'user'), 'main', 'Reset Password');
    }

    public function reset(Request $request)
    {
        $token    = $request->input('token', '');
        $password = $request->input('password', '');
        $confirm  = $request->input('password_confirm', '');

        if (empty($token)) return redirect('/login')->with('error', 'Token is missing.');
        if (strlen($password) < 8) return back()->with('error', 'Password must be at least 8 characters.');
        if ($password !== $confirm) return back()->with('error', 'Passwords do not match.');

        // Hash the incoming plain token before looking up in DB
        $hashedToken = hash('sha256', $token);
        $user = DB::table('users')
            ->where('reset_token', $hashedToken)
            ->where('reset_expires_at', '>', now())
            ->first();
        if (!$user) {
            return redirect('/forgot-password')->with('error', 'This password reset link is invalid or has expired.');
        }

        DB::table('users')->where('id', $user->id)->update([
            'password'         => Hash::make($password),
            'reset_token'      => null,
            'reset_expires_at' => null,
        ]);

        return redirect('/login')->with('success', 'Your password has been reset successfully! You can now log in.');
    }

    public function sendVerificationCode(Request $request)
    {
        $email = trim((string)$request->input('email', ''));
        $code  = trim((string)$request->input('code', ''));

        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return response()->json(['success' => false, 'message' => 'Please enter a valid email address.']);
        }
        if (empty($code)) {
            return response()->json(['success' => false, 'message' => 'Verification code is missing.']);
        }

        $appName = (string)config('app.name', 'IPH Alumni Association');
        $subject = "Verification Code: {$code} - {$appName}";

        $bodyHtml = "<p>প্রিয় সদস্য,</p>
            <p><strong>{$appName}</strong> পোর্টালে আপনার অ্যাকাউন্ট রেজিস্ট্রেশন সম্পন্ন করতে ইমেইল ভেরিফিকেশন ওটিপি (OTP) কোডটি নিচে দেওয়া হলো:</p>
            <div style='text-align:center;margin:24px 0;'>
                <span style='display:inline-block;padding:12px 32px;background:#800020;color:#ffffff;font-size:26px;font-weight:bold;letter-spacing:6px;border-radius:12px;box-shadow:0 4px 15px rgba(128,0,32,0.3);'>{$code}</span>
            </div>
            <p style='font-size:13px;color:#64748b;'>এই ভেরিফিকেশন কোডটির মেয়াদ ১০ মিনিট থাকবে। আপনি যদি এই রেজিস্ট্রেশন রিকোয়েস্ট না করে থাকেন, তবে এই ইমেইলটি উপেক্ষা করুন।</p>";

        $result = MailService::send($email, $subject, [
            'title'       => 'ইমেইল ভেরিফিকেশন কোড (OTP)',
            'badge'       => 'EMAIL VERIFICATION',
            'content'     => $bodyHtml,
            'footer_note' => 'Official automated security message from IPH Alumni Association.',
        ]);

        return response()->json([
            'success' => (bool)($result['success'] ?? false),
            'message' => ($result['success'] ?? false)
                ? 'আপনার ইমেইলে ভেরিফিকেশন কোড পাঠানো হয়েছে!'
                : ($result['message'] ?? 'Failed to send verification email. Please verify SMTP settings.'),
        ]);
    }
}
