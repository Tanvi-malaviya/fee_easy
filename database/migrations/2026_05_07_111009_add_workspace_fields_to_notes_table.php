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
            if (!Schema::hasColumn('notes', 'title')) {
                $table->string('title')->after('id')->nullable();
            }
            if (!Schema::hasColumn('notes', 'category')) {
                $table->string('category')->after('title')->nullable();
            }
            if (!Schema::hasColumn('notes', 'image')) {
                $table->string('image')->after('content')->nullable();
            }
            if (!Schema::hasColumn('notes', 'is_archived')) {
                $table->boolean('is_archived')->default(false)->after('image');
            }
            $table->unsignedBigInteger('notable_id')->nullable()->change();
            $table->string('notable_type')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notes', function (Blueprint $table) {
            $table->dropColumn(['title', 'category', 'image', 'is_archived']);
            $table->unsignedBigInteger('notable_id')->change();
            $table->string('notable_type')->change();
        });
    }
};
