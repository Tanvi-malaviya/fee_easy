<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('institutes', function (Blueprint $table) {
            if (!Schema::hasColumn('institutes', 'upi_id')) {
                $table->string('upi_id')->nullable()->after('fcm_token');
            }
            if (!Schema::hasColumn('institutes', 'upi_qr_code')) {
                $table->string('upi_qr_code')->nullable()->after('upi_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('institutes', function (Blueprint $table) {
            $table->dropColumn(['upi_id', 'upi_qr_code']);
        });
    }
};
