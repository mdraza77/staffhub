<?php

namespace App\Providers;

use App\Models\CompanySetting;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        if (app()->environment('production')) {
            URL::forceScheme('https');
        }

        try {
            if (Schema::hasTable('company_settings')) {
                $globalSetting = CompanySetting::first();
                View::share('globalSetting', $globalSetting);
            }
        } catch (\Exception $e) {
            //
        }
    }
}
