<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\StaffDepartment;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('staff') && !Schema::hasColumn('staff', 'password')) {
            Schema::table('staff', function (Blueprint $table) {
                $table->string('password')->nullable()->after('email');
            });
        }

        // Ensure "Faculty / Teacher" department exists
        StaffDepartment::firstOrCreate(
            ['name' => 'Faculty / Teacher']
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('staff') && Schema::hasColumn('staff', 'password')) {
            Schema::table('staff', function (Blueprint $table) {
                $table->dropColumn('password');
            });
        }
    }
};
