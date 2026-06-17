<?php
include 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$inst = \App\Models\Institute::find(36);
if ($inst) {
    echo "Institute found!\n";
    $content = $inst->websiteContent;
    if ($content) {
        echo "WebsiteContent found!\n";
        echo "events type: " . gettype($content->events) . "\n";
        echo "events value:\n";
        print_r($content->events);
        echo "\n";
    } else {
        echo "No WebsiteContent found!\n";
    }
} else {
    echo "Institute not found!\n";
}
