<?php

namespace App\Filament\Widgets;

use App\Models\Colaborador;
use App\Models\Funcao;
use App\Models\Setor;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class RhStatsWidget extends BaseWidget
{
    protected static ?int $sort = 2;

    protected function getStats(): array
    {
        return [
            Stat::make('Total de Colaboradores', Colaborador::count())
                ->description('Colaboradores cadastrados')
                ->descriptionIcon('heroicon-o-user-group')
                ->color('info'),

            Stat::make('Colaboradores Ativos', Colaborador::where('ativo', true)->count())
                ->description('Em atividade')
                ->descriptionIcon('heroicon-o-check-badge')
                ->color('success'),

            Stat::make('Colaboradores Inativos', Colaborador::where('ativo', false)->count())
                ->description('Desligados ou afastados')
                ->descriptionIcon('heroicon-o-x-circle')
                ->color('danger'),

            Stat::make('Total de Funções', Funcao::count())
                ->description('Funções cadastradas')
                ->descriptionIcon('heroicon-o-briefcase')
                ->color('warning'),

            Stat::make('Total de Setores', Setor::count())
                ->description('Setores cadastrados')
                ->descriptionIcon('heroicon-o-building-office')
                ->color('info'),

            Stat::make('Usuários com Acesso', User::where('ativo', true)->count())
                ->description('Usuários ativos no sistema')
                ->descriptionIcon('heroicon-o-user-circle')
                ->color('success'),
        ];
    }
}
