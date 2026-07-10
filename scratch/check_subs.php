<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$institute = \App\Models\Institute::find(5);
if (!$institute) {
    echo "Institute not found\n";
    exit;
}

echo "Institute ID: " . $institute->id . "\n";
echo "Name: " . $institute->name . "\n";
echo "Subscriptions:\n";
foreach ($institute->subscriptions as $sub) {
    echo "ID: {$sub->id}, Plan: {$sub->plan_name}, Amount: {$sub->amount}, Status: {$sub->status}, Start: {$sub->start_date?->toDateString()}, End: {$sub->end_date?->toDateString()}, Created: {$sub->created_at}\n";
}

echo "\nSubscription Status Method:\n";
print_r($institute->subscriptionStatus());
