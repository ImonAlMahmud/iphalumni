<?php
declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Services\UploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class NewsController extends BaseController
{
    private function slugify(string $text): string
    {
        return Str::slug($text) ?: 'news-' . time();
    }

    public function index(Request $request)
    {
        $news = DB::table('news')->whereNull('deleted_at')->orderBy('created_at', 'desc')->get()->map(fn($r) => (array)$r)->toArray();

        return $this->legacyView('admin/news/index', compact('news'), 'admin', 'News Management');
    }

    public function create(Request $request)
    {
        $news = null;
        return $this->legacyView('admin/news/form', compact('news'), 'admin', 'Create News');
    }

    public function store(Request $request)
    {
        $attachment = null;
        $file = $request->file('attachment_file');
        if ($file && $file->isValid()) {
            $uploader = new UploadService();
            $attachment = $uploader->uploadDocument($file, 'news_docs');
        }

        $category = trim((string)$request->input('category', 'news'));
        if (!in_array($category, ['news', 'press_release', 'notice', 'resolution'], true)) {
            $category = 'news';
        }

        $title = trim((string)$request->input('title', ''));
        $slug  = $this->slugify($title);

        $newsId = DB::table('news')->insertGetId([
            'title'           => $title,
            'category'        => $category,
            'slug'            => $slug,
            'content'         => $request->input('content', ''),
            'attachment_file' => $attachment,
            'status'          => $request->input('status', 'draft'),
            'published_at'    => $request->input('status') === 'published' ? now() : null,
            'author_id'       => Auth::id(),
            'created_at'      => now(),
            'updated_at'      => now(),
        ]);

        // Save Signatories
        $signUids = (array)$request->input('signatory_user_id', []);
        $signTitles = (array)$request->input('signatory_title', []);
        $order = 1;
        foreach ($signUids as $idx => $uid) {
            $uid = (int)$uid;
            if ($uid > 0) {
                $titleOverride = trim((string)($signTitles[$idx] ?? ''));
                DB::table('notice_signatories')->insert([
                    'news_id'           => $newsId,
                    'user_id'           => $uid,
                    'designation_title' => $titleOverride ?: null,
                    'sort_order'        => $order++,
                ]);
            }
        }

        return redirect('/admin/news')->with('success', 'Publication saved successfully.');
    }

    public function edit(Request $request, $id)
    {
        $id   = (int)$id;
        $news = DB::table('news')->where('id', $id)->whereNull('deleted_at')->first();
        if (!$news) {
            abort(404);
        }
        $news = (array)$news;

        return $this->legacyView('admin/news/form', compact('news'), 'admin', 'Edit News / Notice');
    }

    public function update(Request $request, $id)
    {
        $id = (int)$id;
        $oldNews = DB::table('news')->where('id', $id)->first();
        $attachment = $oldNews->attachment_file ?? null;

        $file = $request->file('attachment_file');
        if ($file && $file->isValid()) {
            $uploader = new UploadService();
            $attachment = $uploader->uploadDocument($file, 'news_docs');
        }

        $category = trim((string)$request->input('category', 'news'));
        if (!in_array($category, ['news', 'press_release', 'notice', 'resolution'], true)) {
            $category = 'news';
        }

        DB::table('news')->where('id', $id)->update([
            'title'           => $request->input('title'),
            'category'        => $category,
            'content'         => $request->input('content'),
            'attachment_file' => $attachment,
            'status'          => $request->input('status'),
            'updated_at'      => now(),
        ]);

        // Sync Signatories
        DB::table('notice_signatories')->where('news_id', $id)->delete();
        $signUids = (array)$request->input('signatory_user_id', []);
        $signTitles = (array)$request->input('signatory_title', []);
        $order = 1;
        foreach ($signUids as $idx => $uid) {
            $uid = (int)$uid;
            if ($uid > 0) {
                $titleOverride = trim((string)($signTitles[$idx] ?? ''));
                DB::table('notice_signatories')->insert([
                    'news_id'           => $id,
                    'user_id'           => $uid,
                    'designation_title' => $titleOverride ?: null,
                    'sort_order'        => $order++,
                ]);
            }
        }

        return redirect('/admin/news')->with('success', 'Publication updated successfully.');
    }

    public function delete(Request $request, $id)
    {
        $id = (int)$id;
        DB::table('news')->where('id', $id)->update(['deleted_at' => now()]);
        return redirect('/admin/news')->with('success', 'News deleted.');
    }
}
