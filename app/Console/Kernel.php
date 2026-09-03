<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('subscription:check-expiry')->dailyAt('00:00');
        $schedule->command('demo:seed')->dailyAt('00:00');
        $schedule->command('homework:send-reminders')->dailyAt('08:00');
        $schedule->command('birthday:send-notifications')->dailyAt('08:00');

        // Fee reminders: 3 days before due, on due date, 3/7 days overdue.
        $schedule->command('fees:send-reminders')->dailyAt('09:00');

        // Draft payroll on the 1st of every month, awaiting institute review/confirmation.
        $schedule->command('payroll:generate')->monthlyOn(1, '01:00');

        // Low-attendance parent alerts (checked daily, re-alerts at most every 14 days per student).
        $schedule->command('attendance:low-alerts')->dailyAt('08:30');

        // Demo-to-paid nurture sequence (day 1 / day 3 / day 7 after booking).
        $schedule->command('leads:send-demo-nurture')->dailyAt('10:00');

        // Weekly digests, Monday mornings.
        $schedule->command('digest:parent-progress')->weeklyOn(1, '07:00');
        $schedule->command('digest:institute-summary')->weeklyOn(1, '07:30');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
