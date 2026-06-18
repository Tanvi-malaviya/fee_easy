<?php

namespace App\Services;

use App\Models\Student;
use App\Models\Batch;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class StudentImportService
{
    /**
     * Import students from a CSV file.
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @param \App\Models\Institute $institute
     * @return array
     */
    public function import($file, $institute)
    {
        $filePath = $file->getRealPath();
        $rows = [];

        if (($handle = fopen($filePath, 'r')) !== false) {
            // Read headers
            $headers = fgetcsv($handle, 1000, ',');
            
            // Clean headers (trim whitespace, remove UTF-8 BOM if present)
            if ($headers) {
                $headers[0] = preg_replace('/[\x{00EF}\x{00BB}\x{00BF}]/u', '', $headers[0]); // remove BOM
                $headers = array_map('trim', $headers);
            }

            // Read data rows
            while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                // Skip empty rows
                if (count(array_filter($data)) === 0) {
                    continue;
                }
                $rows[] = $data;
            }
            fclose($handle);
        }

        if (empty($rows)) {
            return [
                'status' => 'error',
                'message' => 'The uploaded CSV file is empty or invalid.',
                'errors' => ['The uploaded CSV file contains no data rows.']
            ];
        }

        // Map headers to indices
        $headerMap = [];
        if ($headers) {
            foreach ($headers as $index => $headerName) {
                $cleanName = strtolower(trim($headerName));
                $headerMap[$cleanName] = $index;
            }
        }

        $getIndex = function ($keys, $defaultIndex) use ($headerMap) {
            foreach ($keys as $key) {
                if (isset($headerMap[$key])) {
                    return $headerMap[$key];
                }
            }
            return $defaultIndex;
        };

        $nameIdx = $getIndex(['name'], 0);
        $emailIdx = $getIndex(['email'], 1);
        $phoneIdx = $getIndex(['phone'], 2);
        $standardIdx = $getIndex(['standard'], 3);
        $dobIdx = $getIndex(['date of birth', 'dob', 'date of birth (yyyy-mm-dd)'], 4);
        $guardianIdx = $getIndex(['guardian name', 'guardian'], 5);
        $feeIdx = $getIndex(['monthly fee', 'fee'], -1);

        $errors = [];
        $validatedData = [];

        // Prefetch all batches for this institute to avoid query inside loop
        $batches = Batch::where('institute_id', $institute->id)->pluck('id', 'name')->toArray();
        $batchesLower = [];
        foreach ($batches as $name => $id) {
            $batchesLower[strtolower(trim($name))] = $id;
        }

        // Track emails in this CSV to prevent duplicates within the file itself
        $emailsInCsv = [];

        foreach ($rows as $rowIndex => $row) {
            $rowNum = $rowIndex + 2; // Row 1 is headers

            $name = ($nameIdx !== -1 && isset($row[$nameIdx])) ? trim($row[$nameIdx]) : '';
            $email = ($emailIdx !== -1 && isset($row[$emailIdx])) ? trim($row[$emailIdx]) : '';
            $phone = ($phoneIdx !== -1 && isset($row[$phoneIdx])) ? trim($row[$phoneIdx]) : '';
            $standard = ($standardIdx !== -1 && isset($row[$standardIdx])) ? trim($row[$standardIdx]) : '';
            $dob = ($dobIdx !== -1 && isset($row[$dobIdx])) ? trim($row[$dobIdx]) : '';
            $guardianName = ($guardianIdx !== -1 && isset($row[$guardianIdx])) ? trim($row[$guardianIdx]) : '';
            $monthlyFee = ($feeIdx !== -1 && isset($row[$feeIdx])) ? trim($row[$feeIdx]) : '';

            $rowErrors = [];

            // 1. Validate Name
            if (empty($name)) {
                $rowErrors[] = "Name is required.";
            }

            // 2. Validate Email
            if (empty($email)) {
                $rowErrors[] = "Email is required.";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $rowErrors[] = "Email format is invalid.";
            } elseif (in_array(strtolower($email), $emailsInCsv)) {
                $rowErrors[] = "Email is duplicated in the CSV file.";
            } else {
                $emailsInCsv[] = strtolower($email);
                // Check database unique
                if (Student::where('email', $email)->exists()) {
                    $rowErrors[] = "Email already exists in the system.";
                }
            }

            // 3. Validate Phone
            if (empty($phone)) {
                $rowErrors[] = "Phone number is required.";
            } elseif (!is_numeric($phone) || strlen($phone) !== 10) {
                $rowErrors[] = "Phone number must be exactly 10 digits.";
            }

            // 4. Validate Standard
            if (empty($standard)) {
                $rowErrors[] = "Standard is required.";
            }

            // 5. Validate DOB
            if (empty($dob)) {
                $rowErrors[] = "Date of Birth is required.";
            } else {
                try {
                    $parsedDate = Carbon::parse($dob);
                    if ($parsedDate->isAfter(Carbon::today())) {
                        $rowErrors[] = "Date of Birth cannot be in the future.";
                    }
                } catch (\Exception $e) {
                    $rowErrors[] = "Date of Birth format is invalid (use YYYY-MM-DD).";
                }
            }

            // 6. Validate Guardian Name
            if (empty($guardianName)) {
                $rowErrors[] = "Guardian Name is required.";
            }

            // 7. Validate Monthly Fee
            if ($monthlyFee !== '') {
                if (!is_numeric($monthlyFee) || $monthlyFee < 0) {
                    $rowErrors[] = "Monthly Fee must be a positive number.";
                }
            }

            // 8. Validate Batch
            // $batchId = null;
            // if ($batchName !== '') {
            //     $batchKey = strtolower(trim($batchName));
            //     if (isset($batchesLower[$batchKey])) {
            //         $batchId = $batchesLower[$batchKey];
            //     } else {
            //         $rowErrors[] = "Batch '$batchName' does not exist in your institute.";
            //     }
            // }

            if (!empty($rowErrors)) {
                foreach ($rowErrors as $err) {
                    $errors[] = "Row {$rowNum}: {$err}";
                }
            } else {
                $validatedData[] = [
                    'name' => $name,
                    'email' => $email,
                    'phone' => $phone,
                    // 'batch_id' => $batchId,
                    'standard' => $standard,
                    'dob' => Carbon::parse($dob)->format('Y-m-d'),
                    'guardian_name' => $guardianName,
                    'monthly_fee' => $monthlyFee !== '' ? floatval($monthlyFee) : null,
                ];
            }
        }

        if (!empty($errors)) {
            return [
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $errors
            ];
        }

        // Start transaction to insert all
        DB::beginTransaction();
        try {
            foreach ($validatedData as $data) {
                // Generate a random password
                $password = Str::random(10);

                $student = Student::create([
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'phone' => $data['phone'],
                    'password' => Hash::make($password),
                    'institute_id' => $institute->id,
                    'batch_id' => null,
                    'standard' => $data['standard'],
                    'dob' => $data['dob'],
                    'guardian_name' => $data['guardian_name'],
                    'monthly_fee' => $data['monthly_fee'],
                    'status' => 1,
                    'id_hash' => Str::random(32),
                ]);

                // Send email
                try {
                    Mail::to($student->email)->send(new \App\Mail\StudentAddedMail(
                        $student->name,
                        $student->email,
                        $password,
                        $institute->institute_name,
                        $institute->logo
                    ));
                } catch (\Exception $e) {
                    \Log::error("Failed to send welcome email during bulk import for: " . $student->email . " - " . $e->getMessage());
                }

                // Notify batch assignment
                if (!empty($student->batch_id)) {
                    $this->notifyBatchChange($student->fresh(), null, $student->batch_id);
                }
            }

            DB::commit();

            return [
                'status' => 'success',
                'message' => count($validatedData) . ' students have been successfully imported!'
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error("Bulk student import failed: " . $e->getMessage());
            return [
                'status' => 'error',
                'message' => 'An error occurred during student creation.',
                'errors' => ['Internal database error during import: ' . $e->getMessage()]
            ];
        }
    }

    /**
     * Send batch notifications.
     */
    private function notifyBatchChange($student, $oldBatchId, $newBatchId): void
    {
        $fcm = app(\App\Services\FCMService::class);
        $student->loadMissing('parent');

        if (!empty($newBatchId)) {
            $batch = Batch::find($newBatchId);
            if (!$batch) {
                return;
            }

            $title = 'Batch Updated';
            $studentBody = "You have been assigned to the batch: {$batch->name}";
            $pushData = ['type' => 'batch_assignment', 'batch_id' => (string) $batch->id];

            \App\Models\Notification::create([
                'user_type'    => 'student',
                'user_id'      => $student->id,
                'title'        => $title,
                'message'      => $studentBody,
                'type'         => 'batch_assignment',
                'reference_id' => $batch->id,
                'is_read'      => false,
            ]);
            if (!empty($student->fcm_token)) {
                $fcm->send($student->fcm_token, $title, $studentBody, $pushData);
            }

            if ($student->parent) {
                $parentBody = "{$student->name} has been assigned to the batch: {$batch->name}";
                \App\Models\Notification::create([
                    'user_type'    => 'parent',
                    'user_id'      => $student->parent->id,
                    'title'        => "Batch Assigned: {$student->name}",
                    'message'      => $parentBody,
                    'type'         => 'batch_assignment',
                    'reference_id' => $batch->id,
                    'is_read'      => false,
                ]);
                if (!empty($student->parent->fcm_token)) {
                    $fcm->send($student->parent->fcm_token, "Batch Assigned: {$student->name}", $parentBody, $pushData);
                }
            }
        }
    }
}
