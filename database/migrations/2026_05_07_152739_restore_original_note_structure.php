<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('notes', function (Blueprint $table) {
            $table->foreignId('institute_id')->nullable()->after('user_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('notable_id')->nullable()->after('institute_id');
            $table->string('notable_type')->nullable()->after('notable_id');
            $table->string('category')->nullable()->after('category_id');
        });
    }

    public function down(): void
    {
        Schema::table('notes', function (Blueprint $table) {
            $table->dropColumn(['institute_id', 'notable_id', 'notable_type', 'category']);
        });
    }
};
