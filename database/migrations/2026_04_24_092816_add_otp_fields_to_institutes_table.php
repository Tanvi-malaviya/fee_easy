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
            if (!Schema::hasColumn('institutes', 'otp')) {
                $table->string('otp')->nullable()->after('password');
            }
            if (!Schema::hasColumn('institutes', 'otp_expires_at')) {
                $table->timestamp('otp_expires_at')->nullable()->after('otp');
            }
            if (!Schema::hasColumn('institutes', 'email_verified_at')) {
                $table->timestamp('email_verified_at')->nullable()->after('otp_expires_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('institutes', function (Blueprint $table) {
            $table->dropColumn(['otp', 'otp_expires_at', 'email_verified_at']);
        });
    }
};
