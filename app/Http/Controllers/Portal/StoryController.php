<?php
declare(strict_types=1);

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\BaseController;
use App\Models\AlumniProfile;
use App\Services\UploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class StoryController extends BaseController
{
    private function slugify(string $text): string
    {
        return Str::slug($text) ?: 'story-' . time();
    }

    public function index(Request $request)
    {
        $user    = Auth::user();
        $profile = (new AlumniProfile())->getByUserId((int)$user->id);
        $profileId = $profile ? (int)$profile['id'] : 0;

        $stories = DB::table('success_stories')
            ->where('profile_id', $profileId)
            ->whereNull('deleted_at')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(fn($r) => (array)$r)
            ->toArray();

        return $this->legacyView('portal/stories/index', compact('stories'), 'portal', 'My Blogs');
    }

    public function create(Request $request)
    {
        $user    = Auth::user();
        $profile = (new AlumniProfile())->getByUserId((int)$user->id);

        $status = $profile['status'] ?? '';
        if (!$profile || !in_array($status, ['verified', 'approved', 'active'])) {
            return redirect('/portal/stories')->with('error', 'কেবলমাত্র ভেরিফাইড অ্যালামনাই সদস্যগণ ব্লগ পোস্ট লিখতে পারেন।');
        }

        return $this->legacyView('portal/stories/create', compact('profile'), 'portal', 'Write a Blog Post');
    }

    public function store(Request $request)
    {
        $user    = Auth::user();
        $profile = (new AlumniProfile())->getByUserId((int)$user->id);

        $status = $profile['status'] ?? '';
        if (!$profile || !in_array($status, ['verified', 'approved', 'active'])) {
            return redirect('/portal/stories')->with('error', 'কেবলমাত্র ভেরিফাইড অ্যালামনাই সদস্যগণ ব্লগ পোস্ট লিখতে পারেন।');
        }

        $title      = trim((string)$request->input('title', ''));
        $batch_year = trim((string)($request->input('batch_year') ?: ($profile['batch_year'] ?? '')));
        $excerpt    = trim((string)$request->input('excerpt', ''));
        $content    = trim((string)$request->input('content', ''));

        if (empty($title) || empty($content)) {
            return redirect('/portal/stories/create')->with('error', 'ব্লগের শিরোনাম এবং বিস্তারিত বিষয়বস্তু আবশ্যক।')->withInput();
        }

        $slug = $this->slugify($title) . '-' . rand(100, 999);

        $cover_image = null;
        $file = $request->file('cover_image');
        if ($file && $file->isValid()) {
            $upload = new UploadService();
            $cover_image = $upload->uploadStoryImage($file);
        }

        DB::table('success_stories')->insert([
            'profile_id'  => $profile['id'],
            'title'       => $title,
            'slug'        => $slug,
            'batch_year'  => $batch_year,
            'excerpt'     => $excerpt,
            'content'     => $content,
            'cover_image' => $cover_image,
            'status'      => 'pending',
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);

        return redirect('/portal/stories')->with('success', 'আপনার ব্লগ পোস্টটি জমাদান সফল হয়েছে! এডমিন পর্যবেক্ষণ করে অনুমোদন (Approve) করলে এটি প্রকাশিত হবে।');
    }

    public function edit(Request $request, $id)
    {
        $id      = (int)$id;
        $user    = Auth::user();
        $profile = (new AlumniProfile())->getByUserId((int)$user->id);

        $story = DB::table('success_stories')
            ->where('id', $id)
            ->where('profile_id', $profile['id'] ?? 0)
            ->whereNull('deleted_at')
            ->first();

        if (!$story) {
            return redirect('/portal/stories')->with('error', 'ব্লগটি খুঁজে পাওয়া যায়নি অথবা আপনার এই ব্লগটি এডিট করার অনুমতি নেই।');
        }
        $story = (array)$story;

        return $this->legacyView('portal/stories/edit', compact('story'), 'portal', 'Edit Blog Post');
    }

    public function update(Request $request, $id)
    {
        $id      = (int)$id;
        $user    = Auth::user();
        $profile = (new AlumniProfile())->getByUserId((int)$user->id);

        $story = DB::table('success_stories')
            ->where('id', $id)
            ->where('profile_id', $profile['id'] ?? 0)
            ->whereNull('deleted_at')
            ->first();

        if (!$story) {
            return redirect('/portal/stories')->with('error', 'ব্লগটি খুঁজে পাওয়া যায়নি অথবা আপনার এই ব্লগটি সংশোধন করার অনুমতি নেই।');
        }
        $story = (array)$story;

        $title      = trim((string)$request->input('title', ''));
        $batch_year = trim((string)($request->input('batch_year') ?: $story['batch_year']));
        $excerpt    = trim((string)$request->input('excerpt', ''));
        $content    = trim((string)$request->input('content', ''));

        if (empty($title) || empty($content)) {
            return back()->with('error', 'ব্লগের শিরোনাম এবং বিস্তারিত বিষয়বস্তু আবশ্যক।');
        }

        $cover_image = $story['cover_image'];
        $file = $request->file('cover_image');
        if ($file && $file->isValid()) {
            $upload = new UploadService();
            $cover_image = $upload->uploadStoryImage($file);
        }

        DB::table('success_stories')->where('id', $id)->update([
            'title'       => $title,
            'batch_year'  => $batch_year,
            'excerpt'     => $excerpt,
            'content'     => $content,
            'cover_image' => $cover_image,
            'updated_at'  => now(),
        ]);

        return redirect('/portal/stories')->with('success', 'ব্লগ পোস্টের সংশোধন সফলভাবে সংরক্ষিত হয়েছে!');
    }

    public function delete(Request $request, $id)
    {
        $id      = (int)$id;
        $user    = Auth::user();
        $profile = (new AlumniProfile())->getByUserId((int)$user->id);

        $story = DB::table('success_stories')
            ->where('id', $id)
            ->where('profile_id', $profile['id'] ?? 0)
            ->whereNull('deleted_at')
            ->first();

        if (!$story) {
            return redirect('/portal/stories')->with('error', 'ব্লগটি খুঁজে পাওয়া যায়নি অথবা আপনার এই ব্লগটি মুছে ফেলার অনুমতি নেই।');
        }

        DB::table('success_stories')->where('id', $id)->update([
            'deleted_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect('/portal/stories')->with('success', 'আপনার ব্লগ পোস্টটি সফলভাবে মুছে ফেলা হয়েছে।');
    }
}
