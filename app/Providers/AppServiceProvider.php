<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Share currency setting globally with all views
        if (!app()->runningInConsole()) {
            \Illuminate\Support\Facades\View::share('currency', \App\Models\SystemSetting::get('currency_symbol', '₹'));
        }

        // Restrict refresh tokens from accessing regular routes
        \Laravel\Sanctum\Sanctum::authenticateAccessTokensUsing(function ($accessToken, $isValid) {
            if (!$isValid) {
                return false;
            }
            return !in_array('refresh-token', $accessToken->abilities);
        });

        // Load dynamic Razorpay credentials from system settings if they exist
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('system_settings')) {
                $keyId = \App\Models\SystemSetting::get('razorpay_key_id');
                $keySecret = \App\Models\SystemSetting::get('razorpay_key_secret');
                $webhookSecret = \App\Models\SystemSetting::get('razorpay_webhook_secret');

                if (!empty($keyId)) {
                    config(['services.razorpay.key_id' => $keyId]);
                }
                if (!empty($keySecret)) {
                    config(['services.razorpay.key_secret' => $keySecret]);
                }
                if (!empty($webhookSecret)) {
                    config(['services.razorpay.webhook_secret' => $webhookSecret]);
                }
            }
        } catch (\Exception $e) {
            // Fail-safe for migration/console environments when database is not set up
        }
    }
}
