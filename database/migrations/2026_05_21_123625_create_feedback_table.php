<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('feedback')) {
            Schema::create('feedback', function (Blueprint $table) {
            $table->id();
            $table->string('user_type');             // 'student' | 'parent'
            $table->unsignedBigInteger('user_id');
            $table->string('rating')->nullable();    // love_it | useful | meh | broken
            $table->text('message')->nullable();
            $table->timestamps();

            $table->index(['user_type', 'user_id']);
        });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('feedback');
    }
};
