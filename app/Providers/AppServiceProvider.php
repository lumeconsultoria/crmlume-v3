<?php

namespace App\Providers;

use App\Models\User;
use App\Models\Colaborador;
use App\Models\Funcao;
use App\Observers\ColaboradorObserver;
use App\Observers\FuncaoObserver;
use App\Observers\UserObserver;
use App\Support\Modules\ModuleManager;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Event;
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

        $authHelpers = app_path('Support/Auth/helpers.php');
        if (file_exists($authHelpers)) {
            require_once $authHelpers;
        }

        Event::listen(PasswordReset::class, function (PasswordReset $event): void {
            /** @var User $user */
            $user = $event->user;

            if ($user->email_verified_at === null) {
                $user->forceFill([
                    'email_verified_at' => now(),
                ])->save();
            }

            if ($user->colaborador_id) {
                $user->colaboradores()->syncWithoutDetaching([$user->colaborador_id]);
            }
        });

        Colaborador::observe(ColaboradorObserver::class);
        User::observe(UserObserver::class);
        Funcao::observe(FuncaoObserver::class);
    }
}
