<?php

namespace App\Filament\Resources\Funcaos\Pages;

use App\Filament\Resources\Funcaos\FuncaoResource;
use App\Models\Setor;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;

class CreateFuncao extends CreateRecord
{
    protected static string $resource = FuncaoResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $setorId = $data['setor_id'] ?? null;

        if (! $setorId) {
            throw ValidationException::withMessages([
                'setor_id' => 'Selecione um Setor válido.',
            ]);
        }

        if (! Setor::query()->find($setorId)) {
            throw ValidationException::withMessages([
                'setor_id' => 'O Setor selecionado é inválido.',
            ]);
        }

        return $data;
    }

    protected function handleRecordCreation(array $data): Model
    {
        return Model::unguarded(fn() => static::getModel()::create($data));
    }
}
