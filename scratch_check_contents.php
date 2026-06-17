<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$institutes = DB::table('institutes')->get();
foreach ($institutes as $inst) {
    echo "ID: {$inst->id}, Name: {$inst->name}, Code: {$inst->institute_code}, Slug: " . Illuminate\Support\Str::slug($inst->institute_name ?? $inst->name) . "\n";
    $content = DB::table('institute_website_contents')->where('institute_id', $inst->id)->first();
    if ($content) {
        echo "  Gallery: " . ($content->gallery ?? 'null') . "\n";
    } else {
        echo "  No Content row.\n";
    }
}
