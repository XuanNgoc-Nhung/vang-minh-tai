<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use App\Helpers\TimeHelper;
use Carbon\Carbon;

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
        // Use Bootstrap 5 pagination views across the app
        Paginator::useBootstrapFive();
        
        // Create global alias for TimeHelper
        if (!class_exists('TimeHelper')) {
            class_alias(TimeHelper::class, 'TimeHelper');
        }
        
        // Create global alias for Carbon
        if (!class_exists('Carbon')) {
            class_alias(Carbon::class, 'Carbon');
        }
    }
}
