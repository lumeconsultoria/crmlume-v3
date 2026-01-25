<?php

namespace App\Filament\Resources\Unidades\Pages;

use App\Filament\Resources\Unidades\UnidadeResource;
use App\Models\Empresa;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;

class EditUnidade extends EditRecord
{
    protected static string $resource = UnidadeResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // grupo_id permanece apenas no estado do formulÃ¡rio (nÃ£o desidratado)
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

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        return Model::unguarded(function () use ($record, $data) {
            $record->fill($data);
            $record->save();
            return $record;
        });
    }
}
