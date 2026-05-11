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
        Schema::table('staff_salaries', function (Blueprint $table) {
            // First add a regular index to staff_id so the foreign key stays happy
            $table->index('staff_id');
            // Then drop the unique constraint
            $table->dropUnique(['staff_id', 'month', 'year']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('staff_salaries', function (Blueprint $table) {
            $table->unique(['staff_id', 'month', 'year']);
            $table->dropIndex(['staff_id']);
        });
    }
};
