<?php

namespace App\Filament\Resources\Empresas\Pages;

use App\Filament\Resources\Empresas\EmpresaResource;
use App\Filament\Resources\Concerns\PrefillsEstrutura;
use Filament\Resources\Pages\CreateRecord;

class CreateEmpresa extends CreateRecord
{
    use PrefillsEstrutura;

    protected static string $resource = EmpresaResource::class;
}
