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

        if (Auth::attempt(['email' => $email, 'password' => $password])) {
            $request->session()->regenerate();
            $user = Auth::user();

            if ($user->status !== 'active' && $user->status !== 'pending') {
                Auth::logout();
                return back()->with('error', 'Your account is not active. Please contact admin.');
            }

            AuditLogger::log('USER_LOGIN', "User logged in: {$email}");

            if (in_array($user->role, ['super_admin', 'admin', 'editor'])) {
                return redirect('/admin');
            }
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
        $name       = trim($request->input('name', ''));
        $email      = trim($request->input('email', ''));
        $password   = $request->input('password', '');
        $confirm    = $request->input('password_confirm', '');
        $batch      = $request->input('batch_year', '');
        $studentId  = trim($request->input('student_id', ''));
        $phone      = trim($request->input('phone', ''));
        $nidNumber  = trim($request->input('nid_number', ''));
        $gender     = $request->input('gender', '');
        $dob        = $request->input('dob', '');
        $bloodGroup = $request->input('blood_group', '');
        $location   = trim($request->input('current_location', ''));
        $website    = trim($request->input('website', ''));
        $linkedin   = trim($request->input('linkedin_url', ''));
        $facebook   = trim($request->input('facebook_url', ''));
        $spouseName = trim($request->input('spouse_name', ''));
        $children   = trim($request->input('children_info', ''));

        // Validation
        $errors = [];
        if (strlen($name) < 2) $errors[] = 'Full name is required (minimum 2 characters).';
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Please enter a valid email address.';
        if (strlen($password) < 8) $errors[] = 'Password must be at least 8 characters.';
        if ($password !== $confirm) $errors[] = 'Passwords do not match.';
        if (empty($batch)) $errors[] = 'Batch year is required.';

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
                if ($inputCleanName === $cleanName($cand['name_english']) || $inputCleanName === $cleanName($cand['name_bangla'] ?? '')) {
                    $autoVerified      = true;
                    $matchedStudentRef = $cand;
                    break;
                }
            }
        } catch (\Exception $e) {}

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

        $msg = $autoVerified
            ? 'Your profile has been automatically verified! You can now log in.'
            : 'Registration submitted! Your profile will be verified within 48 hours. You can log in once approved.';

        return redirect('/login')->with('success', $msg);
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

        $user = DB::table('users')->where('email', $email)->first();
        if ($user) {
            $token = bin2hex(random_bytes(32));
            DB::table('users')->where('id', $user->id)->update([
                'reset_token'      => $token,
                'reset_expires_at' => now()->addHour(),
            ]);

            $resetUrl = url('/reset-password?token=' . $token);
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

        $user = DB::table('users')->where('reset_token', $token)->where('reset_expires_at', '>', now())->first();
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

        $user = DB::table('users')->where('reset_token', $token)->where('reset_expires_at', '>', now())->first();
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
        $email = trim($request->input('email', ''));
        $code  = trim($request->input('code', ''));

        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return response()->json(['success' => false, 'message' => 'Please enter a valid email address.']);
        }
        if (empty($code)) {
            return response()->json(['success' => false, 'message' => 'Verification code is missing.']);
        }

        $mailService = new MailService();
        $appName     = config('app.name');
        $subject     = "Verification Code: {$code} - {$appName}";

        $bodyHtml = "<p>Hello,</p>
            <p>Your email verification code for registration on <strong>{$appName}</strong> is:</p>
            <div style='text-align:center;margin:25px 0;'>
                <span style='display:inline-block;padding:12px 30px;background:#800020;color:#fff;font-size:24px;font-weight:bold;letter-spacing:6px;border-radius:10px;'>{$code}</span>
            </div>
            <p style='font-size:13px;color:#777;'>This code is valid for 10 minutes.</p>";

        $sent = $mailService->send($email, $subject, $bodyHtml);

        return response()->json([
            'success' => $sent,
            'message' => $sent ? 'Verification code sent to your email!' : 'Failed to send email. Please verify your SMTP settings.',
        ]);
    }
}
