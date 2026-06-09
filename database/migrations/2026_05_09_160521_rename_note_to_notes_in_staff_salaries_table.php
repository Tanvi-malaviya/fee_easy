<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasColumn('staff_salaries', 'note') && !Schema::hasColumn('staff_salaries', 'notes')) {
            DB::statement("ALTER TABLE staff_salaries CHANGE note notes TEXT NULL");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE staff_salaries CHANGE notes note TEXT NULL");
    }
};
