<?php

declare(strict_types=1);

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\BaseController;
use App\Models\AlumniProfile;
use App\Models\Membership;
use App\Models\Setting;
use App\Services\UploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MembershipController extends BaseController
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $profileModel = new AlumniProfile;
        $profile = $profileModel->getByUserId((int) $user->id);
        $membership = $profile ? (new Membership)->getByAlumni((int) $profile['id']) : null;

        $types = DB::table('membership_types')
            ->where('is_active', 1)
            ->whereRaw('LOWER(name) != ?', ['honorary'])
            ->orderBy('sort_order')
            ->get()
            ->map(fn ($r) => (array) $r)
            ->toArray();
        $paymentInstructions = (new Setting)->get('payment_instructions', '');

        return $this->legacyView(
            'portal/membership',
            compact('user', 'profile', 'membership', 'types', 'paymentInstructions'),
            'portal',
            'Membership'
        );
    }

    public function apply(Request $request)
    {
        $user = Auth::user();
        $model = new AlumniProfile;
        $profile = $model->getByUserId((int) $user->id);

        if (! $profile) {
            return redirect('/portal/profile')->with('error', 'Please complete your profile first.');
        }
        if (! in_array($profile['status'], ['approved', 'verified', 'active'])) {
            return redirect('/portal/membership')->with('error', 'Your profile must be verified before applying for membership.');
        }

        $typeId = (int) $request->input('membership_type_id');
        $type = DB::table('membership_types')->where('id', $typeId)->where('is_active', 1)->first();

        if (! $type) {
            return redirect('/portal/membership')->with('error', 'Invalid membership type.');
        }
        $type = (array) $type;

        if (strtolower((string) $type['name']) === 'honorary') {
            return redirect('/portal/membership')->with('error', 'Honorary membership cannot be applied directly. It is granted exclusively by administrators.');
        }

        // Check existing active or existing membership
        $existing = DB::table('memberships')
            ->where('alumni_profile_id', (int) $profile['id'])
            ->orderBy('id', 'desc')
            ->first();

        if ($existing && $existing->status === 'active') {
            return redirect('/portal/membership')->with('error', 'You already have an active membership.');
        }

        // Payment Details if Fee > 0
        $paymentMethod = null;
        $transactionId = null;
        $paymentSlipName = null;
        $uploader = new UploadService;

        if ($type['fee'] > 0) {
            $paymentMethod = trim((string) $request->input('method', ''));
            $transactionId = trim((string) $request->input('transaction_id', ''));
            $paymentSlipFile = $request->file('payment_slip');

            if (empty($paymentMethod) || empty($transactionId)) {
                return redirect('/portal/membership')->with('error', 'Please provide the payment method and transaction ID.');
            }
            if (! $paymentSlipFile || ! $paymentSlipFile->isValid()) {
                return redirect('/portal/membership')->with('error', 'Please upload a valid payment proof document/screenshot.');
            }

            $paymentSlipName = $uploader->uploadDocument($paymentSlipFile, 'payment_slip');
            if (! $paymentSlipName) {
                return redirect('/portal/membership')->with('error', 'Payment slip upload failed. Allowed formats: PDF, JPG, PNG under 5MB.');
            }
        }

        $startDate = date('Y-m-d');
        $endDate = $type['duration_months'] ? date('Y-m-d', strtotime("+{$type['duration_months']} months")) : null;

        if ($existing) {
            $memId = (int) $existing->id;
            DB::table('memberships')->where('id', $memId)->update([
                'membership_type_id' => $typeId,
                'status' => 'pending',
                'start_date' => $startDate,
                'end_date' => $endDate,
                'deleted_at' => null,
                'updated_at' => now(),
            ]);
        } else {
            $baseMemberNum = 'IPH-'.($profile['batch_year'] ?? date('Y')).'-'.str_pad((string) $profile['id'], 4, '0', STR_PAD_LEFT);
            $memberNum = $baseMemberNum;
            $suffix = 1;
            while (DB::table('memberships')->where('membership_number', $memberNum)->exists()) {
                $memberNum = $baseMemberNum.'-'.$suffix++;
            }
            $qrCode = bin2hex(random_bytes(16));

            $memId = DB::table('memberships')->insertGetId([
                'alumni_profile_id' => (int) $profile['id'],
                'membership_type_id' => $typeId,
                'status' => 'pending',
                'start_date' => $startDate,
                'end_date' => $endDate,
                'membership_number' => $memberNum,
                'qr_code' => $qrCode,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Create payment record
        DB::table('membership_payments')->insert([
            'membership_id' => $memId,
            'amount' => $type['fee'],
            'currency' => 'BDT',
            'method' => $paymentMethod,
            'transaction_id' => $transactionId,
            'payment_slip' => $paymentSlipName,
            'status' => $type['fee'] > 0 ? 'pending' : 'paid',
            'created_at' => now(),
        ]);

        // Notification
        DB::table('notifications')->insert([
            'user_id' => $user->id,
            'type' => 'membership_applied',
            'title' => 'Membership Application Submitted',
            'message' => "Your {$type['name']} membership application has been submitted. You will be notified once approved.",
            'is_read' => 0,
            'created_at' => now(),
        ]);

        return redirect('/portal/membership')->with('success', 'Membership application submitted! You will receive confirmation once approved.');
    }

    public function qrId(Request $request)
    {
        $user = Auth::user();
        $profile = (new AlumniProfile)->getByUserId((int) $user->id);
        $membership = $profile ? (new Membership)->getByAlumni((int) $profile['id']) : null;

        if (! $membership || $membership['status'] !== 'active') {
            return redirect('/portal/membership')->with('error', 'You do not have an active membership.');
        }

        return $this->legacyView('portal/qr_id', compact('user', 'profile', 'membership'), 'portal', 'My QR ID Card');
    }

    public function verify(Request $request, $code)
    {
        $result = DB::table('memberships as m')
            ->join('membership_types as mt', 'mt.id', '=', 'm.membership_type_id')
            ->join('alumni_profiles as ap', 'ap.id', '=', 'm.alumni_profile_id')
            ->join('users as u', 'u.id', '=', 'ap.user_id')
            ->select('m.*', 'mt.name as type_name', 'u.name as member_name', 'ap.batch_year')
            ->where('m.qr_code', (string) $code)
            ->first();

        $result = $result ? (array) $result : null;

        return $this->legacyView('portal/verify', compact('result'), 'main', 'Membership Verification');
    }
}
