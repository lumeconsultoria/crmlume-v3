<?php

namespace App\Filament\Resources\Setors\Pages;

use App\Filament\Resources\Setors\SetorResource;
use App\Models\Unidade;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Validation\ValidationException;

class EditSetor extends EditRecord
{
    protected static string $resource = SetorResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
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

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
