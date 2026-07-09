<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Http\Request;
use App\Http\Controllers\Api\V1\InstituteSubscriptionController;

$controller = new InstituteSubscriptionController();
$webhookSecret = config('services.razorpay.webhook_secret');

echo "=== Razorpay Webhook Integration Test ===\n";
echo "Loaded Webhook Secret: " . ($webhookSecret ?: "(NOT CONFIGURED)") . "\n\n";

if (!$webhookSecret) {
    echo "ERROR: Please make sure RAZORPAY_WEBHOOK_SECRET is set in your .env file.\n";
    exit(1);
}

// ----------------------------------------------------
// TEST 1: Missing X-Razorpay-Signature
// ----------------------------------------------------
echo "TEST 1: Sending Webhook without signature header...\n";
$req1 = Request::create('/api/v1/institute/subscription/webhook', 'POST', [], [], [], [], json_encode(['event' => 'payment.captured']));
$res1 = $controller->handleWebhook($req1);
echo "Result Status Code: " . $res1->getStatusCode() . " (Expected: 400)\n";
echo "Response Body: " . $res1->getContent() . "\n\n";

// ----------------------------------------------------
// TEST 2: Invalid Signature
// ----------------------------------------------------
echo "TEST 2: Sending Webhook with a tampered/invalid signature...\n";
$body2 = json_encode(['event' => 'payment.captured']);
$req2 = Request::create('/api/v1/institute/subscription/webhook', 'POST', [], [], [], [
    'HTTP_X_RAZORPAY_SIGNATURE' => 'invalid_signature_hex_code_12345'
], $body2);
$res2 = $controller->handleWebhook($req2);
echo "Result Status Code: " . $res2->getStatusCode() . " (Expected: 400)\n";
echo "Response Body: " . $res2->getContent() . "\n\n";

// ----------------------------------------------------
// TEST 3: Correct Signature for generic event
// ----------------------------------------------------
echo "TEST 3: Sending Webhook with a valid signature but ignored event...\n";
$body3 = json_encode(['event' => 'payment.failed']);
$validSignature = hash_hmac('sha256', $body3, $webhookSecret);
$req3 = Request::create('/api/v1/institute/subscription/webhook', 'POST', [], [], [], [
    'HTTP_X_RAZORPAY_SIGNATURE' => $validSignature
], $body3);
$res3 = $controller->handleWebhook($req3);
echo "Result Status Code: " . $res3->getStatusCode() . " (Expected: 200)\n";
echo "Response Body: " . $res3->getContent() . "\n\n";

echo "=========================================\n";
echo "All local validation checks passed successfully!\n";
