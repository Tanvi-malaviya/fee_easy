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

        // Load dynamic Razorpay credentials and Mail settings from system settings if they exist
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('system_settings')) {
                // Razorpay credentials
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

                // Mail credentials & configuration
                $mailMailer = \App\Models\SystemSetting::get('mail_mailer');
                $mailHost = \App\Models\SystemSetting::get('mail_host');
                $mailPort = \App\Models\SystemSetting::get('mail_port');
                $mailUsername = \App\Models\SystemSetting::get('mail_username');
                $mailPassword = \App\Models\SystemSetting::get('mail_password');
                $mailEncryption = \App\Models\SystemSetting::get('mail_encryption');
                $mailFromAddress = \App\Models\SystemSetting::get('mail_from_address');
                $mailFromName = \App\Models\SystemSetting::get('mail_from_name');

                if (!empty($mailMailer)) {
                    config(['mail.default' => $mailMailer]);
                }
                if (!empty($mailHost)) {
                    config(['mail.mailers.smtp.host' => $mailHost]);
                }
                if (!empty($mailPort)) {
                    config(['mail.mailers.smtp.port' => (int) $mailPort]);
                }
                if (!empty($mailUsername)) {
                    config(['mail.mailers.smtp.username' => $mailUsername]);
                }
                if (!empty($mailPassword)) {
                    config(['mail.mailers.smtp.password' => $mailPassword]);
                }
                if (!empty($mailEncryption)) {
                    config(['mail.mailers.smtp.encryption' => $mailEncryption === 'null' ? null : $mailEncryption]);
                }
                if (!empty($mailFromAddress)) {
                    config(['mail.from.address' => $mailFromAddress]);
                }
                if (!empty($mailFromName)) {
                    config(['mail.from.name' => $mailFromName]);
                }
            }
        } catch (\Exception $e) {
            // Fail-safe for migration/console environments when database is not set up
        }
    }
}
