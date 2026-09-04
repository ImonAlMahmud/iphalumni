<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DonationController extends BaseController
{
    public function index(Request $request)
    {
        $eventId = (int)$request->input('event_id', 0);
        $event = null;
        if ($eventId > 0) {
            $event = DB::table('events')->where('id', $eventId)->whereNull('deleted_at')->first();
            $event = $event ? (array)$event : null;
        }

        return $this->legacyView('donation/index', compact('event'), 'main', 'Donate');
    }

    public function store(Request $request)
    {
        $name    = trim((string)$request->input('name', ''));
        $email   = trim((string)$request->input('email', ''));
        $amount  = (float)$request->input('amount', 0);
        $message = trim((string)$request->input('message', ''));
        $eventId = $request->input('event_id') ? (int)$request->input('event_id') : null;

        if ($amount < 10) {
            return redirect('/donate')->with('error', 'Minimum donation is ৳10.')->withInput();
        }

        try {
            DB::table('donations')->insert([
                'event_id'   => $eventId,
                'name'       => $name,
                'email'      => $email,
                'amount'     => $amount,
                'message'    => $message,
                'status'     => 'pending',
                'created_at' => now(),
            ]);
        } catch (\Exception $e) {}

        return redirect('/donate')->with('success', 'Thank you for your donation! We will contact you for payment details.');
    }
}
