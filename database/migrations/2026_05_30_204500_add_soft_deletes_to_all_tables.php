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
        $tables = [
            'institutes',
            'batches',
            'parents',
            'students',
            'fees',
            'payments',
            'attendance',
            'daily_updates',
            'homeworks',
            'homework_submissions',
            'teachers',
            'teacher_attendances',
            'expense_categories',
            'expenses',
            'leads',
            'lead_notes',
            'staff_departments',
            'staff_roles',
            'staff',
            'staff_attendances',
            'staff_salaries',
            'resources',
            'demo_requests',
            'chat_messages',
            'community_messages',
            'feedback',
        ];

        foreach ($tables as $table) {
            if (Schema::hasTable($table) && !Schema::hasColumn($table, 'deleted_at')) {
                Schema::table($table, function (Blueprint $table) {
                    $table->softDeletes();
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = [
            'institutes',
            'batches',
            'parents',
            'students',
            'fees',
            'payments',
            'attendance',
            'daily_updates',
            'homeworks',
            'homework_submissions',
            'teachers',
            'teacher_attendances',
            'expense_categories',
            'expenses',
            'leads',
            'lead_notes',
            'staff_departments',
            'staff_roles',
            'staff',
            'staff_attendances',
            'staff_salaries',
            'resources',
            'demo_requests',
            'chat_messages',
            'community_messages',
            'feedback',
        ];

        foreach ($tables as $table) {
            if (Schema::hasTable($table) && Schema::hasColumn($table, 'deleted_at')) {
                Schema::table($table, function (Blueprint $table) {
                    $table->dropSoftDeletes();
                });
            }
        }
    }
};
