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
        Schema::create('device_sessions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('institute_id');
            $table->unsignedBigInteger('token_id')->nullable();
            $table->string('device')->nullable();
            $table->string('os')->nullable();
            $table->timestamp('last_login')->nullable();
            $table->timestamp('last_open')->nullable();
            $table->text('fcm_token')->nullable();
            $table->timestamps();

            $table->foreign('institute_id')->references('id')->on('institutes')->onDelete('cascade');
            $table->foreign('token_id')->references('id')->on('personal_access_tokens')->onDelete('cascade');
        });

        Schema::table('institutes', function (Blueprint $table) {
            if (Schema::hasColumn('institutes', 'fcm_token')) {
                $table->dropColumn('fcm_token');
            }
            if (Schema::hasColumn('institutes', 'login_device')) {
                $table->dropColumn('login_device');
            }
            if (Schema::hasColumn('institutes', 'os')) {
                $table->dropColumn('os');
            }
            if (Schema::hasColumn('institutes', 'last_login')) {
                $table->dropColumn('last_login');
            }
            if (Schema::hasColumn('institutes', 'last_open')) {
                $table->dropColumn('last_open');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('institutes', function (Blueprint $table) {
            $table->text('fcm_token')->nullable();
            $table->string('login_device')->nullable();
            $table->string('os')->nullable();
            $table->timestamp('last_login')->nullable();
            $table->timestamp('last_open')->nullable();
        });

        Schema::dropIfExists('device_sessions');
    }
};
