<?php

namespace App\Filament\Resources\Setors\Pages;

use App\Filament\Resources\Setors\SetorResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSetors extends ListRecords
{
    protected static string $resource = SetorResource::class;

    protected static ?string $breadcrumb = 'Cadastro de Setor';

    public function getHeading(): string
    {
        return 'Setores/Departamentos';
    }

    public function getSubheading(): ?string
    {
        return 'Gerencie os setores cadastrados em sua(s) empresa(s)';
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
