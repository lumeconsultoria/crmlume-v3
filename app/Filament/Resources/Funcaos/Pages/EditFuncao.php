<?php

namespace App\Filament\Resources\Funcaos\Pages;

use App\Filament\Resources\Funcaos\FuncaoResource;
use App\Models\Setor;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Validation\ValidationException;

class EditFuncao extends EditRecord
{
    protected static string $resource = FuncaoResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $state = $this->form->getState();
        $grupoId = $state['grupo_id'] ?? null;
        $empresaId = $state['empresa_id'] ?? null;
        $unidadeId = $state['unidade_id'] ?? null;
        $setorId = $data['setor_id'] ?? null;

        if (! $grupoId || ! $empresaId || ! $unidadeId || ! $setorId) {
            throw ValidationException::withMessages([
                'setor_id' => 'Selecione Grupo, Empresa, Unidade e Setor válidos.',
            ]);
        }

        $setor = Setor::query()->with('unidade.empresa.grupo')->find($setorId);

        if (! $setor
            || $setor->unidade_id !== (int) $unidadeId
            || $setor->unidade?->empresa_id !== (int) $empresaId
            || $setor->unidade?->empresa?->grupo_id !== (int) $grupoId
        ) {
            throw ValidationException::withMessages([
                'setor_id' => 'O Setor selecionado não pertence à Unidade/Empresa/Grupo informados.',
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
