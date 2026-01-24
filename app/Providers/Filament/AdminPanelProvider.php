<?php

namespace App\Providers\Filament;

use App\Filament\Pages\DryRunImportacao;
use App\Filament\Pages\EstruturaOrganizacional;
use App\Filament\Pages\GovernancaAcesso;
use App\Filament\Resources\Colaboradors\ColaboradorResource;
use App\Filament\Resources\EmailPendencias\EmailPendenciaResource;
use App\Filament\Resources\Empresas\EmpresaResource;
use App\Filament\Resources\Funcaos\FuncaoResource;
use App\Filament\Resources\Grupos\GrupoResource;
use App\Filament\Resources\PrimeiroAcessos\PrimeiroAcessoResource;
use App\Filament\Resources\Setors\SetorResource;
use App\Filament\Resources\Unidades\UnidadeResource;
use App\Http\Middleware\EnsureAdminPanelAccess;
use App\Http\Middleware\EnsureColaboradorAtivo;
use Filament\Http\Middleware\Authenticate;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationGroup;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->authGuard('web')
            ->login()
            ->colors([
                'primary' => Color::Amber,
            ])
            ->resources([
                GrupoResource::class,
                EmpresaResource::class,
                UnidadeResource::class,
                SetorResource::class,
                FuncaoResource::class,
                ColaboradorResource::class,
                EmailPendenciaResource::class,
                PrimeiroAcessoResource::class,
            ])
            ->pages([
                EstruturaOrganizacional::class,
                DryRunImportacao::class,
                GovernancaAcesso::class,
            ])
            ->navigationGroups([
                NavigationGroup::make('Core do Sistema'),
                NavigationGroup::make('Segurança & Governança'),
                NavigationGroup::make('Operacional (Admin)'),
            ])
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
            ->plugins([
                FilamentShieldPlugin::make(),
            ])
            ->authMiddleware([
                Authenticate::class,
                EnsureColaboradorAtivo::class,
                EnsureAdminPanelAccess::class,
            ]);
    }
}
