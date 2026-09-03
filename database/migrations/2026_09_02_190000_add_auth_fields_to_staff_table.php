<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('staff', function (Blueprint $table) {
            if (!Schema::hasColumn('staff', 'must_change_password')) {
                $table->boolean('must_change_password')->default(true)->after('password');
            }
            if (!Schema::hasColumn('staff', 'is_login_blocked')) {
                $table->boolean('is_login_blocked')->default(false)->after('must_change_password');
            }
            if (!Schema::hasColumn('staff', 'remember_token')) {
                $table->rememberToken()->after('is_login_blocked');
            }
        });
    }

    public function down(): void
    {
        Schema::table('staff', function (Blueprint $table) {
            foreach (['must_change_password', 'is_login_blocked', 'remember_token'] as $column) {
                if (Schema::hasColumn('staff', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
