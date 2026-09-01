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
        Schema::table('daily_updates', function (Blueprint $table) {
            if (Schema::hasColumn('daily_updates', 'topic')) {
                $table->dropColumn('topic');
            }
            if (!Schema::hasColumn('daily_updates', 'student_id')) {
                $table->unsignedBigInteger('student_id')->nullable()->after('institute_id');
            }
            if (Schema::hasColumn('daily_updates', 'data')) {
                $table->dropColumn('data');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('daily_updates', function (Blueprint $table) {
            $table->dropColumn('student_id');
            $table->string('topic')->after('institute_id');
            
            if (!Schema::hasColumn('daily_updates', 'data')) {
                $table->text('data')->nullable();
            }
        });
    }
};
