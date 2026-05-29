<?php
require __DIR__ . '/../../../vendor/autoload.php';
$app = require_once __DIR__ . '/../../../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== PERSONAL ACCESS TOKENS ===\n";
$tokens = \DB::table('personal_access_tokens')->orderBy('id', 'desc')->take(5)->get();
foreach ($tokens as $t) {
    echo "ID: {$t->id} | Name: {$t->name} | Tokenable: {$t->tokenable_type} (#{$t->tokenable_id}) | Last Used: {$t->last_used_at}\n";
}
