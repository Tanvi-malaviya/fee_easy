<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            if (!Schema::hasColumn('students', 'last_low_attendance_alert_at')) {
                $table->timestamp('last_low_attendance_alert_at')->nullable()->after('notification_settings');
            }
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            if (Schema::hasColumn('students', 'last_low_attendance_alert_at')) {
                $table->dropColumn('last_low_attendance_alert_at');
            }
        });
    }
};
