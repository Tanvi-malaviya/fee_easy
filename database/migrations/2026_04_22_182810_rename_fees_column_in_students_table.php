<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        \DB::statement('ALTER TABLE students CHANGE fees monthly_fee DECIMAL(10, 2) NULL');
    }

    public function down(): void
    {
        \DB::statement('ALTER TABLE students CHANGE monthly_fee fees DECIMAL(10, 2) NULL');
    }
};
