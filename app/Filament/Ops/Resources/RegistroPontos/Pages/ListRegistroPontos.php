<?php

declare(strict_types=1);

namespace App\Filament\Ops\Resources\RegistroPontos\Pages;

use App\Filament\Ops\Resources\RegistroPontos\RegistroPontoResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListRegistroPontos extends ListRecords
{
    protected static string $resource = RegistroPontoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Registrar Agora'),
        ];
    }
}