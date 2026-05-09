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
        if (!Schema::hasColumn('staff_departments', 'institute_id')) {
            Schema::table('staff_departments', function (Blueprint $table) {
                $table->foreignId('institute_id')->constrained('institutes')->onDelete('cascade');
            });
        }

        if (!Schema::hasColumn('staff_roles', 'institute_id')) {
            Schema::table('staff_roles', function (Blueprint $table) {
                $table->foreignId('institute_id')->constrained('institutes')->onDelete('cascade');
            });
        }

        if (!Schema::hasColumn('staff', 'institute_id')) {
            Schema::table('staff', function (Blueprint $table) {
                $table->foreignId('institute_id')->constrained('institutes')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('staff', function (Blueprint $table) {
            $table->dropForeign(['institute_id']);
            $table->dropColumn('institute_id');
        });

        Schema::table('staff_roles', function (Blueprint $table) {
            $table->dropForeign(['institute_id']);
            $table->dropColumn('institute_id');
        });

        Schema::table('staff_departments', function (Blueprint $table) {
            $table->dropForeign(['institute_id']);
            $table->dropColumn('institute_id');
        });
    }
};
