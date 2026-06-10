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
            $dbDriver = Schema::getConnection()->getDriverName();
            $indexExists = false;
            $uniqueExists = false;

            if ($dbDriver === 'mysql') {
                $indexExists = count(\Illuminate\Support\Facades\DB::select("
                    SHOW INDEX FROM staff_salaries WHERE Key_name = 'staff_salaries_staff_id_index'
                ")) > 0;

                $uniqueExists = count(\Illuminate\Support\Facades\DB::select("
                    SHOW INDEX FROM staff_salaries WHERE Key_name = 'staff_salaries_staff_id_month_year_unique'
                ")) > 0;
            } elseif ($dbDriver === 'sqlite') {
                $indexExists = count(\Illuminate\Support\Facades\DB::select("
                    SELECT name FROM sqlite_master WHERE type='index' AND name='staff_salaries_staff_id_index'
                ")) > 0;

                $uniqueExists = count(\Illuminate\Support\Facades\DB::select("
                    SELECT name FROM sqlite_master WHERE type='index' AND name='staff_salaries_staff_id_month_year_unique'
                ")) > 0;
            } else {
                $uniqueExists = true;
            }

            if (!$indexExists) {
                $table->index('staff_id');
            }
            if ($uniqueExists) {
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
