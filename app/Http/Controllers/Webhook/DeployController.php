<?php
declare(strict_types=1);

namespace App\Http\Controllers\Webhook;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DeployController extends Controller
{
    /**
     * Handle incoming git webhook deployment request
     */
    public function handle(Request $request): JsonResponse
    {
        $configuredSecret = env('DEPLOY_SECRET', 'iph_alumni_secret_key_deploy_2026');
        $targetBranch     = env('DEPLOY_BRANCH', 'main');

        // 1. Verify Secret Token / Signature
        $providedSecret = $request->header('X-Deploy-Token')
            ?: $request->header('X-Gitlab-Token')
            ?: $request->input('token')
            ?: $request->input('secret');

        $githubSignature = $request->header('X-Hub-Signature-256');

        $isValid = false;

        if (!empty($githubSignature) && !empty($configuredSecret)) {
            $payload = $request->getContent();
            $knownSignature = 'sha256=' . hash_hmac('sha256', $payload, $configuredSecret);
            $isValid = hash_equals($knownSignature, $githubSignature);
        } elseif (!empty($providedSecret) && !empty($configuredSecret)) {
            $isValid = hash_equals($configuredSecret, (string)$providedSecret);
        }

        if (!$isValid) {
            Log::warning('Unauthorized deploy webhook attempt', [
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized: Invalid secret token or signature'
            ], 403);
        }

        // 2. Verify Target Branch from Payload (if present)
        $ref = $request->input('ref'); // e.g. "refs/heads/main"
        if (!empty($ref)) {
            $pushedBranch = basename($ref);
            if ($pushedBranch !== $targetBranch && $pushedBranch !== 'master') {
                return response()->json([
                    'status' => 'ignored',
                    'message' => "Push was to branch '{$pushedBranch}', but deployment is configured for '{$targetBranch}'."
                ], 200);
            }
        }

        // 3. Execute Deployment Commands
        $basePath = base_path();
        $isWindows = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
        $cdPrefix = $isWindows ? "cd /d \"{$basePath}\" && " : "cd \"{$basePath}\" && ";

        $commands = [
            "git fetch --all 2>&1",
            "git reset --hard origin/{$targetBranch} 2>&1 || git pull origin {$targetBranch} 2>&1",
            "composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader 2>&1",
            "php artisan migrate --force 2>&1",
            "php artisan optimize:clear 2>&1",
            "php artisan config:cache 2>&1",
            "php artisan route:cache 2>&1",
            "php artisan view:cache 2>&1",
            "php artisan storage:link 2>&1",
        ];

        $output = [];
        $startTime = microtime(true);

        foreach ($commands as $cmd) {
            $fullCmd = $cdPrefix . $cmd;
            $cmdOutput = [];
            $returnCode = 0;
            exec($fullCmd, $cmdOutput, $returnCode);
            $output[] = [
                'command' => $cmd,
                'result'  => implode("\n", $cmdOutput),
                'code'    => $returnCode
            ];
        }

        $duration = round(microtime(true) - $startTime, 2);

        // 4. Write to deploy log
        $logFile = storage_path('logs/deploy.log');
        $logEntry = "=== DEPLOYMENT: " . date('Y-m-d H:i:s') . " (IP: {$request->ip()}) ===\n";
        foreach ($output as $step) {
            $logEntry .= "> {$step['command']}\n" . ($step['result'] ?: '[No Output]') . "\n";
        }
        $logEntry .= "Duration: {$duration}s\n\n";
        @file_put_contents($logFile, $logEntry, FILE_APPEND);

        return response()->json([
            'status'    => 'success',
            'message'   => 'Deployment script executed successfully',
            'timestamp' => date('Y-m-d H:i:s'),
            'branch'    => $targetBranch,
            'duration'  => "{$duration}s",
            'output'    => $output
        ]);
    }
}
