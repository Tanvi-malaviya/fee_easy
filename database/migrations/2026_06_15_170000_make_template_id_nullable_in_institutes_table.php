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
        Schema::table('institutes', function (Blueprint $table) {
            if (!Schema::hasColumn('institutes', 'template_id')) {
                $table->integer('template_id')->nullable()->default(null);
            } else {
                $table->integer('template_id')->nullable()->default(null)->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('institutes', function (Blueprint $table) {
            if (Schema::hasColumn('institutes', 'template_id')) {
                $table->dropColumn('template_id');
            }
        });
    }
};
