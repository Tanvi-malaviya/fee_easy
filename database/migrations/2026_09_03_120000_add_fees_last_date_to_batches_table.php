<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Nullable at the DB level so existing batches don't break — but the
     * store/update validation on Batch requires it going forward, so it
     * gets backfilled the next time each batch is edited. The fee reminder
     * automation skips any batch where this is still null (no fallback).
     */
    public function up(): void
    {
        Schema::table('batches', function (Blueprint $table) {
            if (!Schema::hasColumn('batches', 'fees_last_date')) {
                $table->date('fees_last_date')->nullable()->after('fees');
            }
        });
    }

    public function down(): void
    {
        Schema::table('batches', function (Blueprint $table) {
            if (Schema::hasColumn('batches', 'fees_last_date')) {
                $table->dropColumn('fees_last_date');
            }
        });
    }
};
