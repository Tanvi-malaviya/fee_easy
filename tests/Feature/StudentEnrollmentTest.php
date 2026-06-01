<?php

namespace Tests\Feature;

use App\Models\Institute;
use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentEnrollmentTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_enrollment_id_is_automatically_generated(): void
    {
        // 1. Create an Institute with an explicit code
        $institute = Institute::create([
            'name' => 'Test Owner',
            'email' => 'testinst@example.com',
            'phone' => '1234567890',
            'password' => bcrypt('password123'),
            'institute_name' => 'Alpha Academy',
            'institute_code' => 'ALP',
            'status' => 1,
        ]);

        // 2. Create first student
        $student1 = Student::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '1234567890',
            'password' => bcrypt('password123'),
            'institute_id' => $institute->id,
            'status' => 1,
        ]);

        // 3. Create second student
        $student2 = Student::create([
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'phone' => '1234567890',
            'password' => bcrypt('password123'),
            'institute_id' => $institute->id,
            'status' => 1,
        ]);

        $year = date('Y');
        $this->assertEquals($year . 'ALP00001', $student1->enrollment_id);
        $this->assertEquals($year . 'ALP00002', $student2->enrollment_id);
    }

    public function test_institute_code_is_automatically_generated_on_creation(): void
    {
        $institute = Institute::create([
            'name' => 'Test Owner',
            'email' => 'testinst_random@example.com',
            'phone' => '1234567890',
            'password' => bcrypt('password123'),
            'institute_name' => 'Beta Academy',
            'status' => 1,
        ]);

        $this->assertNotEmpty($institute->institute_code);
        $this->assertMatchesRegularExpression('/^[1-9]\d{5}$/', $institute->institute_code);
        $this->assertEquals(6, strlen($institute->institute_code));
    }
}
