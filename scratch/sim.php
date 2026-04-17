<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$institute = App\Models\Institute::find(39);
$batch = App\Models\Batch::where('id', 3)->where('institute_id', $institute->id)->first();
echo "Script Test:\n";
echo "Batch 3 for Institute 39 via Eloquent: " . ($batch ? 'FOUND' : 'NOT FOUND') . "\n";

// Is there a global scope on Batch? Let's check
echo "Does Batch have global scopes? " . count((new App\Models\Batch())->getGlobalScopes()) . "\n";

// Let's create an HTTP Request
$request = Illuminate\Http\Request::create('/api/v1/institute/homeworks', 'POST', [
    'batch_id' => 3,
    'title' => 'Test',
    'description' => 'Test',
    'due_date' => '2026-04-18'
]);
$request->setUserResolver(function () use ($institute) {
    return $institute;
});

// Since validation requires JSON, let's fake it
$request->headers->set('Accept', 'application/json');

// Get route
$router = app('router');
try {
    $response = $kernel->handle($request);
    echo "Response Status: " . $response->getStatusCode() . "\n";
    echo "Response Content: " . $response->getContent() . "\n";
} catch (\Exception $e) {
    echo "Exception: " . $e->getMessage() . "\n";
}
