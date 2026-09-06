<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MobileAppLink;
use Illuminate\Http\Request;

class MobileAppLinkController extends Controller
{
    // Show list of links and a simple form to add/edit
    public function index()
    {
        $links = MobileAppLink::all();
        return view('admin.mobile_app_links.index', ['links' => $links]);
    }

    // Store new link
    public function store(Request $request)
    {
        $validated = $request->validate([
            'platform' => 'required|string|in:android,ios',
            'url'      => 'required|url',
        ]);
        MobileAppLink::create($validated);
        return redirect()->route('mobile_app_links.index')->with('success', 'Link added');
    }

    // Update existing link
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'platform' => 'required|string|in:android,ios',
            'url'      => 'required|url',
        ]);
        $link = MobileAppLink::findOrFail($id);
        $link->update($validated);
        return redirect()->route('mobile_app_links.index')->with('success', 'Link updated');
    }

    // Delete link
    public function destroy($id)
    {
        $link = MobileAppLink::findOrFail($id);
        $link->delete();
        return redirect()->route('mobile_app_links.index')->with('success', 'Link deleted');
    }
}
?>
