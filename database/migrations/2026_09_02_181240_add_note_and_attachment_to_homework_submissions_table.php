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
        Schema::table('homework_submissions', function (Blueprint $table) {
            if (!Schema::hasColumn('homework_submissions', 'note')) {
                $table->text('note')->nullable()->after('status');
            }
            if (!Schema::hasColumn('homework_submissions', 'attachment')) {
                $table->string('attachment')->nullable()->after('note');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('homework_submissions', function (Blueprint $table) {
            $table->dropColumn(['note', 'attachment']);
        });
    }
};
