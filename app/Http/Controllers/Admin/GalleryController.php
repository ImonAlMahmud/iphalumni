<?php
declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Services\UploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class GalleryController extends BaseController
{
    public function index(Request $request)
    {
        $albums = DB::table('gallery_albums as ga')
            ->select('ga.*', DB::raw('(SELECT COUNT(*) FROM gallery_photos gp WHERE gp.album_id = ga.id) as photo_count'))
            ->whereNull('ga.deleted_at')
            ->orderBy('ga.created_at', 'desc')
            ->get()
            ->map(fn($r) => (array)$r)
            ->toArray();

        return $this->legacyView('admin/gallery/index', compact('albums'), 'admin', 'Gallery Management');
    }

    public function createAlbum(Request $request)
    {
        return $this->legacyView('admin/gallery/create_album', [], 'admin', 'Create Album');
    }

    public function storeAlbum(Request $request)
    {
        DB::table('gallery_albums')->insert([
            'title'       => $request->input('title'),
            'description' => $request->input('description'),
            'album_date'  => $request->input('album_date'),
            'status'      => $request->input('status', 'draft'),
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);

        return redirect('/admin/gallery')->with('success', 'Album created.');
    }

    public function viewAlbum(Request $request, $id)
    {
        $id    = (int)$id;
        $album = DB::table('gallery_albums')->where('id', $id)->first();
        if (!$album) {
            abort(404);
        }
        $album = (array)$album;

        $photos = DB::table('gallery_photos')->where('album_id', $id)->orderBy('sort_order', 'asc')->get()->map(fn($r) => (array)$r)->toArray();

        return $this->legacyView('admin/gallery/view_album', compact('album', 'photos'), 'admin', 'Album: ' . e($album['title']));
    }

    public function uploadPhotos(Request $request, $id)
    {
        $albumId = (int)$id;
        $upload  = new UploadService();
        $files   = $request->file('photos');
        if (!$files) {
            return redirect('/admin/gallery/' . $albumId)->with('error', 'No photos uploaded.');
        }

        if (!is_array($files)) {
            $files = [$files];
        }

        $count = 0;
        foreach ($files as $file) {
            if ($file && $file->isValid()) {
                $filename = $upload->uploadGallery($file, $albumId);
                if ($filename) {
                    DB::table('gallery_photos')->insert([
                        'album_id'    => $albumId,
                        'filename'    => $filename,
                        'uploaded_by' => Auth::id(),
                        'created_at'  => now(),
                    ]);
                    $count++;
                }
            }
        }

        return redirect('/admin/gallery/' . $albumId)->with('success', "{$count} photo(s) uploaded.");
    }

    public function deletePhoto(Request $request, $photo_id)
    {
        $photoId = (int)$photo_id;
        $photo   = DB::table('gallery_photos')->where('id', $photoId)->first();
        if (!$photo) {
            abort(404);
        }

        if (Storage::disk('public')->exists("gallery/{$photo->album_id}/{$photo->filename}")) {
            Storage::disk('public')->delete("gallery/{$photo->album_id}/{$photo->filename}");
        }

        DB::table('gallery_photos')->where('id', $photoId)->delete();

        return redirect('/admin/gallery/' . $photo->album_id)->with('success', 'Photo deleted successfully.');
    }

    public function editAlbum(Request $request, $id)
    {
        $id    = (int)$id;
        $album = DB::table('gallery_albums')->where('id', $id)->whereNull('deleted_at')->first();
        if (!$album) {
            abort(404);
        }
        $album = (array)$album;

        return $this->legacyView('admin/gallery/edit_album', compact('album'), 'admin', 'Edit Album');
    }

    public function updateAlbum(Request $request, $id)
    {
        $id = (int)$id;
        DB::table('gallery_albums')->where('id', $id)->update([
            'title'       => $request->input('title'),
            'description' => $request->input('description'),
            'album_date'  => $request->input('album_date'),
            'status'      => $request->input('status'),
            'updated_at'  => now(),
        ]);

        return redirect('/admin/gallery')->with('success', 'Album updated successfully.');
    }

    public function deleteAlbum(Request $request, $id)
    {
        $id = (int)$id;
        DB::table('gallery_albums')->where('id', $id)->update(['deleted_at' => now()]);
        return redirect('/admin/gallery')->with('success', 'Album deleted successfully.');
    }
}
