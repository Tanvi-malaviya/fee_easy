<?php
// This is a scratch script to verify the new API endpoints

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

// 1. Create a test user if not exists
$user = User::firstOrCreate(
    ['email' => 'testapi@example.com'],
    [
        'name' => 'Test API User',
        'password' => Hash::make('password123'),
    ]
);

echo "Test User created/found: " . $user->email . "\n";

// 2. Test Login
echo "\nTesting /api/v1/auth/login...\n";
$response = $kernel->handle(
    $request = Request::create('/api/v1/auth/login', 'POST', [
        'email' => 'testapi@example.com',
        'password' => 'password123',
        'device_name' => 'TestDevice'
    ])
);

echo "Status: " . $response->getStatusCode() . "\n";
$data = json_decode($response->getContent(), true);
print_r($data);

if ($response->getStatusCode() == 200 && isset($data['data']['token'])) {
    $token = $data['data']['token'];
    echo "Login successful! Token: $token\n";

    // 3. Test Profile
    echo "\nTesting /api/v1/auth/profile...\n";
    $request = Request::create('/api/v1/auth/profile', 'GET');
    $request->headers->set('Authorization', 'Bearer ' . $token);
    $request->headers->set('Accept', 'application/json');
    $response = $kernel->handle($request);
    
    echo "Status: " . $response->getStatusCode() . "\n";
    print_r(json_decode($response->getContent(), true));

    // 4. Test Logout
    echo "\nTesting /api/v1/auth/logout...\n";
    $request = Request::create('/api/v1/auth/logout', 'POST');
    $request->headers->set('Authorization', 'Bearer ' . $token);
    $request->headers->set('Accept', 'application/json');
    $response = $kernel->handle($request);
    
    echo "Status: " . $response->getStatusCode() . "\n";
    print_r(json_decode($response->getContent(), true));
} else {
    echo "Login failed!\n";
}

// Clean up test user
$user->delete();
echo "\nTest User cleaned up.\n";
