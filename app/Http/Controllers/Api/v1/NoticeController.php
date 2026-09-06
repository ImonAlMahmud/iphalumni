<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NoticeController extends BaseApiController
{
    /**
     * List Published Notices & Circulars
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = min(50, max(1, (int)$request->input('per_page', 10)));
        $search = trim((string)$request->input('search', ''));

        $query = DB::table('news')
            ->where('status', 'published')
            ->whereNull('deleted_at');

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        $paginator = $query->orderBy('published_at', 'desc')
            ->orderBy('id', 'desc')
            ->paginate($perPage);

        $items = collect($paginator->items())->map(function ($n) {
            $refYear = date('Y', strtotime($n->published_at ?? $n->created_at));
            $refNo = 'IPH-AA/NOT/' . $refYear . '/' . sprintf('%04d', $n->id);

            return [
                'id'              => $n->id,
                'ref_no'          => $refNo,
                'title'           => $n->title,
                'slug'            => $n->slug,
                'category'        => $n->category ?? 'General Notice',
                'excerpt'         => $n->excerpt ?: mb_strimwidth(strip_tags($n->content), 0, 160, '…'),
                'cover_image_url' => $n->cover_image ? url($n->cover_image) : null,
                'published_at'    => $n->published_at,
                'share_url'       => url('/news/' . $n->slug),
            ];
        });

        return $this->successResponse($items, 'Notices retrieved successfully.', 200, [
            'current_page' => $paginator->currentPage(),
            'per_page'     => $paginator->perPage(),
            'total'        => $paginator->total(),
            'last_page'    => $paginator->lastPage(),
        ]);
    }

    /**
     * Get Single Notice with Signatories & Verification
     */
    public function show(Request $request, string $idOrRef): JsonResponse
    {
        $id = null;

        // Check if format is IPH-AA/NOT/YYYY/0001 or IPH-AA-NOT-YYYY-0001
        if (preg_match('/(?:IPH-AA[\/-]NOT[\/-]\d{4}[\/-])(\d+)/i', $idOrRef, $matches)) {
            $id = (int)$matches[1];
        } elseif (is_numeric($idOrRef)) {
            $id = (int)$idOrRef;
        }

        if ($id) {
            $n = DB::table('news')->where('id', $id)->whereNull('deleted_at')->first();
        } else {
            $n = DB::table('news')->where('slug', $idOrRef)->whereNull('deleted_at')->first();
        }

        if (!$n || $n->status !== 'published') {
            return $this->errorResponse('Notice not found or unpublished.', 404, null, 'NOTICE_NOT_FOUND');
        }

        $signatories = DB::table('notice_signatories as ns')
            ->join('users as u', 'u.id', '=', 'ns.user_id')
            ->leftJoin('committee_members as cm', 'cm.user_id', '=', 'u.id')
            ->select('ns.designation_title', 'u.name', 'u.signature_image', 'cm.designation as default_designation')
            ->where('ns.news_id', $n->id)
            ->orderBy('ns.sort_order', 'asc')
            ->get()
            ->map(function ($s) {
                return [
                    'name'            => $s->name,
                    'designation'     => $s->designation_title ?: $s->default_designation,
                    'signature_image' => $s->signature_image ? url($s->signature_image) : null,
                ];
            });

        $refYear = date('Y', strtotime($n->published_at ?? $n->created_at));
        $refNo = 'IPH-AA/NOT/' . $refYear . '/' . sprintf('%04d', $n->id);

        return $this->successResponse([
            'id'              => $n->id,
            'ref_no'          => $refNo,
            'title'           => $n->title,
            'slug'            => $n->slug,
            'category'        => $n->category ?? 'General Notice',
            'excerpt'         => $n->excerpt,
            'content_html'    => $n->content,
            'cover_image_url' => $n->cover_image ? url($n->cover_image) : null,
            'attachment_url'  => $n->attachment_file ? url($n->attachment_file) : null,
            'published_at'    => $n->published_at,
            'created_at'      => $n->created_at,
            'signatories'     => $signatories,
            'share_url'       => url('/news/' . $n->slug),
            'qr_ref_url'      => url('/notice/ref/' . str_replace('/', '-', $refNo)),
        ], 'Notice details retrieved.');
    }
}
