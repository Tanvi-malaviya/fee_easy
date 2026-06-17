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
        Schema::create('institute_website_contents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('institute_id')->constrained('institutes')->onDelete('cascade');
            $table->json('hero_slides')->nullable(); // multiple data in array
            $table->text('about_vision')->nullable(); // single data
            $table->text('about_mission')->nullable(); // single data
            $table->text('about_values')->nullable(); // single data
            $table->json('achievements')->nullable(); // multiple data in array
            $table->json('gallery')->nullable(); // multiple data in array
            $table->string('facebook')->nullable(); // single data
            $table->string('twitter')->nullable(); // single data
            $table->string('linkedin')->nullable(); // single data
            $table->string('instagram')->nullable(); // single data
            $table->string('youtube')->nullable(); // single data
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('institute_website_contents');
    }
};
