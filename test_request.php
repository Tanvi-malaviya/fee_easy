<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;

// Create a dummy uploaded file
$tempFile = tempnam(sys_get_temp_dir(), 'test');
file_put_contents($tempFile, 'test content');
$file = new UploadedFile($tempFile, 'test.pdf', 'application/pdf', null, true);

// Create request with file
$request = Request::create('/test', 'POST', [], [], ['attachment' => $file]);

echo "Before merge:\n";
echo "hasFile: " . ($request->hasFile('attachment') ? 'YES' : 'NO') . "\n";
echo "file(): " . ($request->file('attachment') instanceof UploadedFile ? 'YES' : 'NO') . "\n";

// Perform the merge similar to the controller
$cleanData = [];
foreach ($request->all() as $key => $value) {
    $cleanData[trim($key)] = $value;
}
$request->merge($cleanData);

echo "After merge:\n";
echo "hasFile: " . ($request->hasFile('attachment') ? 'YES' : 'NO') . "\n";
echo "file(): " . ($request->file('attachment') instanceof UploadedFile ? 'YES' : 'NO') . "\n";
echo "allFiles count: " . count($request->allFiles()) . "\n";
unlink($tempFile);
