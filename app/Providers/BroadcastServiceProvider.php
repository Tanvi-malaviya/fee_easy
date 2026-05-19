<?php

namespace App\Providers;

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\ServiceProvider;

class BroadcastServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Broadcast::routes(['middleware' => ['web', 'auth:institute,sanctum']]);
        Broadcast::routes(['prefix' => 'api', 'middleware' => ['api', 'auth:sanctum']]);

        require base_path('routes/channels.php');
    }
}
