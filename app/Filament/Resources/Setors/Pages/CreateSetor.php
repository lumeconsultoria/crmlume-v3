<?php

namespace App\Filament\Resources\Setors\Pages;

use App\Filament\Resources\Concerns\PrefillsEstrutura;
use App\Filament\Resources\Setors\SetorResource;
use App\Models\Unidade;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Validation\ValidationException;

class CreateSetor extends CreateRecord
{
    use PrefillsEstrutura;

    protected static string $resource = SetorResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $state = $this->form->getState();
        $grupoId = $state['grupo_id'] ?? null;
        $empresaId = $state['empresa_id'] ?? null;
        $unidadeId = $data['unidade_id'] ?? null;

        if (! $grupoId || ! $empresaId || ! $unidadeId) {
            throw ValidationException::withMessages([
                'unidade_id' => 'Selecione Grupo, Empresa e Unidade válidos.',
            ]);
        }

        $unidade = Unidade::query()->with('empresa.grupo')->find($unidadeId);

        if (! $unidade
            || $unidade->empresa_id !== (int) $empresaId
            || $unidade->empresa?->grupo_id !== (int) $grupoId
        ) {
            throw ValidationException::withMessages([
                'unidade_id' => 'A Unidade selecionada não pertence à Empresa/Grupo informados.',
            ]);
        }

        return $data;
    }
}
