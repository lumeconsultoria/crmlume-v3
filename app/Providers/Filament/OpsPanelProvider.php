<?php

namespace App\Providers\Filament;

use App\Http\Middleware\EnsureColaboradorAtivo;
use App\Http\Middleware\EnsureOpsPanelAccess;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Illuminate\Support\Facades\Auth;

class OpsPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('ops')
            ->path('ops')
            ->homeUrl(function (): string {
                $user = Auth::user();

                if ($user && (userIsColaborador($user) || userIsAdminLume($user) || userIsVendedorLume($user))) {
                    return '/ops/meu-ponto';
                }

                return '/ops/cartao-ponto';
            })
            ->authGuard('web')
            ->login()
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Ops/Resources'), for: 'App\Filament\Ops\Resources')
            ->discoverPages(in: app_path('Filament/Ops/Pages'), for: 'App\Filament\Ops\Pages')
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
                EnsureColaboradorAtivo::class,
                EnsureOpsPanelAccess::class,
            ]);
    }
}
