<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Helpers\LanguageHelper;

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
        // Set the application locale based on session
        app()->setLocale(LanguageHelper::getLocale());
    }
}
