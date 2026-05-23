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
        Schema::table('notes', function (Blueprint $table) {
            // Required by SoftDeletes trait on Note model
            if (!Schema::hasColumn('notes', 'deleted_at')) {
                $table->softDeletes();
            }

            // cover_image replaces old 'image' column usage
            if (!Schema::hasColumn('notes', 'cover_image')) {
                $table->string('cover_image')->nullable()->after('content');
            }

            // is_bookmarked flag
            if (!Schema::hasColumn('notes', 'is_bookmarked')) {
                $table->boolean('is_bookmarked')->default(false)->after('cover_image');
            }

            // slug for friendly URLs
            if (!Schema::hasColumn('notes', 'slug')) {
                $table->string('slug')->nullable()->after('title');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notes', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->dropColumn(['cover_image', 'is_bookmarked', 'slug']);
        });
    }
};
