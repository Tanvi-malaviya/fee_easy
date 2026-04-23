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
            $table->string('category')->default('Notice')->after('institute_id');
            $table->string('target_type')->default('all')->after('category');
            $table->string('standard')->nullable()->after('target_type');
            $table->string('attachment')->nullable()->after('description');
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
