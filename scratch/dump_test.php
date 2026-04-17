<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$institute = App\Models\Institute::where('email', 'institute@test.com')->first();
echo "Institute ID: " . $institute->id . "\n";
echo "Batches:\n";
foreach(App\Models\Batch::all() as $batch) {
    echo "Batch ID: " . $batch->id . ", Institute ID: " . $batch->institute_id . "\n";
}
