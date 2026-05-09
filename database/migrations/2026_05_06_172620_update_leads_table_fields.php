<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Use manual ALTER for broader compatibility
        DB::statement('ALTER TABLE leads CHANGE name full_name VARCHAR(255) NOT NULL');
        DB::statement('ALTER TABLE leads CHANGE course_interest course_selection VARCHAR(255) NULL');

        Schema::table('leads', function (Blueprint $table) {
            $table->text('address')->nullable()->after('email');
            $table->string('reference')->nullable()->after('course_selection');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropColumn(['address', 'reference']);
        });
        
        DB::statement('ALTER TABLE leads CHANGE full_name name VARCHAR(255) NOT NULL');
        DB::statement('ALTER TABLE leads CHANGE course_selection course_interest VARCHAR(255) NULL');
    }
};
