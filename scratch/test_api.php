<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->bootstrap();

use App\Models\Institute;
use Illuminate\Http\Request;

$institute = Institute::find(4);
if (!$institute) {
    echo "Institute 4 not found!\n";
    exit;
}

$request = Request::create('/api/v1/institute/reports/dashboard', 'GET');
$request->headers->set('Accept', 'application/json');
app()->instance('request', $request);

// Log in the user
Illuminate\Support\Facades\Auth::guard('institute')->login($institute);

$request->setUserResolver(function() use ($institute) {
    return $institute;
});

try {
    $response = app()->handle($request);
    echo "Status code: " . $response->getStatusCode() . "\n";
    echo "Content: " . $response->getContent() . "\n";
} catch (\Throwable $e) {
    echo "Exception: " . $e->getMessage() . "\n";
    echo "Trace:\n" . $e->getTraceAsString() . "\n";
}
