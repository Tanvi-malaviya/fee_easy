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
        $tomorrow = Carbon::tomorrow()->toDateString();

        // Find all homeworks due tomorrow (still active — tomorrow hasn't passed yet)
        $homeworks = Homework::whereDate('due_date', $tomorrow)
            ->with(['batch.students.parent'])
            ->get();

        if ($homeworks->isEmpty()) {
            $this->info('No homework reminders to send today.');
            return;
        }

        $fcm   = app(\App\Services\FCMService::class);
        $count = 0;

        foreach ($homeworks as $homework) {
            $students = $homework->batch->students ?? collect();

            $notifTitle = "Homework Due Tomorrow 📚";
            $notifBody  = "Reminder: \"{$homework->title}\" is due tomorrow. Submit on time! (If already submitted, please ignore.)";
            $notifData  = [
                'type'        => 'homework_reminder',
                'homework_id' => (string) $homework->id,
                'batch_id'    => (string) $homework->batch_id,
            ];

            foreach ($students as $student) {
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
                    $parentBody  = "{$student->name}'s homework \"{$homework->title}\" is due tomorrow! (If already submitted, please ignore.)";

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

            $this->info("Reminded {$students->count()} student(s) for homework: \"{$homework->title}\"");
        }

        $this->info("Total: {$count} notification(s) sent.");
    }
}
