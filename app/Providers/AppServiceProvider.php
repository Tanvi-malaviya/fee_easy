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
        // Share currency and trial settings globally with all views
        if (!app()->runningInConsole()) {
            \Illuminate\Support\Facades\View::share('currency', \App\Models\SystemSetting::get('currency_symbol', '₹'));
            \Illuminate\Support\Facades\View::share('default_trial', \App\Models\SystemSetting::get('default_trial_days', 14));
        }
    }
}
