<?php
// ==============================================================================
// WEBHOOK.PHP - Post-deployment trigger for Campus Chine
// Called by GitHub Actions after FTP upload completes
// URL: https://votresite.com/webhook.php?token=VOTRE_TOKEN
// ==============================================================================

// Load Laravel environment
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Get secret from .env (WEBHOOK_SECRET variable)
$secret = env('WEBHOOK_SECRET', 'CHANGE_THIS_SECRET_TOKEN');

// Verify token from request URL
$token = $_GET['token'] ?? '';

if (empty($token) || $token !== $secret) {
    http_response_code(403);
    echo json_encode(['status' => 'error', 'message' => 'Invalid or missing token']);
    exit;
}

// Log file path
$logFile = storage_path('logs/webhook.log');

// Log function
function logMessage($message)
{
    global $logFile;
    $timestamp = date('Y-m-d H:i:s');
    file_put_contents($logFile, "[$timestamp] $message\n", FILE_APPEND);
}

logMessage('Webhook triggered');

// Path to deploy script
$deployScript = base_path('deploy.sh');

if (!file_exists($deployScript)) {
    http_response_code(404);
    logMessage('ERROR: deploy.sh not found at ' . $deployScript);
    echo json_encode(['status' => 'error', 'message' => 'Deploy script not found']);
    exit;
}

// Execute deploy script
$output = [];
$returnCode = 0;
$command = "cd " . base_path() . " && bash deploy.sh 2>&1";

exec($command, $output, $returnCode);

// Log output
logMessage('User: ' . get_current_user());
logMessage('Path: ' . getenv('PATH'));
logMessage('Output: ' . implode("\n", $output));
logMessage('Return code: ' . $returnCode);

// Response
if ($returnCode === 0) {
    http_response_code(200);
    echo json_encode([
        'status' => 'success',
        'message' => 'Deployment completed',
        'output' => $output
    ]);
}
else {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Deployment failed',
        'return_code' => $returnCode,
        'output' => $output
    ]);
}

logMessage('Webhook completed');