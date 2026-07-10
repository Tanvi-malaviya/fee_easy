<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Institute;
use App\Models\Plan;

echo "=== Testing Razorpay Order Creation with Tuoora Metadata ===\n";

$institute = Institute::first();
if (!$institute) {
    echo "ERROR: No institute found.\n";
    exit(1);
}

$plan = Plan::where('price', '>', 0)->first() ?: Plan::first();
if (!$plan) {
    echo "ERROR: No plans found.\n";
    exit(1);
}

echo "Using Institute: {$institute->institute_name} (ID: {$institute->id})\n";
echo "Using Plan: {$plan->name} (Price: {$plan->price}, ID: {$plan->id})\n";

try {
    $api = new \Razorpay\Api\Api(config('services.razorpay.key_id'), config('services.razorpay.key_secret'));
    $api->setAppDetails('Tuoora', '1.0.0');

    $orderData = [
        'receipt'         => 'rcpt_' . $institute->id . '_' . time(),
        'amount'          => $plan->price * 100, // in paise
        'currency'        => 'INR',
        'notes'           => [
            'plan_id' => $plan->id,
            'institute_id' => $institute->id,
            'app_name' => 'Tuoora',
            'app_id' => 'com.app.tuoora',
            'invoice_id' => 'INV-' . strtoupper(substr(uniqid(), -6)) . '-' . $institute->id
        ]
    ];

    echo "\nSending order data to Razorpay API...\n";
    $razorpayOrder = $api->order->create($orderData);

    echo "Razorpay Order Created Successfully!\n";
    echo "Order ID: " . $razorpayOrder['id'] . "\n";
    echo "\nReturned Notes from Razorpay Response:\n";
    print_r($razorpayOrder['notes']->toArray());

} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
