<?php

declare(strict_types=1);

namespace App\Filament\Resources\EmailPendencias\Pages;

use App\Filament\Resources\EmailPendencias\EmailPendenciaResource;
use Filament\Resources\Pages\ListRecords;

class ListEmailPendencias extends ListRecords
{
    protected static string $resource = EmailPendenciaResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
