<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Gallery;
use App\Services\UploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GalleryController extends BaseController
{
    public function index(Request $request)
    {
        $albums = (new Gallery())->getAlbumsWithCover();
        foreach ($albums as $key => $album) {
            $photos = DB::table('gallery_photos')
                ->where('album_id', $album['id'])
                ->orderBy('sort_order', 'asc')
                ->orderBy('created_at', 'asc')
                ->limit(4)
                ->pluck('filename')
                ->toArray();
            $albums[$key]['photos'] = $photos;
        }

        return $this->legacyView('gallery/index', compact('albums'), 'main', 'Gallery');
    }

    public function album(Request $request, $id)
    {
        $id     = (int)$id;
        $model  = new Gallery();
        $album  = DB::table('gallery_albums')->where('id', $id)->first();
        if (!$album) {
            abort(404);
        }
        $album = (array)$album;
        $photos = $model->getPhotos($id);

        return $this->legacyView('gallery/album', compact('album', 'photos'), 'main', (string)$album['title']);
    }

    public function upload(Request $request, $id)
    {
        $albumId = (int)$id;
        $user    = Auth::user();
        if (!$user) {
            return redirect('/login')->with('error', 'Please log in first.');
        }

        $profileStatus = DB::table('alumni_profiles')->where('user_id', $user->id)->value('status');

        if (!in_array($user->role, ['super_admin', 'admin']) && $profileStatus !== 'approved') {
            return redirect('/gallery/' . $albumId)->with('error', 'Only approved verified alumni can upload photos.');
        }

        $files = $request->file('photos');
        if (!$files) {
            return redirect('/gallery/' . $albumId)->with('error', 'No photos selected.');
        }

        if (!is_array($files)) {
            $files = [$files];
        }

        $upload = new UploadService();
        $count  = 0;

        foreach ($files as $file) {
            if ($file && $file->isValid()) {
                $filename = $upload->uploadGallery($file, $albumId);
                if ($filename) {
                    DB::table('gallery_photos')->insert([
                        'album_id'    => $albumId,
                        'filename'    => $filename,
                        'uploaded_by' => $user->id,
                        'created_at'  => now(),
                    ]);
                    $count++;
                }
            }
        }

        return redirect('/gallery/' . $albumId)->with('success', "{$count} photo(s) uploaded successfully.");
    }
}
