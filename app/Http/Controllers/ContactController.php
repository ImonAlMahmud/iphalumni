<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ContactController extends BaseController
{
    public function index(Request $request)
    {
        return $this->legacyView('contact/index', [], 'main', 'Contact Us');
    }

    public function send(Request $request)
    {
        $name    = trim((string)$request->input('name', ''));
        $email   = trim((string)$request->input('email', ''));
        $subject = trim((string)$request->input('subject', ''));
        $message = trim((string)$request->input('message', ''));

        if (!$name || !$email || !$message) {
            return redirect('/contact')->with('error', 'Name, email and message are required.');
        }

        try {
            DB::table('contact_messages')->insert([
                'name'       => $name,
                'email'      => $email,
                'subject'    => $subject,
                'message'    => $message,
                'created_at' => now(),
            ]);
        } catch (\Exception $e) {}

        return redirect('/contact')->with('success', 'Message sent! We will respond within 2 business days.');
    }
}
