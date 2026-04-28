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
        Schema::table('fees', function (Blueprint $table) {
            $table->dropColumn(['month', 'year']);
            $table->date('date')->nullable()->after('status');
        });

        // Use DB statement for ENUM change if modifying existing string column
        // But Laravel 10 supports it with doctrine/dbal, we can try native or DB statement
        // For MySQL:
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE payments MODIFY COLUMN payment_method ENUM('Cash', 'Online') NOT NULL DEFAULT 'Cash'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fees', function (Blueprint $table) {
            $table->dropColumn('date');
            $table->string('month')->nullable();
            $table->string('year')->nullable();
        });

        \Illuminate\Support\Facades\DB::statement("ALTER TABLE payments MODIFY COLUMN payment_method VARCHAR(255) NOT NULL DEFAULT 'Cash'");
    }
};
