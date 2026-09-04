<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

abstract class BaseController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Render a legacy .php view file (from resources/views/)
     * Pass data as array, plus optional $title, $description, $layout
     */
    protected function legacyView(
        string $view,
        array $data = [],
        string $layout = 'main',
        string $title = '',
        string $description = ''
    ): \Illuminate\Http\Response {
        // Extract data into local variables
        extract($data);

        $viewFile = resource_path('views/' . str_replace('.', '/', $view) . '.php');
        if (!file_exists($viewFile)) {
            abort(404, "View not found: {$view}");
        }

        // Capture view content
        ob_start();
        require $viewFile;
        $content = ob_get_clean();

        // Load layout
        $layoutFile = resource_path("views/layouts/{$layout}.php");
        if (!file_exists($layoutFile)) {
            return response($content);
        }

        ob_start();
        require $layoutFile;
        $html = ob_get_clean();

        return response($html);
    }

    /**
     * Redirect helper matching old redirect() pattern
     */
    protected function redirectTo(string $path): \Illuminate\Http\RedirectResponse
    {
        return redirect()->to($path);
    }

    /**
     * Flash a message to session
     */
    protected function flash(string $key, mixed $value): void
    {
        session()->flash($key, $value);
    }
}
