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
        Schema::table('batches', function (Blueprint $table) {
            if (!Schema::hasColumn('batches', 'description')) {
                $table->text('description')->nullable()->after('subject');
            }
            if (!Schema::hasColumn('batches', 'fees')) {
                $table->decimal('fees', 10, 2)->nullable()->after('description');
            }
        });
    }

    public function down(): void
    {
        Schema::table('batches', function (Blueprint $table) {
            $table->dropColumn(['description', 'fees']);
        });
    }
};
