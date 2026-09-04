<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Event;
use App\Services\MailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EventController extends BaseController
{
    public function index(Request $request)
    {
        $model    = new Event();
        $upcoming = $model->getUpcoming(12);
        $past     = $model->getPast(6);

        return $this->legacyView('events/index', compact('upcoming', 'past'), 'main', 'Events');
    }

    public function show(Request $request, $slug)
    {
        $event = (new Event())->findBySlug((string)$slug);
        if (!$event) {
            abort(404);
        }

        $raisedAmount = (float) DB::table('donations')
            ->where('event_id', $event['id'])
            ->where('status', 'completed')
            ->sum('amount');

        $description = mb_strimwidth(strip_tags($event['description'] ?? ''), 0, 155, '…');
        $ogType = 'event';
        $title = (string)$event['title'];

        return $this->legacyView('events/show', compact('event', 'raisedAmount', 'description', 'ogType'), 'main', $title);
    }

    public function register(Request $request, $slug)
    {
        $event = (new Event())->findBySlug((string)$slug);
        if (!$event) {
            abort(404);
        }

        $user = Auth::user();
        if (!$user) {
            return redirect('/login')->with('error', 'Please log in to register.');
        }

        // Eligibility Check based on allowed_roles
        $allowed = $event['allowed_roles'] ?? 'all';
        if ($allowed === 'verified_alumni') {
            $isVerified = ($user->status === 'verified' || $user->status === 'approved' || in_array($user->role, ['super_admin', 'admin']));
            if (!$isVerified) {
                return redirect('/events/' . $slug)->with('error', 'এই ইভেন্টে শুধুমাত্র ভেরিফাইড অ্যালামনাই মেম্বাররা অংশ নিতে পারবেন।');
            }
        } elseif ($allowed === 'students') {
            $isStudent = ($user->role === 'student');
            if (!$isStudent) {
                return redirect('/events/' . $slug)->with('error', 'এই ইভেন্টে শুধুমাত্র রানিং স্টুডেন্টরা অংশ নিতে পারবেন।');
            }
        }

        // Check duplicate registration
        $already = DB::table('event_registrations')
            ->where('event_id', $event['id'])
            ->where('user_id', $user->id)
            ->exists();

        if ($already) {
            return redirect('/events/' . $slug)->with('error', 'আপনি ইতিমধ্যেই এই ইভেন্টের নিবন্ধনের রেসপন্স সাবমিট করেছেন।');
        }

        $regType  = $event['registration_type'] ?? 'free';
        $fee      = (float)($event['ticket_fee'] ?? 0);
        $passCode = 'IPH-EVT-' . strtoupper(substr(md5(uniqid((string)$user->id, true)), 0, 8));

        if ($regType === 'paid' && $fee > 0) {
            DB::table('event_registrations')->insert([
                'event_id'       => $event['id'],
                'user_id'        => $user->id,
                'pass_code'      => $passCode,
                'payment_status' => 'paid',
                'amount'         => $fee,
                'created_at'     => now(),
            ]);
            $payStatus = 'paid';
            $successMsg = 'পেমেন্ট সম্পন্ন হয়েছে এবং আপনার Event Pass টি ইমেইলে পাঠিয়ে দেওয়া হয়েছে!';
        } else {
            DB::table('event_registrations')->insert([
                'event_id'       => $event['id'],
                'user_id'        => $user->id,
                'pass_code'      => $passCode,
                'payment_status' => 'free',
                'amount'         => 0.00,
                'created_at'     => now(),
            ]);
            $payStatus = 'free';
            $successMsg = 'আপনার ইভেন্ট রেসপন্স সফলভাবে গৃহীত হয়েছে এবং Event Pass টি ইমেইলে পাঠিয়ে দেওয়া হয়েছে!';
        }

        // Send Email with Event Pass
        $this->sendEventPassEmail($user->toArray(), $event, $passCode, $payStatus, $fee);

        return redirect('/events/' . $slug)->with('success', $successMsg);
    }

    private function sendEventPassEmail(array $user, array $event, string $passCode, string $payStatus, float $fee): void
    {
        $mail = new MailService();
        $to = $user['email'];
        $subject = '🎟️ Your Event Pass: ' . $event['title'] . ' — IPH Alumni';

        $eventDate = date('d F Y, h:i A', strtotime($event['event_date']));
        $venue     = e($event['venue'] ?? 'TBA');
        $userName  = e($user['name'] ?? 'Member');
        $evtTitle  = e($event['title']);
        $typeBadge = $payStatus === 'paid' ? 'PAID TICKET PASS (৳' . number_format($fee) . ')' : 'FREE ENTRY PASS';

        $html = "
        <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #e5e7eb; background-color: #ffffff;'>
            <div style='text-align: center; padding-bottom: 20px; border-bottom: 2px solid #800020;'>
                <h2 style='color: #800020; margin: 0; font-size: 22px;'>IPH ALUMNI ASSOCIATION</h2>
                <p style='color: #6b7280; font-size: 13px; margin-top: 4px;'>Official Event Entry Pass</p>
            </div>
            <div style='padding: 24px 0;'>
                <p style='font-size: 15px; color: #111827;'>Dear <strong>{$userName}</strong>,</p>
                <p style='font-size: 14px; color: #4b5563;'>
                    Thank you for responding to <strong>{$evtTitle}</strong>. Your entry pass has been generated successfully. Please show this email or pass code at the event venue entrance.
                </p>
                <div style='background-color: #f9fafb; border: 2px dashed #800020; border-radius: 12px; padding: 20px; text-align: center; margin: 24px 0;'>
                    <div style='display: inline-block; font-size: 11px; font-weight: bold; color: #800020; background: #fef2f2; padding: 4px 12px; border-radius: 20px; margin-bottom: 8px;'>
                        {$typeBadge}
                    </div>
                    <div style='font-family: monospace; font-size: 24px; font-weight: bold; color: #111827; letter-spacing: 2px;'>
                        {$passCode}
                    </div>
                    <p style='font-size: 12px; color: #6b7280; margin-top: 6px;'>UNIQUE VERIFICATION PASS CODE</p>
                </div>
                <table style='width: 100%; font-size: 14px; color: #374151; border-collapse: collapse;'>
                    <tr>
                        <td style='padding: 8px 0; color: #6b7280; width: 120px;'><strong>Event Name:</strong></td>
                        <td style='padding: 8px 0; font-weight: bold;'>{$evtTitle}</td>
                    </tr>
                    <tr>
                        <td style='padding: 8px 0; color: #6b7280;'><strong>Date & Time:</strong></td>
                        <td style='padding: 8px 0;'>{$eventDate}</td>
                    </tr>
                    <tr>
                        <td style='padding: 8px 0; color: #6b7280;'><strong>Venue:</strong></td>
                        <td style='padding: 8px 0;'>{$venue}</td>
                    </tr>
                </table>
            </div>
            <div style='text-align: center; padding-top: 20px; border-top: 1px solid #e5e7eb; font-size: 12px; color: #9ca3af;'>
                &copy; " . date('Y') . " IPH Alumni Association.
            </div>
        </div>";

        try {
            $mail->send($to, $subject, $html);
        } catch (\Throwable $t) {
            error_log("Event Pass mail error: " . $t->getMessage());
        }
    }
}
