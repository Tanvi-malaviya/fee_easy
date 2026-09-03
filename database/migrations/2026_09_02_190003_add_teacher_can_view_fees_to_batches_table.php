<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('batches', function (Blueprint $table) {
            if (!Schema::hasColumn('batches', 'teacher_can_view_fees')) {
                $table->boolean('teacher_can_view_fees')->default(false)->after('staff_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('batches', function (Blueprint $table) {
            if (Schema::hasColumn('batches', 'teacher_can_view_fees')) {
                $table->dropColumn('teacher_can_view_fees');
            }
        });
    }
};
