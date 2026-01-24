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
