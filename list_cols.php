<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$models = [
    'App\Models\Plan',
    'App\Models\Institute',
    'App\Models\Batch',
    'App\Models\User',
    'App\Models\Student',
    'App\Models\StudentParent',
    'App\Models\Subscription',
    'App\Models\Fee',
    'App\Models\Payment',
    'App\Models\Receipt',
    'App\Models\Attendance',
    'App\Models\DailyUpdate',
    'App\Models\Homework',
    'App\Models\Notification',
    'App\Models\InstituteWhatsappSetting'
];

foreach ($models as $m) {
    if (class_exists($m)) {
        echo $m.': '.implode(',', Illuminate\Support\Facades\Schema::getColumnListing((new $m)->getTable()))."\n";
    } else {
        echo $m.': NOT FOUND\n';
    }
}
