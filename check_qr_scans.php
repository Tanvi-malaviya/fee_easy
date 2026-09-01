<?php
// Quick QR scan DB check script
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$count = App\Models\QrScan::count();
echo "Total QR Scans in DB: $count\n\n";

if ($count > 0) {
    $scans = App\Models\QrScan::latest()->take(5)->get();
    foreach ($scans as $s) {
        echo "ID:{$s->id} | Type:{$s->qr_type} | IP:{$s->ip_address} | Browser:{$s->browser} | OS:{$s->os} | Device:{$s->device_type} | Country:" . ($s->country ?? 'N/A') . " | GPS:" . ($s->latitude ? "{$s->latitude},{$s->longitude}" : 'none') . "\n";
    }
} else {
    echo "No scans yet. Visit http://127.0.0.1:8000/qr/web first.\n";
}
