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
            $sm = Schema::getConnection()->getDoctrineSchemaManager();
            $indexes = $sm->listTableIndexes('staff_salaries');
            if (!isset($indexes['staff_salaries_staff_id_index'])) {
                $table->index('staff_id');
            }
            if (isset($indexes['staff_salaries_staff_id_month_year_unique'])) {
                $table->dropUnique(['staff_id', 'month', 'year']);
            }
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
