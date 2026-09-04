<?php
/**
 * Standalone Webhook Deployment Script
 * IPH Alumni Association
 *
 * Usage:
 * URL: https://your-domain.com/deploy.php?secret=YOUR_DEPLOY_SECRET
 * Or configured as GitHub / GitLab Webhook Payload URL
 */

// Load environment if possible
$envFile = __DIR__ . '/../.env';
$deploySecret = 'iph_alumni_secret_key_deploy_2026';
$deployBranch = 'main';

if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (str_starts_with(trim($line), '#')) continue;
        if (str_contains($line, '=')) {
            [$k, $v] = explode('=', $line, 2);
            $k = trim($k);
            $v = trim($v, " \t\n\r\0\x0B\"'");
            if ($k === 'DEPLOY_SECRET') $deploySecret = $v;
            if ($k === 'DEPLOY_BRANCH') $deployBranch = $v;
        }
    }
}

header('Content-Type: application/json; charset=utf-8');

// Verification
$providedSecret = $_SERVER['HTTP_X_DEPLOY_TOKEN'] 
    ?? $_SERVER['HTTP_X_GITLAB_TOKEN'] 
    ?? $_GET['secret'] 
    ?? $_GET['token'] 
    ?? $_POST['secret'] 
    ?? null;

$githubSignature = $_SERVER['HTTP_X_HUB_SIGNATURE_256'] ?? null;
$rawBody = file_get_contents('php://input');

$isValid = false;
if (!empty($githubSignature) && !empty($deploySecret)) {
    $knownSig = 'sha256=' . hash_hmac('sha256', $rawBody, $deploySecret);
    $isValid = hash_equals($knownSig, $githubSignature);
} elseif (!empty($providedSecret) && !empty($deploySecret)) {
    $isValid = hash_equals($deploySecret, (string)$providedSecret);
}

if (!$isValid) {
    http_response_code(403);
    echo json_encode([
        'status' => 'error',
        'message' => 'Unauthorized: Invalid secret token or signature'
    ], JSON_PRETTY_PRINT);
    exit;
}

// Target Branch Check
$payload = json_decode($rawBody, true);
if (!empty($payload['ref'])) {
    $pushedBranch = basename($payload['ref']);
    if ($pushedBranch !== $deployBranch && $pushedBranch !== 'master') {
        echo json_encode([
            'status' => 'ignored',
            'message' => "Push was to branch '{$pushedBranch}', but auto-deploy is configured for '{$deployBranch}'."
        ], JSON_PRETTY_PRINT);
        exit;
    }
}

// Execution
$root = realpath(__DIR__ . '/..');
$isWindows = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
$cdPrefix = $isWindows ? "cd /d \"{$root}\" && " : "cd \"{$root}\" && ";

$commands = [
    "git fetch --all 2>&1",
    "git reset --hard origin/{$deployBranch} 2>&1 || git pull origin {$deployBranch} 2>&1",
    "composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader 2>&1",
    "php artisan migrate --force 2>&1",
    "php artisan optimize:clear 2>&1",
    "php artisan config:cache 2>&1",
    "php artisan route:cache 2>&1",
    "php artisan view:cache 2>&1",
    "php artisan storage:link 2>&1",
];

$output = [];
$start = microtime(true);

foreach ($commands as $cmd) {
    $fullCmd = $cdPrefix . $cmd;
    $out = [];
    $code = 0;
    exec($fullCmd, $out, $code);
    $output[] = [
        'command' => $cmd,
        'result'  => implode("\n", $out),
        'code'    => $code
    ];
}

$duration = round(microtime(true) - $start, 2);

// Write to deploy log
$logFile = $root . '/storage/logs/deploy.log';
$logEntry = "=== DEPLOYMENT: " . date('Y-m-d H:i:s') . " ===\n";
foreach ($output as $step) {
    $logEntry .= "> {$step['command']}\n" . ($step['result'] ?: '[No Output]') . "\n";
}
$logEntry .= "Duration: {$duration}s\n\n";
@file_put_contents($logFile, $logEntry, FILE_APPEND);

echo json_encode([
    'status'    => 'success',
    'message'   => 'Automated webhook deployment completed',
    'timestamp' => date('Y-m-d H:i:s'),
    'branch'    => $deployBranch,
    'duration'  => "{$duration}s",
    'output'    => $output
], JSON_PRETTY_PRINT);
