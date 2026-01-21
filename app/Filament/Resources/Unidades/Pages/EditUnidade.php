<?php

namespace App\Filament\Resources\Unidades\Pages;

use App\Filament\Resources\Unidades\UnidadeResource;
use App\Models\Empresa;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Validation\ValidationException;

class EditUnidade extends EditRecord
{
    protected static string $resource = UnidadeResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
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

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
