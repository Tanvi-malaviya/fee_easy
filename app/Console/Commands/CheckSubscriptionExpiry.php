<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Subscription;
use App\Models\Institute;
use Carbon\Carbon;

class CheckSubscriptionExpiry extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscription:check-expiry';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automated check to expire trials and paid plans that have reached their end date.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = Carbon::today();

        // 1. Send Expiry Warning Notifications (expiring in 7 days down to 0 days)
        $warningSubscriptions = Subscription::whereBetween('end_date', [$today, $today->copy()->addDays(7)])
            ->whereIn('status', ['active', 'trial'])
            ->get();

        $fcmService = app(\App\Services\FCMService::class);

        foreach ($warningSubscriptions as $subscription) {
            $institute = $subscription->institute;
            if (!$institute) {
                continue;
            }

            $days = $today->diffInDays($subscription->end_date);
            $planName = $subscription->plan_name;
            $formattedDate = $subscription->end_date->format('d-m-Y');

            if ($days === 0) {
                $title = 'Plan Expiring Today';
                $message = "Your {$planName} plan expires today. Renew now to keep your services active.";
            } else {
                $title = 'Plan Expiring Soon';
                $message = "Your {$planName} plan expires in {$days} days on {$formattedDate}. Tap to renew.";
            }

            // Save to Notification database for Institute
            \App\Models\Notification::create([
                'user_type' => 'institute',
                'user_id' => $institute->id,
                'title' => $title,
                'message' => $message,
                'type' => 'subscription_alert',
                'is_read' => false,
            ]);

            // Send Firebase push notification if token exists
            if (!empty($institute->fcm_token)) {
                $fcmService->send($institute->fcm_token, $title, $message, [
                    'type' => 'subscription_alert',
                    'plan_name' => $planName,
                    'days_remaining' => (string) $days,
                ]);
            }
        }

        // 2. Find all active or trial subscriptions that should have expired by now
        $expiredSubscriptions = Subscription::where('end_date', '<', $today)
            ->whereIn('status', ['active', 'trial'])
            ->get();

        foreach ($expiredSubscriptions as $subscription) {
            // Update the subscription itself
            $subscription->update(['status' => 'expired']);

            // Deactivate the institute associated with this subscription
            $institute = $subscription->institute;
            if ($institute && $institute->status === 'active') {
                $institute->update(['status' => 'inactive']);
                $this->warn("Institute '{$institute->institute_name}' deactivated due to plan expiry.");
            }
        }

        // 3. Find any subscriptions that are currently marked 'expired' but their end_date is in the future
        $reactivatedSubscriptions = Subscription::where('end_date', '>=', $today)
            ->where('status', 'expired')
            ->get();

        foreach ($reactivatedSubscriptions as $subscription) {
            $subscription->update(['status' => 'active']);
            $institute = $subscription->institute;
            if ($institute && $institute->status === 'inactive') {
                $institute->update(['status' => 'active']);
                $this->info("Institute '{$institute->institute_name}' reactivated as subscription is now active.");
            }
        }

        $this->info(count($warningSubscriptions) . ' subscription warning notifications processed.');
        $this->info(count($expiredSubscriptions) . ' subscriptions processed and expired.');
        $this->info(count($reactivatedSubscriptions) . ' subscriptions reactivated.');
    }
}
