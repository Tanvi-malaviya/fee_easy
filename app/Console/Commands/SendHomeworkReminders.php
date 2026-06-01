<?php

namespace App\Console\Commands;

use App\Models\Homework;
use App\Models\Notification;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendHomeworkReminders extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'homework:send-reminders';

    /**
     * The console command description.
     */
    protected $description = 'Send push notification reminders to students/parents one day before homework due date.';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $threeDaysFromNow = Carbon::now()->addDays(3)->toDateString();
        $twoDaysFromNow = Carbon::now()->addDays(2)->toDateString();
        $tomorrow = Carbon::now()->addDays(1)->toDateString();

        // Find all homeworks due in 3 days, 2 days, or tomorrow
        $homeworks = Homework::where(function($query) use ($threeDaysFromNow, $twoDaysFromNow, $tomorrow) {
                $query->whereDate('due_date', $threeDaysFromNow)
                      ->orWhereDate('due_date', $twoDaysFromNow)
                      ->orWhereDate('due_date', $tomorrow);
            })
            ->with(['batch.students.parent', 'submissions'])
            ->get();

        if ($homeworks->isEmpty()) {
            $this->info('No homework reminders to send today.');
            return;
        }

        $fcm   = app(\App\Services\FCMService::class);
        $count = 0;

        foreach ($homeworks as $homework) {
            $dueDate = Carbon::parse($homework->due_date)->startOfDay();
            $today = Carbon::now()->startOfDay();
            $daysLeft = $today->diffInDays($dueDate);

            if ($daysLeft <= 0 || $daysLeft > 3) {
                continue;
            }

            // Exclude students who already submitted
            $submittedStudentIds = $homework->submissions->pluck('student_id')->toArray();
            $students = $homework->batch->students ?? collect();

            if ($daysLeft == 1) {
                $notifTitle    = "Homework Due Tomorrow";
                $notifBody     = "\"" . $homework->title . "\" is due tomorrow. Don't forget to submit.";
                $parentDayText = "tomorrow";
            } else {
                $notifTitle    = "Homework Due in {$daysLeft} Days";
                $notifBody     = "\"" . $homework->title . "\" is due in {$daysLeft} days. Plan ahead!";
                $parentDayText = "in {$daysLeft} days";
            }

            $notifData  = [
                'type'        => 'homework_reminder',
                'homework_id' => (string) $homework->id,
                'batch_id'    => (string) $homework->batch_id,
            ];

            foreach ($students as $student) {
                // If student already submitted, skip reminder
                if (in_array($student->id, $submittedStudentIds)) {
                    continue;
                }

                // DB notification → student
                Notification::create([
                    'user_type'    => 'student',
                    'user_id'      => $student->id,
                    'title'        => $notifTitle,
                    'message'      => $notifBody,
                    'type'         => 'homework_reminder',
                    'reference_id' => $homework->id,
                    'is_read'      => false,
                ]);

                // FCM push → student
                if (!empty($student->fcm_token)) {
                    $fcm->send($student->fcm_token, $notifTitle, $notifBody, $notifData);
                }

                // DB notification → parent
                if ($student->parent) {
                    $parentTitle = "Homework Reminder: {$student->name}";
                    $parentBody  = "{$student->name}'s homework \"" . $homework->title . "\" is due {$parentDayText}.";

                    Notification::create([
                        'user_type'    => 'parent',
                        'user_id'      => $student->parent->id,
                        'title'        => $parentTitle,
                        'message'      => $parentBody,
                        'type'         => 'homework_reminder',
                        'reference_id' => $homework->id,
                        'is_read'      => false,
                    ]);

                    // FCM push → parent
                    if (!empty($student->parent->fcm_token)) {
                        $fcm->send($student->parent->fcm_token, $parentTitle, $parentBody, $notifData);
                    }
                }

                $count++;
            }

            $this->info("Reminded student(s) for homework: \"{$homework->title}\" ({$daysLeft} day(s) left)");
        }

        $this->info("Total: {$count} notification(s) sent.");
    }
}
