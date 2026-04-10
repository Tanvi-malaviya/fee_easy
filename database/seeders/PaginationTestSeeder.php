<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Institute;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\SubscriptionPayment;
use Illuminate\Support\Str;

class PaginationTestSeeder extends Seeder
{
    public function run()
    {
        echo "Seeding Institutes...\n";
        for ($i = 1; $i <= 15; $i++) {
            $inst = Institute::create([
                'name' => "Owner Name $i",
                'institute_name' => "Dummy Institute $i",
                'email' => "dummy$i@example.com",
                'phone' => "98765432" . str_pad($i, 2, '0', STR_PAD_LEFT),
                'address' => "$i Dummy Street, Knowledge Park",
                'city' => "City $i",
                'state' => "State $i",
                'pincode' => "4000" . str_pad($i, 2, '0', STR_PAD_LEFT),
                'status' => 'active',
            ]);

            // Create a trial subscription for each to allow revenue records
            Subscription::create([
                'institute_id' => $inst->id,
                'plan_name' => 'Free Trial',
                'amount' => 0,
                'start_date' => now(),
                'end_date' => now()->addDays(14),
                'status' => 'trial',
            ]);
        }

        echo "Seeding Plans...\n";
        $plans = [
            ['name' => 'Basic Monthly', 'price' => 499, 'duration_days' => 30, 'trial_days' => 7, 'status' => true],
            ['name' => 'Standard Quarterly', 'price' => 1299, 'duration_days' => 90, 'trial_days' => 7, 'status' => true],
            ['name' => 'Premium Yearly', 'price' => 4500, 'duration_days' => 365, 'trial_days' => 14, 'status' => true],
            ['name' => 'Professional Plus', 'price' => 9999, 'duration_days' => 730, 'trial_days' => 30, 'status' => true],
            ['name' => 'Lifetime Growth', 'price' => 25000, 'duration_days' => 3650, 'trial_days' => 0, 'status' => true],
        ];

        foreach ($plans as $p) {
            Plan::create($p);
        }

        echo "Seeding Revenue Transactions...\n";
        $allSubs = Subscription::all();
        for ($i = 1; $i <= 20; $i++) {
            $sub = $allSubs->count() > 0 ? $allSubs->random() : null;
            if ($sub) {
                SubscriptionPayment::create([
                    'subscription_id' => $sub->id,
                    'amount' => rand(500, 5000),
                    'transaction_id' => 'TXN-' . strtoupper(Str::random(10)),
                    'payment_gateway' => 'manual',
                    'paid_at' => now()->subDays(rand(1, 30)),
                ]);
            }
        }

        echo "Done! Seeded 15 Institutes, 5 Plans, and 20 Transactions.\n";
    }
}
