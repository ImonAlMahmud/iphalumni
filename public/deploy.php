<?php
/**
 * Standalone Local-to-Server & Webhook Deployment Script
 * IPH Alumni Association
 */

// disable error display to clients
ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');
error_reporting(E_ALL);
@set_time_limit(300);
@ini_set('memory_limit', '512M');

header('Content-Type: application/json; charset=utf-8');

// require env secret
$deploySecret = getenv('DEPLOY_SECRET') ?: null;
if (empty($deploySecret) && file_exists(__DIR__ . '/../.env')) {
    $envContent = @file_get_contents(__DIR__ . '/../.env') ?: '';
    if (preg_match('/^DEPLOY_SECRET=(.*)$/m', $envContent, $m)) {
        $deploySecret = trim($m[1], " \t\n\r\0\x0B\"'");
    }
}
$providedSecret = $_SERVER['HTTP_X_DEPLOY_TOKEN'] ?? $_GET['token'] ?? $_POST['token'] ?? ($_GET['secret'] ?? ($_POST['secret'] ?? null));
if (empty($deploySecret) || !hash_equals((string)$deploySecret, (string)$providedSecret)) {
    http_response_code(403);
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized.']);
    exit;
}

// safe extraction helpers
function safe_filename(string $name): string {
    $name = str_replace(['\\', "\0"], '', $name);
    $name = preg_replace('#\.\.+#', '', $name);
    $name = preg_replace('/[^A-Za-z0-9_\-\.\/]/', '', $name);
    return ltrim($name, '/');
}

function is_allowed_file(string $name): bool {
    if (str_starts_with($name, '.')) return false;
    if (str_contains($name, '/.git') || str_contains($name, '/.env') || str_contains($name, '.env')) return false;
    $badExt = ['env', 'phar', 'sh', 'exe', 'bat', 'cmd'];
    $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
    if (in_array($ext, $badExt, true)) return false;
    return true;
}

// Ping / Health check
if (isset($_GET['action']) && $_GET['action'] === 'ping') {
    echo json_encode([
        'status'      => 'success',
        'message'     => 'Deploy endpoint is online and responding!',
        'php_version' => PHP_VERSION,
        'zip_enabled' => class_exists('ZipArchive'),
        'exec_enabled'=> function_exists('exec'),
        'upload_max'  => ini_get('upload_max_filesize'),
        'post_max'    => ini_get('post_max_size')
    ], JSON_PRETTY_PRINT);
    exit;
}

$root = file_exists(__DIR__ . '/../artisan') ? realpath(__DIR__ . '/..') : realpath(__DIR__);

if (isset($_GET['action']) && $_GET['action'] === 'log') {
    $logFile = $root . '/storage/logs/laravel.log';
    $content = file_exists($logFile) ? @file_get_contents($logFile) : 'Log file not found';
    $lines = explode("\n", (string)$content);
    $tail = array_slice($lines, -150);
    echo json_encode([
        'status' => 'success',
        'log'    => implode("\n", $tail)
    ]);
    exit;
}
$isWindows = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
$cdPrefix = $isWindows ? "cd /d \"{$root}\" && " : "cd \"{$root}\" && ";

function run_cmd(string $cmd, string $cdPrefix): array {
    if (!function_exists('exec')) {
        return ['command' => $cmd, 'result' => 'exec() is disabled by hosting provider', 'code' => 0];
    }
    $fullCmd = $cdPrefix . $cmd;
    $out = [];
    $code = 0;
    @exec($fullCmd, $out, $code);
    return ['command' => $cmd, 'result' => implode("\n", $out), 'code' => $code];
}

function clear_laravel_cache_dirs(string $root): void {
    $dirs = [
        $root . '/bootstrap/cache',
        $root . '/storage/framework/cache/data',
        $root . '/storage/framework/views',
    ];
    foreach ($dirs as $dir) {
        if (!is_dir($dir)) continue;
        $files = @glob($dir . '/*');
        if ($files) {
            foreach ($files as $file) {
                if (is_file($file) && basename($file) !== '.gitignore') {
                    @unlink($file);
                }
            }
        }
    }
}

$start = microtime(true);
$output = [];

try {
    // ── Direct Local ZIP Upload Receiver ──
    $uploadedFile = null;
    $isTempStream = false;

    if (!empty($_FILES)) {
        $first = reset($_FILES);
        if ($first['error'] === UPLOAD_ERR_OK && !empty($first['tmp_name']) && is_uploaded_file($first['tmp_name'])) {
            $uploadedFile = $first['tmp_name'];
        }
    }

    // Fallback to raw binary stream
    if (empty($uploadedFile)) {
        $rawStream = file_get_contents('php://input');
        if (!empty($rawStream) && substr($rawStream, 0, 4) === "PK\x03\x04") {
            $tempStreamFile = tempnam(sys_get_temp_dir(), 'iph_pkg_');
            file_put_contents($tempStreamFile, $rawStream);
            $uploadedFile = $tempStreamFile;
            $isTempStream = true;
        }
    }

    if (!empty($uploadedFile)) {
        if (!class_exists('ZipArchive')) {
            throw new Exception("PHP ZipArchive extension is not enabled on this server.");
        }

        $zip = new ZipArchive();
        if ($zip->open($uploadedFile) === true) {
            $fileCount = $zip->numFiles;
            $extractedCount = 0;
            for ($i = 0; $i < $fileCount; $i++) {
                $rawName = $zip->getNameIndex($i);
                $filename = safe_filename($rawName);

                if (empty($filename) || !is_allowed_file($filename) || str_ends_with($filename, '/')) {
                    continue;
                }

                $content = $zip->getFromIndex($i);
                if ($content !== false) {
                    $targetRoot = $root . '/' . $filename;
                    @mkdir(dirname($targetRoot), 0755, true);
                    @file_put_contents($targetRoot, $content);
                    $extractedCount++;
                }
            }
            $zip->close();

            if ($isTempStream && file_exists($uploadedFile)) {
                @unlink($uploadedFile);
            }

            $output[] = [
                'command' => 'extract_package',
                'result'  => "Successfully extracted {$extractedCount} sanitized files to server.",
                'code'    => 0
            ];
        } else {
            throw new Exception("Failed to open uploaded ZIP package.");
        }
    } else {
        // Git webhook pull flow
        $branch = 'main';
        $output[] = run_cmd("git fetch origin {$branch} 2>&1", $cdPrefix);
        $output[] = run_cmd("git reset --hard origin/{$branch} 2>&1", $cdPrefix);
    }

    clear_laravel_cache_dirs($root);
    $output[] = run_cmd('php artisan optimize:clear 2>&1', $cdPrefix);
    $output[] = run_cmd('php artisan storage:link 2>&1', $cdPrefix);

    $duration = round(microtime(true) - $start, 2);

    echo json_encode([
        'status'   => 'success',
        'message'  => 'Deployment completed successfully.',
        'duration' => "{$duration}s",
        'output'   => $output,
    ], JSON_PRETTY_PRINT);

} catch (Throwable $e) {
    error_log('Deployment failed: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'status'  => 'error',
        'message' => 'Deployment failed.',
    ], JSON_PRETTY_PRINT);
}
