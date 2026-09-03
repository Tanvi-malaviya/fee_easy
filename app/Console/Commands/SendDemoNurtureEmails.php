<?php

namespace App\Console\Commands;

use App\Mail\DemoNurtureMail;
use App\Models\DemoRequest;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendDemoNurtureEmails extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'leads:send-demo-nurture';

    /**
     * The console command description.
     */
    protected $description = 'Send a 3-step nurture sequence (day 1, day 3, day 7) to institutes that booked a demo but have not yet been converted.';

    /**
     * Stage number => days since the demo request was created.
     */
    protected const STAGES = [1 => 1, 2 => 3, 3 => 7];

    public function handle(): void
    {
        $count = 0;

        foreach (self::STAGES as $stageNumber => $daysSince) {
            $targetDate = Carbon::today()->subDays($daysSince);

            $requests = DemoRequest::whereDate('created_at', $targetDate)
                ->where('nurture_stage', $stageNumber - 1)
                ->whereNotIn('status', ['Converted', 'converted'])
                ->get();

            foreach ($requests as $demoRequest) {
                if (empty($demoRequest->email)) {
                    continue;
                }

                try {
                    Mail::to($demoRequest->email)->send(new DemoNurtureMail(
                        $demoRequest->full_name,
                        $demoRequest->institute_name,
                        $daysSince
                    ));

                    $demoRequest->update([
                        'nurture_stage' => $stageNumber,
                        'nurture_last_sent_at' => now(),
                    ]);
                    $count++;
                } catch (\Throwable $e) {
                    Log::error("Failed to send demo nurture email to {$demoRequest->email}: " . $e->getMessage());
                }
            }
        }

        $this->info("Sent {$count} demo nurture email(s).");
    }
}
