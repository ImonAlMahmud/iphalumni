<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StoriesController extends BaseController
{
    public function index(Request $request)
    {
        $stories = DB::table('success_stories')
            ->where('status', 'published')
            ->whereNull('deleted_at')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(fn($r) => (array)$r)
            ->toArray();

        return $this->legacyView('stories/index', compact('stories'), 'main', 'Success Stories');
    }

    public function show(Request $request, $slug)
    {
        $story = DB::table('success_stories')
            ->where('slug', (string)$slug)
            ->where('status', 'published')
            ->whereNull('deleted_at')
            ->first();

        if (!$story) {
            abort(404);
        }
        $story = (array)$story;

        return $this->legacyView('stories/show', compact('story'), 'main', (string)$story['title']);
    }
}
