<?php

namespace App\Filament\Resources\Unidades\Pages;

use App\Filament\Resources\Concerns\PrefillsEstrutura;
use App\Filament\Resources\Unidades\UnidadeResource;
use App\Models\Empresa;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Validation\ValidationException;

class CreateUnidade extends CreateRecord
{
    use PrefillsEstrutura;

    protected static string $resource = UnidadeResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $state = $this->form->getState();
        $grupoId = $state['grupo_id'] ?? null;
        $empresaId = $data['empresa_id'] ?? null;

        if (! $grupoId || ! $empresaId) {
            throw ValidationException::withMessages([
                'empresa_id' => 'Selecione um Grupo e uma Empresa válidos.',
            ]);
        }

        $empresa = Empresa::query()->find($empresaId);

        if (! $empresa || $empresa->grupo_id !== (int) $grupoId) {
            throw ValidationException::withMessages([
                'empresa_id' => 'A Empresa selecionada não pertence ao Grupo informado.',
            ]);
        }

        return $data;
    }
}
