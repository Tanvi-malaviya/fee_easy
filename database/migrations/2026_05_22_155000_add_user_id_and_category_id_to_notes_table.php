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
            // Add user_id foreign key referencing users table
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade')->after('id');
            // Add category_id foreign key referencing note_categories table
            $table->foreignId('category_id')->nullable()->constrained('note_categories')->onDelete('set null')->after('category');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notes', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
            $table->dropForeign(['category_id']);
            $table->dropColumn('category_id');
        });
    }
};
