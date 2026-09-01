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
        Schema::table('institute_website_contents', function (Blueprint $table) {
            if (!Schema::hasColumn('institute_website_contents', 'events')) {
                $table->json('events')->nullable()->after('gallery');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('institute_website_contents', function (Blueprint $table) {
            if (Schema::hasColumn('institute_website_contents', 'events')) {
                $table->dropColumn('events');
            }
        });
    }
};
