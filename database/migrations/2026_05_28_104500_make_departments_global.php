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
        Schema::table('staff_departments', function (Blueprint $table) {
            if (Schema::hasColumn('staff_departments', 'institute_id')) {
                // Drop foreign key and column
                $table->dropForeign(['institute_id']);
                $table->dropColumn('institute_id');
            }
        });
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
