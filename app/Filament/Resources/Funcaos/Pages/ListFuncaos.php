<?php

namespace App\Filament\Resources\Funcaos\Pages;

use App\Filament\Resources\Funcaos\FuncaoResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListFuncaos extends ListRecords
{
    protected static string $resource = FuncaoResource::class;

    protected static ?string $breadcrumb = 'Cadastro de Função';

    public function getHeading(): string
    {
        return 'Cargos/Funções';
    }

    public function getSubheading(): ?string
    {
        return 'Gerencie as funções cadastradas em sua(s) empresa(s)';
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Cadastrar')
                ->color('success')
                ->icon('heroicon-o-plus'),
        ];
    }
}
