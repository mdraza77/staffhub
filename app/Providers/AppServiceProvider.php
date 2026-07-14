<?php

namespace App\Providers;

use App\Models\CompanySetting;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use \Illuminate\Support\Facades\View;

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
        try {
            if (Schema::hasTable('company_settings')) {
                $globalSetting = CompanySetting::first();
                View::share('globalSetting', $globalSetting);
            }
        } catch (\Exception $e) {
            // Ignore during build/deployment
        }
    }
}
