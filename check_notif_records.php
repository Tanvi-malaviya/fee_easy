<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
$notifs = Illuminate\Support\Facades\DB::table('notifications')->latest()->take(10)->get();
foreach ($notifs as $notif) {
    echo "ID: {$notif->id} | Title: {$notif->title} | Type: {$notif->type} | Image: " . ($notif->image ?? 'NULL') . "\n";
}
