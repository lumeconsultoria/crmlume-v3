<?php

namespace App\Filament\Resources\Empresas\Pages;

use App\Filament\Resources\Empresas\EmpresaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListEmpresas extends ListRecords
{
    protected static string $resource = EmpresaResource::class;

    protected static ?string $breadcrumb = 'Cadastro de Empresa';

    public function getHeading(): string
    {
        return 'Empresa / Empregador';
    }

    public function getSubheading(): ?string
    {
        return 'Gerencie as empresas e/ou empregadores cadastrados';
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
