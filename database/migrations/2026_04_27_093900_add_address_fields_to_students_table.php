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
        Schema::table('students', function (Blueprint $table) {
            if (!Schema::hasColumn('students', 'address_line_1')) {
                $table->string('address_line_1')->nullable();
            }
            if (!Schema::hasColumn('students', 'address_line_2')) {
                $table->string('address_line_2')->nullable();
            }
            if (!Schema::hasColumn('students', 'city')) {
                $table->string('city')->nullable();
            }
            if (!Schema::hasColumn('students', 'state')) {
                $table->string('state')->nullable();
            }
            if (!Schema::hasColumn('students', 'country')) {
                $table->string('country')->nullable();
            }
            if (!Schema::hasColumn('students', 'pincode')) {
                $table->string('pincode')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn(['address_line_1', 'address_line_2', 'city', 'state', 'country', 'pincode']);
        });
    }
};
