<?php

declare(strict_types=1);

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\BaseController;
use App\Models\AlumniProfile;
use App\Models\Membership;
use App\Services\UddoktaPayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UddoktaPayController extends BaseController
{
    protected UddoktaPayService $uddoktaPay;

    public function __construct(UddoktaPayService $uddoktaPay)
    {
        $this->uddoktaPay = $uddoktaPay;
    }

    /**
     * Initiate UddoktaPay checkout for membership
     */
    public function initiate(Request $request)
    {
        $user = Auth::user();
        if (! $user) {
            return redirect('/login')->with('error', 'Please log in to continue.');
        }

        $profile = (new AlumniProfile)->getByUserId((int) $user->id);
        if (! $profile) {
            return redirect('/portal/profile')->with('error', 'Please complete your alumni profile first.');
        }

        if (! in_array($profile['status'], ['approved', 'verified', 'active'])) {
            return redirect('/portal/membership')->with('error', 'Your alumni profile must be verified before applying for membership.');
        }

        try {
            $membershipId = (int) $request->input('membership_id', 0);
            $typeId = (int) $request->input('membership_type_id', 0);

            $membership = null;
            $type = null;

            if ($membershipId > 0) {
                $membership = DB::table('memberships')
                    ->where('id', $membershipId)
                    ->where('alumni_profile_id', (int) $profile['id'])
                    ->first();

                if (! $membership) {
                    return redirect('/portal/membership')->with('error', 'Membership record not found.');
                }

                if ($membership->status === 'active') {
                    return redirect('/portal/membership')->with('info', 'Your membership is already active!');
                }

                $type = DB::table('membership_types')->where('id', $membership->membership_type_id)->first();
            } elseif ($typeId > 0) {
                $type = DB::table('membership_types')->where('id', $typeId)->where('is_active', 1)->first();

                if (! $type) {
                    return redirect('/portal/membership')->with('error', 'Invalid membership type selected.');
                }

                if (strtolower((string) $type->name) === 'honorary') {
                    return redirect('/portal/membership')->with('error', 'Honorary membership is granted exclusively by administrators.');
                }

                // Check if already active or has existing record
                $existing = DB::table('memberships')
                    ->where('alumni_profile_id', (int) $profile['id'])
                    ->orderBy('id', 'desc')
                    ->first();

                if ($existing && $existing->status === 'active') {
                    return redirect('/portal/membership')->with('info', 'You already have an active membership.');
                }

                $startDate = date('Y-m-d');
                $endDate = $type->duration_months ? date('Y-m-d', strtotime("+{$type->duration_months} months")) : null;

                // Reuse existing membership record (pending, cancelled, expired) or create new
                if ($existing) {
                    $membershipId = (int) $existing->id;
                    $updateData = [
                        'membership_type_id' => $typeId,
                        'status' => 'pending',
                        'start_date' => $startDate,
                        'end_date' => $endDate,
                        'deleted_at' => null,
                        'updated_at' => now(),
                    ];
                    if (empty($existing->membership_number) || !str_starts_with($existing->membership_number, 'IPHAA-')) {
                        $updateData['membership_number'] = 'IPHAA-' . str_pad((string) $profile['id'], 5, '0', STR_PAD_LEFT);
                    }
                    DB::table('memberships')->where('id', $membershipId)->update($updateData);
                } else {
                    $memberNum = 'IPHAA-' . str_pad((string) $profile['id'], 5, '0', STR_PAD_LEFT);
                    $suffix = 1;
                    while (DB::table('memberships')->where('membership_number', $memberNum)->exists()) {
                        $memberNum = 'IPHAA-' . str_pad((string) $profile['id'], 5, '0', STR_PAD_LEFT) . '-' . $suffix++;
                    }
                    $qrCode = bin2hex(random_bytes(16));

                    $membershipId = DB::table('memberships')->insertGetId([
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
            } else {
                return redirect('/portal/membership')->with('error', 'Please select a valid membership plan.');
            }

            $type = (array) $type;
            $fee = (float) ($type['fee'] ?? 0);

            if ($fee <= 0) {
                // Free membership -> auto activate
                $this->activateMembership($membershipId, 'free', 'FREE-'.uniqid(), null, 0);

                return redirect('/portal/membership')->with('success', 'Membership activated successfully!');
            }

            // Ensure pending payment record exists
            $existingPayment = DB::table('membership_payments')->where('membership_id', $membershipId)->first();
            if (! $existingPayment) {
                DB::table('membership_payments')->insert([
                    'membership_id' => $membershipId,
                    'amount' => $fee,
                    'currency' => 'BDT',
                    'method' => 'uddoktapay',
                    'transaction_id' => null,
                    'status' => 'pending',
                    'created_at' => now(),
                ]);
            } else {
                DB::table('membership_payments')->where('membership_id', $membershipId)->update([
                    'amount' => $fee,
                    'method' => 'uddoktapay',
                    'status' => 'pending',
                    'updated_at' => now(),
                ]);
            }

            // Prepare UddoktaPay Checkout Payload
            $payload = [
                'full_name' => $user->name ?? 'Alumni Member',
                'email' => $user->email,
                'amount' => (string) $fee,
                'metadata' => [
                    'membership_id' => $membershipId,
                    'alumni_profile_id' => (int) $profile['id'],
                    'user_id' => (int) $user->id,
                    'type_name' => $type['name'],
                ],
                'redirect_url' => url('/portal/membership/payment/uddoktapay/success'),
                'cancel_url' => url('/portal/membership/payment/uddoktapay/cancel'),
                'webhook_url' => url('/webhook/uddoktapay'),
                'return_type' => 'GET',
            ];

            $response = $this->uddoktaPay->initPayment($payload);

            if ($response['success'] && ! empty($response['payment_url'])) {
                return redirect()->away($response['payment_url']);
            }

            return redirect('/portal/membership')->with('error', $response['message'] ?? 'Could not initialize UddoktaPay payment.');
        } catch (\Throwable $e) {
            Log::error('UddoktaPay initiation error', ['err' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);

            return redirect('/portal/membership')->with('error', 'Unable to initialize payment at this moment. Please contact admin.');
        }
    }

    /**
     * User return redirect from UddoktaPay after payment
     */
    public function success(Request $request)
    {
        $invoiceId = (string) $request->input('invoice_id', '');

        if (empty($invoiceId)) {
            return redirect('/portal/membership')->with('error', 'Invalid payment response. Invoice ID missing.');
        }

        $paymentData = $this->uddoktaPay->verifyPayment($invoiceId);

        if (! $paymentData) {
            return redirect('/portal/membership')->with('error', 'Unable to verify payment with UddoktaPay. Please contact support if your account was charged.');
        }

        $status = strtoupper((string) ($paymentData['status'] ?? ''));

        if ($status === 'COMPLETED') {
            $metadata = $paymentData['metadata'] ?? [];
            $membershipId = (int) ($metadata['membership_id'] ?? 0);
            $method = (string) ($paymentData['payment_method'] ?? 'uddoktapay');
            $trxId = (string) ($paymentData['transaction_id'] ?? $invoiceId);
            $amount = (float) ($paymentData['amount'] ?? 0);

            if ($membershipId > 0) {
                $this->activateMembership($membershipId, $method, $trxId, $invoiceId, $amount);

                return redirect('/portal/membership')->with('success', 'অভিনন্দন! আপনার পেমেন্ট সফল হয়েছে এবং মেম্বারশিপটি তাৎক্ষণিকভাবে সক্রিয় করা হয়েছে।');
            }

            return redirect('/portal/membership')->with('success', 'Payment verified successfully.');
        }

        if ($status === 'PENDING') {
            return redirect('/portal/membership')->with('warning', 'আপনার পেমেন্টটি বর্তমানে প্রক্রিয়াকরণে (Pending) রয়েছে। কিছুক্ষণের মধ্যে সক্রিয় হয়ে যাবে।');
        }

        return redirect('/portal/membership')->with('error', 'পেমেন্ট সম্পন্ন হয়নি বা বাতিল হয়েছে। (Status: '.$status.')');
    }

    /**
     * Payment Cancelled Handler
     */
    public function cancel(Request $request)
    {
        return redirect('/portal/membership')->with('info', 'পেমেন্ট প্রক্রিয়া বাতিল করা হয়েছে। আপনি পরবর্তীতে যেকোনো সময় আবার চেষ্টা করতে পারেন।');
    }

    /**
     * Public Webhook (IPN) from UddoktaPay
     */
    public function webhook(Request $request)
    {
        $raw = $request->getContent();
        $signature = $request->header('RT-UDDOKTAPAY-SIGN', '');
        $secret = $this->uddoktaPay->getWebhookSecret() ?: env('UDDOKTAPAY_WEBHOOK_SECRET', '');
        if (empty($secret) || empty($signature) || !hash_equals(hash_hmac('sha256', $raw, $secret), trim($signature))) {
            Log::warning('UddoktaPay Webhook: invalid signature', ['ip' => $request->ip()]);
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $payload = $request->all();
        // idempotency check:
        $trxId = (string)($payload['transaction_id'] ?? ($payload['invoice_id'] ?? ''));
        if (!empty($trxId) && DB::table('membership_payments')->where('transaction_id', $trxId)->where('status', 'paid')->exists()) {
            Log::info('Webhook duplicate trx ignored', ['trx' => $trxId]);
            return response()->json(['status' => 'already_processed'], 200);
        }

        $status = strtoupper((string) ($payload['status'] ?? ''));
        $metadata = $payload['metadata'] ?? [];
        $membershipId = (int) ($metadata['membership_id'] ?? 0);
        $method = (string) ($payload['payment_method'] ?? 'uddoktapay');
        $invoiceId = (string) ($payload['invoice_id'] ?? '');
        $amount = (float) ($payload['amount'] ?? 0);

        if ($status === 'COMPLETED' && $membershipId > 0) {
            $this->activateMembership($membershipId, $method, $trxId, $invoiceId, $amount);

            return response()->json(['status' => 'success', 'message' => 'Membership activated.'], 200);
        }

        return response()->json(['status' => 'ignored'], 200);
    }

    /**
     * Idempotently activate membership, record payment and ledger funds
     */
    protected function activateMembership(int $membershipId, string $method, string $trxId, ?string $invoiceId, float $amount): void
    {
        try {
            DB::transaction(function () use ($membershipId, $method, $trxId, $invoiceId, $amount) {
                // Duplicate check on transaction_id
                if (!empty($trxId)) {
                    $existingPayment = DB::table('membership_payments')
                        ->where('transaction_id', $trxId)
                        ->where('status', 'paid')
                        ->first();
                    if ($existingPayment) {
                        return;
                    }
                }

                $membership = DB::table('memberships')->where('id', $membershipId)->first();
                if (! $membership) {
                    return;
                }

                // 1. Update Membership status to active
                DB::table('memberships')->where('id', $membershipId)->update([
                    'status' => 'active',
                    'approved_at' => now(),
                    'updated_at' => now(),
                ]);

                // 2. Update or insert membership_payments
                $paymentExists = DB::table('membership_payments')->where('membership_id', $membershipId)->exists();
                if ($paymentExists) {
                    DB::table('membership_payments')->where('membership_id', $membershipId)->update([
                        'method' => $method,
                        'transaction_id' => $trxId,
                        'status' => 'paid',
                        'paid_at' => now(),
                        'updated_at' => now(),
                    ]);
                } else {
                    DB::table('membership_payments')->insert([
                        'membership_id' => $membershipId,
                        'amount' => $amount,
                        'currency' => 'BDT',
                        'method' => $method,
                        'transaction_id' => $trxId,
                        'status' => 'paid',
                        'paid_at' => now(),
                        'created_at' => now(),
                    ]);
                }

                // 3. Record in Association Funds (duplicate check via reference_no)
                $ref = 'MEM-'.$membershipId;
                $fundExists = DB::table('association_funds')->where('reference_no', $ref)->exists();

                $mDetail = DB::table('memberships as m')
                    ->join('membership_types as mt', 'mt.id', '=', 'm.membership_type_id')
                    ->join('alumni_profiles as ap', 'ap.id', '=', 'm.alumni_profile_id')
                    ->join('users as u', 'u.id', '=', 'ap.user_id')
                    ->select('m.*', 'mt.name as type_name', 'mt.fee', 'u.id as user_id', 'u.name as user_name')
                    ->where('m.id', $membershipId)
                    ->first();

                if (! $fundExists && $mDetail && (float) $mDetail->fee > 0) {
                    DB::table('association_funds')->insert([
                        'title' => 'মেম্বারশিপ ফি সংগ্রহ: '.$mDetail->user_name.' ('.$mDetail->type_name.')',
                        'source' => 'Membership Collection (UddoktaPay)',
                        'amount' => $amount > 0 ? $amount : (float) $mDetail->fee,
                        'fund_date' => now()->toDateString(),
                        'reference_no' => $ref,
                        'notes' => 'Online Payment via UddoktaPay ['.strtoupper($method).'] - Trx: '.$trxId.($invoiceId ? ' (Invoice: '.$invoiceId.')' : ''),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }

                // 4. Send notification to user
                if ($mDetail) {
                    $notifExists = DB::table('notifications')
                        ->where('user_id', $mDetail->user_id)
                        ->where('type', 'membership_approved')
                        ->whereDate('created_at', now()->toDateString())
                        ->exists();

                    if (! $notifExists) {
                        DB::table('notifications')->insert([
                            'user_id' => $mDetail->user_id,
                            'type' => 'membership_approved',
                            'title' => 'মেম্বারশিপ সক্রিয় হয়েছে',
                            'message' => "অভিনন্দন! আপনার {$mDetail->type_name} মেম্বারশিপটি অনলাইন পেমেন্টের (UddoktaPay) মাধ্যমে সফলভাবে সক্রিয় করা হয়েছে।",
                            'is_read' => 0,
                            'created_at' => now(),
                        ]);
                    }
                }
            }, 5);
        } catch (\Throwable $e) {
            Log::error('activateMembership failed', ['error' => $e->getMessage(), 'membership_id' => $membershipId]);
        }
    }
}
