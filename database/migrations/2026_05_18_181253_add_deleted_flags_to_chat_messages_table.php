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
        Schema::table('chat_messages', function (Blueprint $table) {
            if (!Schema::hasColumn('chat_messages', 'deleted_by_sender')) {
                $table->boolean('deleted_by_sender')->default(false)->after('received_at');
            }
            if (!Schema::hasColumn('chat_messages', 'deleted_by_receiver')) {
                $table->boolean('deleted_by_receiver')->default(false)->after('deleted_by_sender');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('chat_messages', function (Blueprint $table) {
            $table->dropColumn(['deleted_by_sender', 'deleted_by_receiver']);
        });
    }
};
