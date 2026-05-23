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
            // institute_id should be nullable — notes can belong to a user without an institute
            $table->unsignedBigInteger('institute_id')->nullable()->change();

            // content should be nullable — users may save a note with just a title
            $table->text('content')->nullable()->change();

            // is_archived should be nullable with default false
            $table->boolean('is_archived')->nullable()->default(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notes', function (Blueprint $table) {
            $table->unsignedBigInteger('institute_id')->nullable(false)->change();
            $table->text('content')->nullable(false)->change();
        });
    }
};
