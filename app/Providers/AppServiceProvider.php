<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use App\Helpers\TimeHelper;
use App\Models\CauHinh;
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
        
        // Share CauHinh data with all views
        try {
            $cauHinh = CauHinh::find(1);
            if ($cauHinh) {
                // Share the entire CauHinh object
                View::share('cauHinh', $cauHinh);
                // Also keep the livechat_license for backward compatibility
                View::share('livechat_license', $cauHinh->id_live_chat);
            } else {
                // Fallback to null if no configuration found
                View::share('cauHinh', null);
                View::share('livechat_license', '');
            }
        } catch (\Exception $e) {
            // Fallback to null if database error
            View::share('cauHinh', null);
            View::share('livechat_license', '');
        }
    }
}
