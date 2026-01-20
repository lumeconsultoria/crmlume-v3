<?php

namespace App\Providers;

use App\Support\Modules\ModuleManager;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(ModuleManager::class, fn() => new ModuleManager());
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $helpers = app_path('Support/Modules/helpers.php');
        if (file_exists($helpers)) {
            require_once $helpers;
        }
    }
}
