<?php

declare(strict_types=1);

namespace App\Filament\Resources\PrimeiroAcessos\Pages;

use App\Filament\Resources\PrimeiroAcessos\PrimeiroAcessoResource;
use Filament\Resources\Pages\ListRecords;

class ListPrimeiroAcessos extends ListRecords
{
    protected static string $resource = PrimeiroAcessoResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
