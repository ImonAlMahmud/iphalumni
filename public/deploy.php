<?php
/**
 * Standalone Local-to-Server & Webhook Deployment Script
 * IPH Alumni Association
 */

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
@set_time_limit(300);
@ini_set('memory_limit', '512M');

header('Content-Type: application/json; charset=utf-8');

// Load environment config
$envFile = __DIR__ . '/../.env';
if (!file_exists($envFile)) {
    $envFile = __DIR__ . '/.env';
}

$deploySecret = 'iph_alumni_secret_key_deploy_2026';
$deployBranch = 'main';

if (file_exists($envFile)) {
    $lines = @file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if ($lines) {
        foreach ($lines as $line) {
            $line = trim($line);
            if (str_starts_with($line, '#')) continue;
            if (str_contains($line, '=')) {
                [$k, $v] = explode('=', $line, 2);
                $k = trim($k);
                $v = trim($v, " \t\n\r\0\x0B\"'");
                if ($k === 'DEPLOY_SECRET') $deploySecret = $v;
                if ($k === 'DEPLOY_BRANCH') $deployBranch = $v;
            }
        }
    }
}

// Verification
$providedSecret = $_SERVER['HTTP_X_DEPLOY_TOKEN'] 
    ?? $_SERVER['HTTP_X_GITLAB_TOKEN'] 
    ?? $_GET['secret'] 
    ?? $_GET['token'] 
    ?? $_POST['secret'] 
    ?? null;

$isValid = false;
if (!empty($providedSecret) && !empty($deploySecret)) {
    $isValid = hash_equals($deploySecret, (string)$providedSecret);
}

if (!$isValid) {
    http_response_code(403);
    echo json_encode([
        'status'  => 'error',
        'message' => 'Unauthorized: Invalid secret token. Please check DEPLOY_SECRET.'
    ], JSON_PRETTY_PRINT);
    exit;
}

// Test / Ping Action
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

// Determine project root directory
$root = file_exists(__DIR__ . '/../artisan') ? realpath(__DIR__ . '/..') : realpath(__DIR__);
$isWindows = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
$cdPrefix = $isWindows ? "cd /d \"{$root}\" && " : "cd \"{$root}\" && ";

// Action: Database connection check
if (isset($_GET['action']) && $_GET['action'] === 'db_check') {
    $dbHost = '127.0.0.1';
    $dbName = 'pmarkbdc_alumni';
    $dbUser = 'pmarkbdc_alumni';
    $dbPass = '100%Imon?';
    $dbPort = '3306';

    if (file_exists($envFile)) {
        $lines = @file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $l) {
            $l = trim($l);
            if (str_starts_with($l, 'DB_HOST=')) $dbHost = trim(explode('=', $l, 2)[1]);
            if (str_starts_with($l, 'DB_DATABASE=')) $dbName = trim(explode('=', $l, 2)[1]);
            if (str_starts_with($l, 'DB_USERNAME=')) $dbUser = trim(explode('=', $l, 2)[1]);
            if (str_starts_with($l, 'DB_PASSWORD=')) $dbPass = trim(explode('=', $l, 2)[1], " \t\n\r\0\x0B\"'");
            if (str_starts_with($l, 'DB_PORT=')) $dbPort = trim(explode('=', $l, 2)[1]);
        }
    }

    try {
        $dsn = "mysql:host={$dbHost};port={$dbPort};dbname={$dbName};charset=utf8mb4";
        $pdo = new PDO($dsn, $dbUser, $dbPass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
        $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
        
        echo json_encode([
            'status'      => 'success',
            'message'     => 'Database connection successful!',
            'database'    => $dbName,
            'table_count' => count($tables),
            'tables'      => $tables,
        ], JSON_PRETTY_PRINT);
    } catch (Throwable $e) {
        http_response_code(500);
        echo json_encode([
            'status'   => 'error',
            'message'  => 'DB Connection Failed: ' . $e->getMessage(),
            'database' => $dbName,
            'user'     => $dbUser,
        ], JSON_PRETTY_PRINT);
    }
    exit;
}

if (isset($_GET['action']) && $_GET['action'] === 'view_user_location') {
    $dbHost = '127.0.0.1'; $dbName = 'pmarkbdc_alumni'; $dbUser = 'pmarkbdc_alumni'; $dbPass = '100%Imon?'; $dbPort = '3306';
    if (file_exists($envFile)) {
        $lines = @file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $l) {
            $l = trim($l);
            if (str_starts_with($l, 'DB_HOST=')) $dbHost = trim(explode('=', $l, 2)[1]);
            if (str_starts_with($l, 'DB_DATABASE=')) $dbName = trim(explode('=', $l, 2)[1]);
            if (str_starts_with($l, 'DB_USERNAME=')) $dbUser = trim(explode('=', $l, 2)[1]);
            if (str_starts_with($l, 'DB_PASSWORD=')) $dbPass = trim(explode('=', $l, 2)[1], " \t\n\r\0\x0B\"'");
            if (str_starts_with($l, 'DB_PORT=')) $dbPort = trim(explode('=', $l, 2)[1]);
        }
    }
    $dsn = "mysql:host={$dbHost};port={$dbPort};dbname={$dbName};charset=utf8mb4";
    $pdo = new PDO($dsn, $dbUser, $dbPass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    $profiles = $pdo->query("SELECT ap.id, ap.user_id, u.name, u.email, ap.current_location, ap.thana_upazila, ap.country, ap.province_city, ap.location_type, ap.permanent_location, ap.permanent_upazila, ap.permanent_district FROM alumni_profiles ap JOIN users u ON u.id = ap.user_id LIMIT 10")->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode(['profiles' => $profiles], JSON_PRETTY_PRINT);
    exit;
}

// Action: View latest Laravel logs
if (isset($_GET['action']) && $_GET['action'] === 'logs') {
    $logFile = $root . '/storage/logs/laravel.log';
    if (!file_exists($logFile)) {
        echo json_encode(['status' => 'info', 'message' => 'No laravel.log file found at ' . $logFile]);
        exit;
    }
    $lines = file($logFile);
    $num = isset($_GET['lines']) ? max(10, min(1000, (int)$_GET['lines'])) : 200;
    $lastLines = array_slice($lines, -$num);
    echo json_encode([
        'status'         => 'success',
        'file'           => $logFile,
        'size'           => filesize($logFile),
        'last_lines'     => implode("", $lastLines),
    ], JSON_PRETTY_PRINT);
    exit;
}

// Action: Check alumni_profiles columns
if (isset($_GET['action']) && $_GET['action'] === 'db_columns') {
    $dbHost = '127.0.0.1'; $dbName = 'pmarkbdc_alumni'; $dbUser = 'pmarkbdc_alumni'; $dbPass = '100%Imon?'; $dbPort = '3306';
    if (file_exists($envFile)) {
        $lines = @file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $l) {
            $l = trim($l);
            if (str_starts_with($l, 'DB_HOST=')) $dbHost = trim(explode('=', $l, 2)[1], " \t\n\r\0\x0B\"'");
            if (str_starts_with($l, 'DB_DATABASE=')) $dbName = trim(explode('=', $l, 2)[1], " \t\n\r\0\x0B\"'");
            if (str_starts_with($l, 'DB_USERNAME=')) $dbUser = trim(explode('=', $l, 2)[1], " \t\n\r\0\x0B\"'");
            if (str_starts_with($l, 'DB_PASSWORD=')) $dbPass = trim(explode('=', $l, 2)[1], " \t\n\r\0\x0B\"'");
            if (str_starts_with($l, 'DB_PORT=')) $dbPort = trim(explode('=', $l, 2)[1], " \t\n\r\0\x0B\"'");
        }
    }
    try {
        $dsn = "mysql:host={$dbHost};port={$dbPort};dbname={$dbName};charset=utf8mb4";
        $pdo = new PDO($dsn, $dbUser, $dbPass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
        $cols = $pdo->query("SHOW COLUMNS FROM alumni_profiles")->fetchAll(PDO::FETCH_COLUMN);
        
        // Also check if permanent_location exists, if not add it immediately!
        $hasPerm = in_array('permanent_location', $cols);
        $added = false;
        if (!$hasPerm) {
            $pdo->exec("ALTER TABLE alumni_profiles ADD COLUMN permanent_location VARCHAR(255) NULL AFTER current_location");
            $added = true;
            $cols = $pdo->query("SHOW COLUMNS FROM alumni_profiles")->fetchAll(PDO::FETCH_COLUMN);
        }

        echo json_encode([
            'status'         => 'success',
            'has_permanent'  => in_array('permanent_location', $cols),
            'auto_added'     => $added,
            'column_count'   => count($cols),
            'columns'        => $cols
        ], JSON_PRETTY_PRINT);
    } catch (Throwable $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
    exit;
}

// Action: Import SQL file directly into live database
if (isset($_GET['action']) && $_GET['action'] === 'import_sql') {
    $sqlContent = '';
    $sqlFile = $root . '/iph_alumni_database_fixed.sql';
    
    // Check if raw POST data contains SQL
    $rawInput = file_get_contents('php://input');
    if (!empty($rawInput) && (str_contains($rawInput, 'CREATE TABLE') || str_contains($rawInput, 'INSERT INTO'))) {
        $sqlContent = $rawInput;
    } elseif (file_exists($sqlFile)) {
        $sqlContent = file_get_contents($sqlFile);
    }

    if (empty($sqlContent)) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'No SQL content provided or iph_alumni_database_fixed.sql not found.'], JSON_PRETTY_PRINT);
        exit;
    }

    $dbHost = '127.0.0.1';
    $dbName = 'pmarkbdc_alumni';
    $dbUser = 'pmarkbdc_alumni';
    $dbPass = '100%Imon?';
    $dbPort = '3306';

    if (file_exists($envFile)) {
        $lines = @file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $l) {
            $l = trim($l);
            if (str_starts_with($l, 'DB_HOST=')) $dbHost = trim(explode('=', $l, 2)[1]);
            if (str_starts_with($l, 'DB_DATABASE=')) $dbName = trim(explode('=', $l, 2)[1]);
            if (str_starts_with($l, 'DB_USERNAME=')) $dbUser = trim(explode('=', $l, 2)[1]);
            if (str_starts_with($l, 'DB_PASSWORD=')) $dbPass = trim(explode('=', $l, 2)[1], " \t\n\r\0\x0B\"'");
            if (str_starts_with($l, 'DB_PORT=')) $dbPort = trim(explode('=', $l, 2)[1]);
        }
    }

    try {
        $dsn = "mysql:host={$dbHost};port={$dbPort};dbname={$dbName};charset=utf8mb4";
        $pdo = new PDO($dsn, $dbUser, $dbPass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::MYSQL_ATTR_MULTI_STATEMENTS => true,
        ]);

        $pdo->exec("SET FOREIGN_KEY_CHECKS=0;");
        $pdo->exec($sqlContent);
        $pdo->exec("SET FOREIGN_KEY_CHECKS=1;");

        $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);

        echo json_encode([
            'status'      => 'success',
            'message'     => 'All database tables and seed data have been successfully imported into ' . $dbName . '!',
            'database'    => $dbName,
            'table_count' => count($tables),
            'tables'      => $tables,
        ], JSON_PRETTY_PRINT);
    } catch (Throwable $e) {
        http_response_code(500);
        echo json_encode([
            'status'   => 'error',
            'message'  => 'SQL Import Error: ' . $e->getMessage(),
            'database' => $dbName,
        ], JSON_PRETTY_PRINT);
    }
    exit;
}
$start = microtime(true);
$output = [];

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

try {
    // ── Direct Local ZIP Upload Receiver (supports both $_FILES and raw binary stream) ──
    $uploadedFile = null;
    $isTempStream = false;

    if (!empty($_FILES)) {
        $first = reset($_FILES);
        if ($first['error'] === UPLOAD_ERR_OK && !empty($first['tmp_name']) && is_uploaded_file($first['tmp_name'])) {
            $uploadedFile = $first['tmp_name'];
        }
    }

    // Fallback to raw binary stream (bypasses upload_max_filesize 2MB limit via post_max_size 8MB)
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
            for ($i = 0; $i < $fileCount; $i++) {
                $rawName = $zip->getNameIndex($i);
                $filename = str_replace('\\', '/', $rawName);

                if ($filename === '.env' || str_ends_with($filename, '/.env')) {
                    continue;
                }
                
                // Extract file content
                $content = $zip->getFromIndex($i);
                if ($content !== false && !str_ends_with($filename, '/')) {
                    // 1. Write to backend root
                    $targetRoot = $root . '/' . $filename;
                    @mkdir(dirname($targetRoot), 0755, true);
                    @file_put_contents($targetRoot, $content);

                    // 2. If inside public/, also write directly to web root (__DIR__)
                    if (str_starts_with($filename, 'public/')) {
                        $stripped = substr($filename, 7);
                        if (!empty($stripped)) {
                            $targetWeb = __DIR__ . '/' . $stripped;
                            @mkdir(dirname($targetWeb), 0755, true);
                            @file_put_contents($targetWeb, $content);
                        }
                    }

                    // 3. If inside storage/, also mirror directly to web root (__DIR__/storage/)
                    if (str_starts_with($filename, 'storage/app/public/')) {
                        $stripped = substr($filename, 19);
                        if (!empty($stripped)) {
                            $targetStorage = __DIR__ . '/storage/' . $stripped;
                            @mkdir(dirname($targetStorage), 0755, true);
                            @file_put_contents($targetStorage, $content);
                        }
                    } elseif (str_starts_with($filename, 'storage/')) {
                        $stripped = substr($filename, 8);
                        if (!empty($stripped) && !str_starts_with($stripped, 'logs/') && !str_starts_with($stripped, 'framework/')) {
                            $targetStorage = __DIR__ . '/storage/' . $stripped;
                            @mkdir(dirname($targetStorage), 0755, true);
                            @file_put_contents($targetStorage, $content);
                        }
                    }
                }
            }
            $zip->close();

            if ($isTempStream && file_exists($uploadedFile)) {
                @unlink($uploadedFile);
            }

            $output[] = [
                'command' => 'extract_package',
                'result'  => "Successfully extracted {$fileCount} files/folders to server root and web directory.",
                'code'    => 0
            ];
        } else {
            throw new Exception("Failed to open uploaded ZIP package.");
        }

        // Auto Schema Update
        try {
            $envFile = $root . '/.env';
            if (file_exists($envFile)) {
                $lines = @file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                $dbH = '127.0.0.1'; $dbN = ''; $dbU = ''; $dbP = ''; $dbPort = 3306;
                foreach ($lines as $l) {
                    $l = trim($l);
                    if (str_starts_with($l, 'DB_HOST=')) $dbH = trim(explode('=', $l, 2)[1]);
                    if (str_starts_with($l, 'DB_DATABASE=')) $dbN = trim(explode('=', $l, 2)[1]);
                    if (str_starts_with($l, 'DB_USERNAME=')) $dbU = trim(explode('=', $l, 2)[1]);
                    if (str_starts_with($l, 'DB_PASSWORD=')) $dbP = trim(explode('=', $l, 2)[1], " \t\n\r\0\x0B\"'");
                    if (str_starts_with($l, 'DB_PORT=')) $dbPort = trim(explode('=', $l, 2)[1]);
                }
                if ($dbN && $dbU) {
                    $pdo = new PDO("mysql:host={$dbH};port={$dbPort};dbname={$dbN};charset=utf8mb4", $dbU, $dbP);
                    $checkCol = $pdo->query("SHOW COLUMNS FROM alumni_profiles LIKE 'permanent_location'")->fetch();
                    if (!$checkCol) {
                        $pdo->exec("ALTER TABLE alumni_profiles ADD COLUMN permanent_location VARCHAR(255) NULL AFTER current_location");
                    }
                    $pdo->exec("ALTER TABLE alumni_profiles MODIFY batch_year VARCHAR(50) NULL DEFAULT NULL");
                    $pdo->exec("ALTER TABLE alumni_profiles MODIFY gender VARCHAR(30) NULL DEFAULT NULL");
                    $pdo->exec("ALTER TABLE alumni_profiles MODIFY blood_group VARCHAR(20) NULL DEFAULT NULL");
                    $pdo->exec("ALTER TABLE job_applications MODIFY user_id int(11) NULL DEFAULT NULL");
                    $pdo->exec("CREATE TABLE IF NOT EXISTS job_alert_subscriptions (
                        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                        email VARCHAR(191) NOT NULL UNIQUE,
                        name VARCHAR(100) NULL,
                        job_types VARCHAR(255) NULL,
                        token VARCHAR(64) NOT NULL UNIQUE,
                        status ENUM('active', 'unsubscribed') NOT NULL DEFAULT 'active',
                        created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
                        updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                        INDEX idx_job_sub_status (status),
                        INDEX idx_job_sub_email (email)
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");
                }
            }
        } catch (Throwable) {}

        $output[] = run_cmd("php artisan optimize:clear 2>&1", $cdPrefix);
        $output[] = run_cmd("php artisan storage:link 2>&1", $cdPrefix);
        clear_laravel_cache_dirs($root);

        $duration = round(microtime(true) - $start, 2);

        echo json_encode([
            'status'    => 'success',
            'type'      => 'direct_local_sync',
            'message'   => 'Direct Local-to-Server deployment completed successfully!',
            'timestamp' => date('Y-m-d H:i:s'),
            'duration'  => "{$duration}s",
            'output'    => $output
        ], JSON_PRETTY_PRINT);
        exit;
    }

    // ── Default Action: Cache Clear & Maintenance ──
    $output[] = run_cmd("php artisan optimize:clear 2>&1", $cdPrefix);
    $output[] = run_cmd("php artisan storage:link 2>&1", $cdPrefix);
    clear_laravel_cache_dirs($root);

    $duration = round(microtime(true) - $start, 2);
    echo json_encode([
        'status'    => 'success',
        'message'   => 'Server cache and storage cleared successfully',
        'duration'  => "{$duration}s",
        'output'    => $output
    ], JSON_PRETTY_PRINT);

} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode([
        'status'  => 'error',
        'message' => $e->getMessage(),
        'file'    => basename($e->getFile()) . ':' . $e->getLine()
    ], JSON_PRETTY_PRINT);
}
