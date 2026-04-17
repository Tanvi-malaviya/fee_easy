<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$institute = App\Models\Institute::find(39);
echo "Institute Class: ".get_class($institute)."\n";
echo "Institute ID: ".$institute->id."\n";

$batch_id = 3;
$batch = App\Models\Batch::where('id', $batch_id)
            ->where('institute_id', $institute->id)
            ->first();

if (!$batch) {
    echo "Batch query returned NULL.\n";
    $query = App\Models\Batch::where('id', $batch_id)->where('institute_id', $institute->id)->toSql();
    $bindings = App\Models\Batch::where('id', $batch_id)->where('institute_id', $institute->id)->getBindings();
    echo "Query: $query\n";
    echo "Bindings: ".json_encode($bindings)."\n";
    
    echo "All batches in DB:\n";
    foreach(App\Models\Batch::all() as $b) {
        echo "ID: {$b->id}, InstID: {$b->institute_id}\n";
    }
} else {
    echo "Batch FOUND: ID: ".$batch->id.", InstID: ".$batch->institute_id."\n";
}
