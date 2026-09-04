<?php
declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Services\UploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EventController extends BaseController
{
    private function slugify(string $text): string
    {
        return Str::slug($text) ?: 'event-' . time();
    }

    public function index(Request $request)
    {
        $events = DB::table('events')->whereNull('deleted_at')->orderBy('event_date', 'desc')->get()->map(fn($r) => (array)$r)->toArray();

        return $this->legacyView('admin/events/index', compact('events'), 'admin', 'Events Management');
    }

    public function create(Request $request)
    {
        $event = null;
        return $this->legacyView('admin/events/form', compact('event'), 'admin', 'Create Event');
    }

    public function store(Request $request)
    {
        $isCrowd = (int)$request->input('is_crowdfunding', 0);
        $goal = $isCrowd ? (float)$request->input('crowdfunding_goal') : null;

        $regType = trim((string)$request->input('registration_type', 'free'));
        $fee     = $regType === 'paid' ? (float)$request->input('ticket_fee', 0) : 0.00;
        $roles   = trim((string)$request->input('allowed_roles', 'all'));

        $coverImage = null;
        $file = $request->file('cover_image');
        if ($file && $file->isValid()) {
            $uploader = new UploadService();
            $coverImage = $uploader->uploadStoryImage($file);
        }

        DB::table('events')->insert([
            'title'             => $request->input('title'),
            'slug'              => $this->slugify((string)$request->input('title', '')),
            'description'       => $request->input('description'),
            'cover_image'       => $coverImage,
            'venue'             => $request->input('venue'),
            'event_date'        => $request->input('event_date'),
            'status'            => $request->input('status', 'draft'),
            'registration_type' => $regType,
            'ticket_fee'        => $fee,
            'allowed_roles'     => $roles,
            'max_attendees'     => (int)$request->input('max_attendees', 0),
            'is_crowdfunding'   => $isCrowd,
            'crowdfunding_goal' => $goal,
            'created_at'        => now(),
            'updated_at'        => now(),
        ]);

        return redirect('/admin/events')->with('success', 'Event created.');
    }

    public function edit(Request $request, $id)
    {
        $id    = (int)$id;
        $event = DB::table('events')->where('id', $id)->whereNull('deleted_at')->first();
        if (!$event) {
            abort(404);
        }
        $event = (array)$event;

        return $this->legacyView('admin/events/form', compact('event'), 'admin', 'Edit Event');
    }

    public function update(Request $request, $id)
    {
        $id = (int)$id;
        $oldEvent = DB::table('events')->where('id', $id)->first();
        $coverImage = $oldEvent->cover_image ?? null;

        $file = $request->file('cover_image');
        if ($file && $file->isValid()) {
            $uploader = new UploadService();
            $coverImage = $uploader->uploadStoryImage($file);
        }

        $isCrowd = (int)$request->input('is_crowdfunding', 0);
        $goal = $isCrowd ? (float)$request->input('crowdfunding_goal') : null;

        $regType = trim((string)$request->input('registration_type', 'free'));
        $fee     = $regType === 'paid' ? (float)$request->input('ticket_fee', 0) : 0.00;
        $roles   = trim((string)$request->input('allowed_roles', 'all'));

        DB::table('events')->where('id', $id)->update([
            'title'             => $request->input('title'),
            'description'       => $request->input('description'),
            'cover_image'       => $coverImage,
            'venue'             => $request->input('venue'),
            'event_date'        => $request->input('event_date'),
            'status'            => $request->input('status'),
            'registration_type' => $regType,
            'ticket_fee'        => $fee,
            'allowed_roles'     => $roles,
            'max_attendees'     => (int)$request->input('max_attendees', 0),
            'is_crowdfunding'   => $isCrowd,
            'crowdfunding_goal' => $goal,
            'updated_at'        => now(),
        ]);

        return redirect('/admin/events')->with('success', 'Event updated.');
    }

    public function delete(Request $request, $id)
    {
        $id = (int)$id;
        DB::table('events')->where('id', $id)->update(['deleted_at' => now()]);
        return redirect('/admin/events')->with('success', 'Event deleted.');
    }

    public function financials(Request $request, $id)
    {
        $id = (int)$id;
        $event = DB::table('events')->where('id', $id)->whereNull('deleted_at')->first();
        if (!$event) {
            abort(404);
        }
        $event = (array)$event;

        $registrationsCount = DB::table('event_registrations')->where('event_id', $id)->count();
        $registrationsRevenue = $registrationsCount * (float)($event['ticket_fee'] ?? 0);

        $donationsRevenue = (float) DB::table('donations')->where('event_id', $id)->where('status', 'completed')->sum('amount');

        $expenses = DB::table('event_expenses')->where('event_id', $id)->orderBy('spent_at', 'desc')->get()->map(fn($r) => (array)$r)->toArray();
        $totalExpenses = array_sum(array_column($expenses, 'amount'));

        $netBalance = ($registrationsRevenue + $donationsRevenue) - $totalExpenses;

        return $this->legacyView(
            'admin/events/financials',
            compact('event', 'registrationsCount', 'registrationsRevenue', 'donationsRevenue', 'expenses', 'totalExpenses', 'netBalance'),
            'admin',
            'Event Financials - ' . $event['title']
        );
    }

    public function storeExpense(Request $request, $id)
    {
        $id = (int)$id;

        DB::table('event_expenses')->insert([
            'event_id'    => $id,
            'title'       => trim((string)$request->input('title', '')),
            'amount'      => (float)$request->input('amount', 0),
            'description' => trim((string)$request->input('description', '')),
            'spent_at'    => $request->input('spent_at') ?: date('Y-m-d'),
        ]);

        return redirect('/admin/events/' . $id . '/financials')->with('success', 'Expense recorded successfully.');
    }
}
