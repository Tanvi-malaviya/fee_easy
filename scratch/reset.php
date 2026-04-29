<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$i = App\Models\Institute::first();
if ($i) {
    $i->password = Hash::make('password123');
    $i->save();
    echo "Password reset to: password123\n";
} else {
    echo "Institute not found!\n";
}
