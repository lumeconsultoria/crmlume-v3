<?php

namespace App\Filament\Resources\Colaboradors\Pages;

use App\Filament\Resources\Colaboradors\ColaboradorResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListColaboradors extends ListRecords
{
    protected static string $resource = ColaboradorResource::class;

    protected static ?string $breadcrumb = 'Cadastro de Colaborador';

    public function getHeading(): string
    {
        return 'Funcionários';
    }

    public function getSubheading(): ?string
    {
        return 'Gerencie a sua base de colaboradores para as ações do aplicativo';
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
