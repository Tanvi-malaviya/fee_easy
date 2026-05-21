<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$notifications = App\Models\Notification::where('type', 'homework')
    ->whereNotNull('reference_id')
    ->get();

foreach ($notifications as $notif) {
    $hw = App\Models\Homework::find($notif->reference_id);
    if (!$hw) continue;

    $body = $hw->title
        . ($hw->description ? "\n" . $hw->description : '')
        . ($hw->due_date ? "\nDue: " . date('M d, Y', strtotime($hw->due_date)) : '');

    $notif->update(['message' => $body]);
    echo "Updated notification ID {$notif->id} for homework '{$hw->title}'" . PHP_EOL;
}

echo "Done! " . count($notifications) . " notifications updated." . PHP_EOL;
