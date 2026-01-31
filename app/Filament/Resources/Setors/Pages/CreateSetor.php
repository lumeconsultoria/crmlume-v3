<?php

namespace App\Filament\Resources\Setors\Pages;

use App\Filament\Resources\Setors\SetorResource;
use App\Models\Unidade;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;

class CreateSetor extends CreateRecord
{
    protected static string $resource = SetorResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $unidadeId = $data['unidade_id'] ?? null;

        if (! $unidadeId) {
            throw ValidationException::withMessages([
                'unidade_id' => 'Selecione uma Unidade válida.',
            ]);
        }

        if (! Unidade::query()->find($unidadeId)) {
            throw ValidationException::withMessages([
                'unidade_id' => 'A Unidade selecionada é inválida.',
            ]);
        }

        return $data;
    }

    protected function handleRecordCreation(array $data): Model
    {
        return Model::unguarded(fn() => static::getModel()::create($data));
    }
}
