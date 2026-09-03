<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('demo_requests', function (Blueprint $table) {
            if (!Schema::hasColumn('demo_requests', 'nurture_stage')) {
                $table->unsignedTinyInteger('nurture_stage')->default(0)->after('status');
            }
            if (!Schema::hasColumn('demo_requests', 'nurture_last_sent_at')) {
                $table->timestamp('nurture_last_sent_at')->nullable()->after('nurture_stage');
            }
        });
    }

    public function down(): void
    {
        Schema::table('demo_requests', function (Blueprint $table) {
            $cols = [];
            if (Schema::hasColumn('demo_requests', 'nurture_stage')) $cols[] = 'nurture_stage';
            if (Schema::hasColumn('demo_requests', 'nurture_last_sent_at')) $cols[] = 'nurture_last_sent_at';
            if (!empty($cols)) $table->dropColumn($cols);
        });
    }
};
