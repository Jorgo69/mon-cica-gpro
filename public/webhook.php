<?php
// ==============================================================================
// WEBHOOK.PHP - Post-deployment trigger
// Called by GitHub Actions after FTP upload completes
// Auth: Bearer token in Authorization header OR X-Webhook-Token header
// ==============================================================================

header('Content-Type: application/json');

// Only accept POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
    exit;
}

// Load Laravel environment
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Get secret from .env
$secret = env('WEBHOOK_SECRET');

if (empty($secret) || $secret === 'CHANGE_THIS_SECRET_TOKEN') {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Webhook not configured']);
    exit;
}

// Accept token from Authorization header (Bearer) or X-Webhook-Token header
$token = '';
$authHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
if (str_starts_with($authHeader, 'Bearer ')) {
    $token = substr($authHeader, 7);
} else {
    $token = $_SERVER['HTTP_X_WEBHOOK_TOKEN'] ?? '';
}

if (!hash_equals($secret, $token)) {
    http_response_code(403);
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

// Log file
$logFile = storage_path('logs/webhook.log');

$logMessage = function ($message) use ($logFile) {
    $timestamp = date('Y-m-d H:i:s');
    file_put_contents($logFile, "[$timestamp] $message\n", FILE_APPEND);
};

$logMessage('Webhook triggered from IP: ' . ($_SERVER['REMOTE_ADDR'] ?? 'unknown'));

// Path to deploy script
$deployScript = base_path('deploy.sh');

if (!file_exists($deployScript)) {
    http_response_code(404);
    $logMessage('ERROR: deploy.sh not found');
    echo json_encode(['status' => 'error', 'message' => 'Deploy script not found']);
    exit;
}

// Execute deploy script
$output = [];
$returnCode = 0;
$command = "cd " . escapeshellarg(base_path()) . " && bash deploy.sh 2>&1";

exec($command, $output, $returnCode);

$logMessage('Return code: ' . $returnCode);
$logMessage('Output: ' . implode("\n", $output));

// Response (no sensitive info exposed)
if ($returnCode === 0) {
    http_response_code(200);
    echo json_encode(['status' => 'success', 'message' => 'Deployment completed']);
} else {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Deployment failed']);
}

$logMessage('Webhook completed');
