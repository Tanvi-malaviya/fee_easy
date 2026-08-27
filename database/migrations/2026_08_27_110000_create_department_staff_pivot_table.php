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
        if (!Schema::hasTable('department_staff')) {
            Schema::create('department_staff', function (Blueprint $table) {
                $table->id();
                $table->foreignId('staff_id')->constrained('staff')->onDelete('cascade');
                $table->foreignId('staff_department_id')->constrained('staff_departments')->onDelete('cascade');
                $table->timestamps();

                $table->unique(['staff_id', 'staff_department_id']);
            });
        }

        // Migrate existing staff department relationships
        if (Schema::hasColumn('staff', 'staff_department_id')) {
            $existingStaff = DB::table('staff')->whereNotNull('staff_department_id')->get(['id', 'staff_department_id']);
            foreach ($existingStaff as $st) {
                DB::table('department_staff')->insertOrIgnore([
                    'staff_id' => $st->id,
                    'staff_department_id' => $st->staff_department_id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            Schema::table('staff', function (Blueprint $table) {
                $table->unsignedBigInteger('staff_department_id')->nullable()->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('department_staff');
    }
};
