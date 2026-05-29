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
        // 1. Add institute_code to institutes table
        if (!Schema::hasColumn('institutes', 'institute_code')) {
            Schema::table('institutes', function (Blueprint $table) {
                $table->string('institute_code')->nullable()->after('name');
            });
        }

        // 2. Add enrollment_id to students table
        if (!Schema::hasColumn('students', 'enrollment_id')) {
            Schema::table('students', function (Blueprint $table) {
                $table->string('enrollment_id')->nullable()->unique()->after('id');
            });
        }

        // 3. Populate existing institutes with a default code
        $institutes = DB::table('institutes')->get();
        foreach ($institutes as $inst) {
            $code = strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $inst->institute_name ?? $inst->name), 0, 3));
            if (empty($code)) {
                $code = 'INST' . $inst->id;
            }
            // Ensure uniqueness of the code
            $originalCode = $code;
            $counter = 1;
            while (DB::table('institutes')->where('institute_code', $code)->where('id', '!=', $inst->id)->exists()) {
                $code = $originalCode . $counter;
                $counter++;
            }
            DB::table('institutes')->where('id', $inst->id)->update(['institute_code' => $code]);
        }

        // 4. Populate existing students with a generated enrollment_id
        $students = DB::table('students')->orderBy('id', 'asc')->get();
        $counters = []; // Keep track of serial number per institute-year
        
        foreach ($students as $student) {
            $inst = DB::table('institutes')->where('id', $student->institute_id)->first();
            $code = $inst ? ($inst->institute_code ?? 'INST') : 'INST';
            $year = date('Y', strtotime($student->created_at));
            
            $key = $student->institute_id . '_' . $year;
            if (!isset($counters[$key])) {
                $counters[$key] = 1;
            } else {
                $counters[$key]++;
            }

            $enrollmentId = $year . $code . str_pad($counters[$key], 5, '0', STR_PAD_LEFT);
            
            // Ensure uniqueness
            $originalEnrollmentId = $enrollmentId;
            $suffix = 1;
            while (DB::table('students')->where('enrollment_id', $enrollmentId)->where('id', '!=', $student->id)->exists()) {
                $enrollmentId = $originalEnrollmentId . '_' . $suffix;
                $suffix++;
            }

            DB::table('students')->where('id', $student->id)->update(['enrollment_id' => $enrollmentId]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('students', 'enrollment_id')) {
            Schema::table('students', function (Blueprint $table) {
                $table->dropColumn('enrollment_id');
            });
        }

        if (Schema::hasColumn('institutes', 'institute_code')) {
            Schema::table('institutes', function (Blueprint $table) {
                $table->dropColumn('institute_code');
            });
        }
    }
};
