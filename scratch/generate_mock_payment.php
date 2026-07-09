<?php

// Bootstrap Laravel to automatically use the active credentials from your .env file
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Change this order ID whenever you create a new order in Step 1
$order_id = 'order_TBIDbFJjOMT6WN'; 

$payment_id = 'pay_test' . bin2hex(random_bytes(5));
$secret = config('services.razorpay.key_secret');

if (empty($secret)) {
    echo "Error: RAZORPAY_KEY_SECRET is not configured in your .env file.\n";
    exit(1);
}

$signature = hash_hmac('sha256', $order_id . '|' . $payment_id, $secret);

echo json_encode([
    'plan_id' => 1, // Change this to match your plan_id if needed
    'razorpay_order_id' => $order_id,
    'razorpay_payment_id' => $payment_id,
    'razorpay_signature' => $signature
], JSON_PRETTY_PRINT) . "\n";
