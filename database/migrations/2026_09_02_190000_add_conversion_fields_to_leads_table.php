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
        Schema::table('leads', function (Blueprint $table) {
            if (!Schema::hasColumn('leads', 'converted_student_id')) {
                $table->foreignId('converted_student_id')->nullable()->after('status')
                    ->constrained('students')->nullOnDelete();
            }
            if (!Schema::hasColumn('leads', 'converted_at')) {
                $table->timestamp('converted_at')->nullable()->after('converted_student_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropConstrainedForeignId('converted_student_id');
            $table->dropColumn('converted_at');
        });
    }
};
