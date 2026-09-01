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
        if (!Schema::hasTable('staff')) {
            Schema::create('staff', function (Blueprint $table) {
            $table->id();
            $table->string('employee_id')->unique();
            $table->string('full_name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->foreignId('staff_role_id')->constrained('staff_roles');
            $table->foreignId('staff_department_id')->constrained('staff_departments');
            $table->enum('employment_type', ['Salary', 'Hourly'])->default('Salary');
            $table->decimal('base_salary', 15, 2)->default(0);
            $table->enum('status', ['active', 'away', 'offline'])->default('active');
            $table->string('profile_image')->nullable();
            $table->timestamps();
        });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff');
    }
};
