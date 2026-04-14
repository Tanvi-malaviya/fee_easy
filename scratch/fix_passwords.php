<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$app->boot();

use App\Models\Student;
use App\Models\StudentParent;
use Illuminate\Support\Facades\Hash;

Student::whereNull('password')->orWhere('password', '')->update(['password' => Hash::make('password')]);
StudentParent::whereNull('password')->orWhere('password', '')->update(['password' => Hash::make('password')]);

echo "Passwords updated for Students and Parents successfully!\n";
