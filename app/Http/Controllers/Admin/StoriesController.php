<?php
declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Services\MailService;
use App\Services\UploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class StoriesController extends BaseController
{
    private function slugify(string $text): string
    {
        return Str::slug($text) ?: 'story-' . time();
    }

    public function index(Request $request)
    {
        $stories = DB::table('success_stories')->whereNull('deleted_at')->orderBy('created_at', 'desc')->get()->map(fn($r) => (array)$r)->toArray();

        return $this->legacyView('admin/stories/index', compact('stories'), 'admin', 'Success Stories');
    }

    public function create(Request $request)
    {
        $story = null;
        return $this->legacyView('admin/stories/form', compact('story'), 'admin', 'Create Success Story');
    }

    public function store(Request $request)
    {
        $title       = trim((string)$request->input('title', ''));
        $slug        = $this->slugify($title);
        $batch_year  = trim((string)$request->input('batch_year', ''));
        $excerpt     = trim((string)$request->input('excerpt', ''));
        $content     = trim((string)$request->input('content', ''));
        $status      = $request->input('status', 'draft');
        $is_featured = (int)$request->input('is_featured', 0);

        $cover_image = null;
        $file = $request->file('cover_image');
        if ($file && $file->isValid()) {
            $upload = new UploadService();
            $cover_image = $upload->uploadStoryImage($file);
        }

        DB::table('success_stories')->insert([
            'title'       => $title,
            'slug'        => $slug,
            'batch_year'  => $batch_year,
            'excerpt'     => $excerpt,
            'content'     => $content,
            'cover_image' => $cover_image,
            'status'      => $status,
            'is_featured' => $is_featured,
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);

        return redirect('/admin/stories')->with('success', 'Success story created.');
    }

    public function edit(Request $request, $id)
    {
        $id    = (int)$id;
        $story = DB::table('success_stories')->where('id', $id)->whereNull('deleted_at')->first();
        if (!$story) {
            abort(404);
        }
        $story = (array)$story;

        return $this->legacyView('admin/stories/form', compact('story'), 'admin', 'Edit Success Story');
    }

    public function update(Request $request, $id)
    {
        $id = (int)$id;
        $oldStory = DB::table('success_stories')->where('id', $id)->first();
        $cover_image = $oldStory->cover_image ?? null;

        $file = $request->file('cover_image');
        if ($file && $file->isValid()) {
            $upload = new UploadService();
            $cover_image = $upload->uploadStoryImage($file);
        }

        $title       = trim((string)$request->input('title', ''));
        $slug        = $this->slugify($title);
        $batch_year  = trim((string)$request->input('batch_year', ''));
        $excerpt     = trim((string)$request->input('excerpt', ''));
        $content     = trim((string)$request->input('content', ''));
        $status      = $request->input('status', 'draft');
        $is_featured = (int)$request->input('is_featured', 0);

        DB::table('success_stories')->where('id', $id)->update([
            'title'       => $title,
            'slug'        => $slug,
            'batch_year'  => $batch_year,
            'excerpt'     => $excerpt,
            'content'     => $content,
            'cover_image' => $cover_image,
            'status'      => $status,
            'is_featured' => $is_featured,
            'updated_at'  => now(),
        ]);

        return redirect('/admin/stories')->with('success', 'Success story updated.');
    }

    public function delete(Request $request, $id)
    {
        $id = (int)$id;
        DB::table('success_stories')->where('id', $id)->update(['deleted_at' => now()]);
        return redirect('/admin/stories')->with('success', 'Success story deleted.');
    }

    public function approve(Request $request, $id)
    {
        $id    = (int)$id;
        $story = DB::table('success_stories')->where('id', $id)->whereNull('deleted_at')->first();
        if (!$story) {
            return redirect('/admin/stories')->with('error', 'Story not found.');
        }
        $story = (array)$story;

        DB::table('success_stories')->where('id', $id)->update(['status' => 'published', 'updated_at' => now()]);

        // Send Email Alert to all verified alumni
        $alumniUsers = DB::table('users')->where('status', 'active')->get();
        $mailService = new MailService();
        $blogTitle   = $story['title'];
        $excerpt     = $story['excerpt'] ?: mb_substr(strip_tags((string)$story['content']), 0, 150) . '...';
        $storyUrl    = url('/stories/' . $story['slug']);

        $sentCount = 0;
        foreach ($alumniUsers as $alumni) {
            if (!empty($alumni->email)) {
                $htmlBody = '<p>প্রিয় ' . e($alumni->name) . ',</p>' .
                            '<p>আইপিএইচ অ্যালামনাই অ্যাসোসিয়েশন পোর্টাল-এ একটি নতুন ব্লগ পোস্ট প্রকাশিত হয়েছে:</p>' .
                            '<blockquote style="border-left:4px solid #800020;padding-left:12px;margin:16px 0;color:#555;">' .
                            '<strong>' . e($blogTitle) . '</strong><br>' . e($excerpt) .
                            '</blockquote><p><a href="' . $storyUrl . '">ব্লগ পোস্টটি পড়ুন</a></p>';

                $mailService->send($alumni->email, '[IPH Alumni Blog Alert] ' . $blogTitle, $htmlBody);
                $sentCount++;
            }

            DB::table('notifications')->insert([
                'user_id'    => $alumni->id,
                'title'      => 'নতুন ব্লগ: ' . mb_substr($blogTitle, 0, 40),
                'message'    => 'নতুন একটি ব্লগ পোস্ট প্রকাশিত হয়েছে: ' . $blogTitle,
                'link'       => '/stories/' . $story['slug'],
                'is_read'    => 0,
                'created_at' => now(),
            ]);
        }

        return redirect('/admin/stories')->with('success', "ব্লগ পোস্টটি সফলভাবে অনুমোদন (Approve) করা হয়েছে এবং {$sentCount} জন অ্যালামনাই সদস্যের ইমেইলে অ্যালার্ট পাঠানো হয়েছে!");
    }

    public function reject(Request $request, $id)
    {
        $id = (int)$id;
        DB::table('success_stories')->where('id', $id)->update(['status' => 'rejected', 'updated_at' => now()]);
        return redirect('/admin/stories')->with('success', 'ব্লগ পোস্টটি প্রত্যাখ্যান করা হয়েছে।');
    }

    public function preview(Request $request, $id)
    {
        $id = (int)$id;
        $story = DB::table('success_stories as s')
            ->leftJoin('alumni_profiles as ap', 'ap.id', '=', 's.profile_id')
            ->leftJoin('users as u', 'u.id', '=', 'ap.user_id')
            ->select('s.*', 'u.name as author_name', 'u.email as author_email')
            ->where('s.id', $id)
            ->whereNull('s.deleted_at')
            ->first();

        if (!$story) {
            return redirect('/admin/stories')->with('error', 'Story not found.');
        }
        $story = (array)$story;

        return $this->legacyView('admin/stories/preview', compact('story'), 'admin', 'Story Preview: ' . $story['title']);
    }

    public function toggleFeatured(Request $request, $id)
    {
        $id = (int)$id;
        $curr = (int) DB::table('success_stories')->where('id', $id)->value('is_featured');
        $newVal = $curr ? 0 : 1;

        DB::table('success_stories')->where('id', $id)->update(['is_featured' => $newVal, 'updated_at' => now()]);

        $msg = $newVal ? 'ব্লগটি সফলভাবে Featured করা হয়েছে!' : 'ব্লগটি Featured তালিকা থেকে বাদ দেওয়া হয়েছে।';
        return back()->with('success', $msg);
    }
}
