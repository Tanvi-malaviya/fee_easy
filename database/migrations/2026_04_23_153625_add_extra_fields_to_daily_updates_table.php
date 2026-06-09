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
            if (!Schema::hasColumn('daily_updates', 'category')) {
                $table->string('category')->default('Notice')->after('institute_id');
            }
            if (!Schema::hasColumn('daily_updates', 'target_type')) {
                $table->string('target_type')->default('all')->after('category');
            }
            if (!Schema::hasColumn('daily_updates', 'standard')) {
                $table->string('standard')->nullable()->after('target_type');
            }
            if (!Schema::hasColumn('daily_updates', 'attachment')) {
                $table->string('attachment')->nullable()->after('description');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('daily_updates', function (Blueprint $table) {
            $table->dropColumn(['category', 'target_type', 'standard', 'attachment']);
        });
    }
};
