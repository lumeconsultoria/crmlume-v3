<?php

namespace App\Filament\Resources\Unidades\Pages;

use App\Filament\Resources\Concerns\PrefillsEstrutura;
use App\Filament\Resources\Unidades\UnidadeResource;
use App\Models\Empresa;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\Model;

class CreateUnidade extends CreateRecord
{
    protected static string $resource = UnidadeResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $empresaId = $data['empresa_id'] ?? null;

        if (! $empresaId) {
            throw ValidationException::withMessages([
                'empresa_id' => 'Selecione uma Empresa válida.',
            ]);
        }

        $empresa = Empresa::query()->find($empresaId);

        if (! $empresa) {
            throw ValidationException::withMessages([
                'empresa_id' => 'A Empresa selecionada é inválida.',
            ]);
        }

        return $data;
    }

    protected function handleRecordCreation(array $data): Model
    {
        return Model::unguarded(fn() => static::getModel()::create($data));
    }
}
