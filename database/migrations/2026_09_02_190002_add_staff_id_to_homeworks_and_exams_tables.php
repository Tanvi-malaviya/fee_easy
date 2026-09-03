<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('homeworks', function (Blueprint $table) {
            if (!Schema::hasColumn('homeworks', 'staff_id')) {
                $table->foreignId('staff_id')->nullable()->after('institute_id')
                    ->constrained('staff')->nullOnDelete();
            }
        });

        Schema::table('exams', function (Blueprint $table) {
            if (!Schema::hasColumn('exams', 'staff_id')) {
                $table->foreignId('staff_id')->nullable()->after('institute_id')
                    ->constrained('staff')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('homeworks', function (Blueprint $table) {
            if (Schema::hasColumn('homeworks', 'staff_id')) {
                $table->dropConstrainedForeignId('staff_id');
            }
        });

        Schema::table('exams', function (Blueprint $table) {
            if (Schema::hasColumn('exams', 'staff_id')) {
                $table->dropConstrainedForeignId('staff_id');
            }
        });
    }
};
