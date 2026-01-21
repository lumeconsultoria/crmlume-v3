<?php

declare(strict_types=1);

namespace App\Providers\Filament;

use App\Http\Middleware\EnsurePrimeiroAcessoFilamentEnabled;
use App\Filament\PrimeiroAcesso\Pages\PrimeiroAcessoPublico;
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

class PrimeiroAcessoPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('primeiro-acesso')
            ->path('primeiro-acesso')
            ->colors([
                'primary' => Color::Amber,
            ])
            ->pages([
                PrimeiroAcessoPublico::class,
            ])
            ->middleware([
                EnsurePrimeiroAcessoFilamentEnabled::class,
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
            ->authMiddleware([]);
    }
}
