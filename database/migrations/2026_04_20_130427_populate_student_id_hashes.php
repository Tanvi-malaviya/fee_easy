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
        $students = \App\Models\Student::whereNull('id_hash')->get();
        foreach ($students as $student) {
            $student->update([
                'id_hash' => \Illuminate\Support\Str::random(32)
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No easy way to reverse this without losing the hashes, 
        // which is fine for this utility migration.
    }
};
