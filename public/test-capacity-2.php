<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(Illuminate\Http\Request::capture());

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

if (!Schema::hasColumn('batches', 'max_capacity')) {
    Schema::table('batches', function (Blueprint $table) {
        $table->integer('max_capacity')->default(30)->after('days');
    });
    echo "Successfully added max_capacity column to batches table.\n";
} else {
    echo "Column max_capacity already exists.\n";
}
