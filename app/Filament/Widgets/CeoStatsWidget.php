<?php

namespace App\Filament\Widgets;

use App\Models\Empresa;
use App\Models\Grupo;
use App\Models\Unidade;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class CeoStatsWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        return [
            Stat::make('Total de Grupos', Grupo::count())
                ->description('Grupos cadastrados')
                ->descriptionIcon('heroicon-o-building-office-2')
                ->color('success'),

            Stat::make('Total de Empresas', Empresa::count())
                ->description('Empresas cadastradas')
                ->descriptionIcon('heroicon-o-building-office')
                ->color('info'),

            Stat::make('Total de Unidades', Unidade::count())
                ->description('Unidades cadastradas')
                ->descriptionIcon('heroicon-o-building-storefront')
                ->color('warning'),

            Stat::make('Grupos Ativos', Grupo::where('ativo', true)->count())
                ->description('Grupos em operação')
                ->descriptionIcon('heroicon-o-check-circle')
                ->color('success'),

            Stat::make('Empresas Ativas', Empresa::where('ativo', true)->count())
                ->description('Empresas em operação')
                ->descriptionIcon('heroicon-o-check-circle')
                ->color('success'),

            Stat::make('Unidades Ativas', Unidade::where('ativo', true)->count())
                ->description('Unidades em operação')
                ->descriptionIcon('heroicon-o-check-circle')
                ->color('success'),
        ];
    }
}
