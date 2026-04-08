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

        // Find all active or trial subscriptions that should have expired by now
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

        $this->info(count($expiredSubscriptions) . ' subscriptions processed and expired.');
    }
}
