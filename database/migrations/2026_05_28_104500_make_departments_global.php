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
        if (Schema::hasColumn('staff_departments', 'institute_id')) {
            $dbDriver = Schema::getConnection()->getDriverName();
            $foreignExists = true;
            if ($dbDriver === 'mysql') {
                $foreignExists = count(\Illuminate\Support\Facades\DB::select("
                    SELECT CONSTRAINT_NAME 
                    FROM information_schema.KEY_COLUMN_USAGE 
                    WHERE TABLE_SCHEMA = DATABASE() 
                      AND TABLE_NAME = 'staff_departments' 
                      AND COLUMN_NAME = 'institute_id' 
                      AND CONSTRAINT_NAME = 'staff_departments_institute_id_foreign'
                ")) > 0;
            }

            Schema::table('staff_departments', function (Blueprint $table) use ($foreignExists) {
                if ($foreignExists) {
                    $table->dropForeign(['institute_id']);
                }
                $table->dropColumn('institute_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('staff_departments', function (Blueprint $table) {
            if (!Schema::hasColumn('staff_departments', 'institute_id')) {
                $table->foreignId('institute_id')->nullable()->constrained('institutes')->onDelete('cascade');
            }
        });
    }
};
