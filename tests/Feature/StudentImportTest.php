<?php

namespace Tests\Feature;

use App\Models\Institute;
use App\Models\Student;
use App\Models\Batch;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class StudentImportTest extends TestCase
{
    use RefreshDatabase;

    protected $institute;

    protected function setUp(): void
    {
        parent::setUp();
        Mail::fake();

        // Create a fully setup institute
        $this->institute = Institute::create([
            'name' => 'Owner Name',
            'email' => 'inst@example.com',
            'phone' => '9876543210',
            'password' => bcrypt('password123'),
            'institute_name' => 'Test Institute',
            'status' => 'active',
            'address' => '123 Main St',
            'city' => 'Ahmedabad',
            'state' => 'Gujarat',
            'country' => 'India',
            'pincode' => '380001',
            'email_verified_at' => now(),
        ]);

        // Create an active subscription
        \App\Models\Subscription::create([
            'institute_id' => $this->institute->id,
            'plan_name' => 'Premium Plan',
            'amount' => 5000,
            'start_date' => now()->subDays(5),
            'end_date' => now()->addDays(360),
            'status' => 'active'
        ]);
    }

    public function test_sample_csv_download(): void
    {
        $response = $this->actingAs($this->institute, 'institute')
            ->get(route('institute.students.import.sample'));

        $response->assertStatus(200);
        $response->assertHeader('Content-Disposition', 'attachment; filename="student_import_template.csv"');
        $this->assertStringContainsString('Name,Email,Phone,Standard', $response->streamedContent());
    }

    public function test_valid_csv_import(): void
    {
        // Create a fake CSV with new 6-column format
        $csvContent = "Name,Email,Phone,Standard,Date of Birth,Guardian Name\n";
        $csvContent .= "Alice Smith,alice@example.com,9876543210,10th,2010-05-15,Bob Smith\n";
        $csvContent .= "Bob Jones,bob@example.com,8765432109,11th,2009-08-20,Tom Jones\n";

        $file = UploadedFile::fake()->createWithContent('students.csv', $csvContent);

        $response = $this->actingAs($this->institute, 'institute')
            ->post(route('institute.students.import'), [
                'file' => $file
            ]);

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'success',
            'message' => '2 students have been successfully imported!'
        ]);

        // Check if database has students
        $this->assertDatabaseHas('students', [
            'name' => 'Alice Smith',
            'email' => 'alice@example.com',
            'phone' => '9876543210',
            'batch_id' => null,
            'standard' => '10th',
            'guardian_name' => 'Bob Smith',
            'monthly_fee' => null
        ]);

        $this->assertDatabaseHas('students', [
            'name' => 'Bob Jones',
            'email' => 'bob@example.com',
            'phone' => '8765432109',
            'batch_id' => null,
            'standard' => '11th',
            'guardian_name' => 'Tom Jones',
            'monthly_fee' => null
        ]);
    }

    public function test_invalid_csv_import_validation_fails(): void
    {
        // Create a fake CSV with errors:
        // - Missing name (row 2)
        // - Invalid email (row 3)
        // - Missing standard (row 4)
        $csvContent = "Name,Email,Phone,Standard,Date of Birth,Guardian Name\n";
        $csvContent .= ",alice@example.com,9876543210,10th,2010-05-15,Bob Smith\n";
        $csvContent .= "Bob Jones,invalid-email,8765432109,11th,2009-08-20,Tom Jones\n";
        $csvContent .= "Charlie Brown,charlie@example.com,7654321098,,2011-12-01,Lucy Brown\n";

        $file = UploadedFile::fake()->createWithContent('students.csv', $csvContent);

        $response = $this->actingAs($this->institute, 'institute')
            ->post(route('institute.students.import'), [
                'file' => $file
            ]);

        $response->assertStatus(422);
        $response->assertJsonStructure([
            'status',
            'message',
            'errors'
        ]);

        $errors = $response->json('errors');
        $this->assertCount(3, $errors);
        $this->assertStringContainsString('Row 2: Name is required.', $errors[0]);
        $this->assertStringContainsString('Row 3: Email format is invalid.', $errors[1]);
        $this->assertStringContainsString('Row 4: Standard is required.', $errors[2]);
    }
}
