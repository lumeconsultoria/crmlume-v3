<?php

namespace App\Filament\Resources\Unidades\Pages;

use App\Filament\Resources\Concerns\PrefillsEstrutura;
use App\Filament\Resources\Unidades\UnidadeResource;
use App\Models\Empresa;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;

class CreateUnidade extends CreateRecord
{
    use PrefillsEstrutura;

    protected static string $resource = UnidadeResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // grupo_id nÃ£o Ã© desidratado no form; precisamos ler do estado bruto.
        $rawState = $this->form->getRawState();
        $grupoId = $rawState['grupo_id'] ?? null;
        $empresaId = $data['empresa_id'] ?? null;

        if (! $grupoId) {
            throw ValidationException::withMessages([
                'grupo_id' => 'Selecione um Grupo valido.',
            ]);
        }

        if (! $empresaId) {
            throw ValidationException::withMessages([
                'empresa_id' => 'Selecione uma Empresa valida.',
            ]);
        }

        $empresa = Empresa::query()->find($empresaId);

        if (! $empresa) {
            throw ValidationException::withMessages([
                'empresa_id' => 'A Empresa selecionada e invalida.',
            ]);
        }

        if ($empresa->grupo_id !== $grupoId) {
            throw ValidationException::withMessages([
                'empresa_id' => 'A Empresa precisa pertencer ao mesmo Grupo selecionado.',
            ]);
        }

        unset($data['grupo_id']);

        return $data;
    }

    protected function handleRecordCreation(array $data): Model
    {
        return Model::unguarded(fn () => static::getModel()::create($data));
    }
}
