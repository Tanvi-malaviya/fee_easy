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
    }
}
