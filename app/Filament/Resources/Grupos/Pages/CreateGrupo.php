<?php

namespace App\Filament\Resources\Grupos\Pages;

use App\Filament\Resources\Grupos\GrupoResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateGrupo extends CreateRecord
{
    protected static string $resource = GrupoResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        return Model::unguarded(fn() => static::getModel()::create($data));
    }
}
