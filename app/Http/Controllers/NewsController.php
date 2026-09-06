<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NewsController extends BaseController
{
    public function index(Request $request)
    {
        $page = max(1, (int)$request->input('page', 1));
        $news = (new News())->getPublished($page, 9);

        return $this->legacyView('news/index', compact('news'), 'main', 'News');
    }

    public function show(Request $request, $slug = null)
    {
        $param = $slug ?? $request->route('slug') ?? $request->route('id');

        if (is_numeric($param)) {
            $n = DB::table('news')->where('id', (int)$param)->whereNull('deleted_at')->first();
        } else {
            $n = DB::table('news')->where('slug', (string)$param)->whereNull('deleted_at')->first();
        }

        $isAdmin = Auth::check() && in_array(Auth::user()->role, ['super_admin', 'admin', 'editor']);
        if (!$n || ($n->status !== 'published' && !$isAdmin)) {
            abort(404);
        }

        $n = (array)$n;

        // Fetch signatories
        $signatories = DB::table('notice_signatories as ns')
            ->join('users as u', 'u.id', '=', 'ns.user_id')
            ->leftJoin('committee_members as cm', 'cm.user_id', '=', 'u.id')
            ->select('ns.designation_title', 'u.name', 'u.signature_image', 'cm.designation as default_designation')
            ->where('ns.news_id', $n['id'])
            ->orderBy('ns.sort_order', 'asc')
            ->limit(4)
            ->get()
            ->map(fn($r) => (array)$r)
            ->toArray();

        $refYear = date('Y', strtotime($n['published_at'] ?? $n['created_at']));
        $refNo = 'IPH-AA/NOT/' . $refYear . '/' . sprintf('%04d', $n['id']);
        $description = mb_strimwidth(strip_tags($n['content']), 0, 155, '…');
        $ogType = 'article';
        $title = (string)$n['title'];

        return $this->legacyView('news/show', compact('n', 'signatories', 'description', 'ogType', 'refNo'), 'main', $title);
    }

    public function showByRef(Request $request, $ref = null)
    {
        $refStr = $ref ?? $request->query('ref') ?? $request->route('ref') ?? '';
        $refStr = trim((string)$refStr);

        if (empty($refStr)) {
            return redirect('/news');
        }

        // Try to extract notice ID from the Ref string
        // Supported formats: IPH-AA/NOT/2026/0003, IPH-AA-NOT-2026-0003, 0003, 3
        $id = null;
        if (preg_match('/(?:IPH-AA[\/-]NOT[\/-]\d{4}[\/-])?0*(\d+)$/i', $refStr, $matches)) {
            $id = (int)$matches[1];
        } elseif (is_numeric($refStr)) {
            $id = (int)$refStr;
        }

        $n = null;
        if ($id) {
            $n = DB::table('news')->where('id', $id)->whereNull('deleted_at')->first();
        }

        if (!$n) {
            $n = DB::table('news')->where('slug', $refStr)->whereNull('deleted_at')->first();
        }

        $isAdmin = Auth::check() && in_array(Auth::user()->role, ['super_admin', 'admin', 'editor']);
        if (!$n || ($n->status !== 'published' && !$isAdmin)) {
            abort(404, 'Official Notice record not found.');
        }

        $n = (array)$n;

        // Fetch signatories
        $signatories = DB::table('notice_signatories as ns')
            ->join('users as u', 'u.id', '=', 'ns.user_id')
            ->leftJoin('committee_members as cm', 'cm.user_id', '=', 'u.id')
            ->select('ns.designation_title', 'u.name', 'u.signature_image', 'cm.designation as default_designation')
            ->where('ns.news_id', $n['id'])
            ->orderBy('ns.sort_order', 'asc')
            ->limit(4)
            ->get()
            ->map(fn($r) => (array)$r)
            ->toArray();

        $refYear = date('Y', strtotime($n['published_at'] ?? $n['created_at']));
        $refNo = 'IPH-AA/NOT/' . $refYear . '/' . sprintf('%04d', $n['id']);
        $isVerifiedNotice = true;
        $description = mb_strimwidth(strip_tags($n['content']), 0, 155, '…');
        $ogType = 'article';
        $title = 'Official Notice: ' . $n['title'];

        return $this->legacyView('news/show', compact('n', 'signatories', 'description', 'ogType', 'refNo', 'isVerifiedNotice'), 'main', $title);
    }

    public function printPdf(Request $request, $id)
    {
        $id = (int)$id;
        $n = DB::table('news')->where('id', $id)->whereNull('deleted_at')->first();
        if (!$n) {
            abort(404);
        }
        $n = (array)$n;

        $signatories = DB::table('notice_signatories as ns')
            ->join('users as u', 'u.id', '=', 'ns.user_id')
            ->leftJoin('committee_members as cm', 'cm.user_id', '=', 'u.id')
            ->select('ns.designation_title', 'u.name', 'u.signature_image', 'cm.designation as default_designation')
            ->where('ns.news_id', $n['id'])
            ->orderBy('ns.sort_order', 'asc')
            ->limit(4)
            ->get()
            ->map(fn($r) => (array)$r)
            ->toArray();

        $refYear = date('Y', strtotime($n['published_at'] ?? $n['created_at']));
        $refId   = sprintf('%04d', $n['id']);
        $refNo   = "IPH-AA/NOT/{$refYear}/{$refId}";

        // QR code URL strictly based on the official REF No
        $noticeUrl = url('/notice/' . $refNo);
        $qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&margin=0&data=' . urlencode($noticeUrl);

        $siteSettings = [];
        try {
            $settingsRows = DB::table('settings')->get();
            foreach ($settingsRows as $sr) {
                $siteSettings[$sr->key] = $sr->value;
            }
        } catch (\Exception $e) {}

        extract(compact('n', 'signatories', 'noticeUrl', 'qrUrl', 'siteSettings', 'refNo'));
        $viewFile = resource_path('views/news/print_notice.php');
        if (file_exists($viewFile)) {
            ob_start();
            require $viewFile;
            return response(ob_get_clean());
        }
        abort(404);
    }
}
