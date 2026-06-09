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
            if (!Schema::hasColumn('institutes', 'website')) {
                $table->string('website')->nullable()->after('pincode');
            }
            if (!Schema::hasColumn('institutes', 'youtube')) {
                $table->string('youtube')->nullable()->after('website');
            }
            if (!Schema::hasColumn('institutes', 'instagram')) {
                $table->string('instagram')->nullable()->after('youtube');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('institutes', function (Blueprint $table) {
            $table->dropColumn(['website', 'youtube', 'instagram']);
        });
    }
};
