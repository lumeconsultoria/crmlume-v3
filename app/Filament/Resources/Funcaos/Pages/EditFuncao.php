<?php

namespace App\Filament\Resources\Funcaos\Pages;

use App\Filament\Resources\Funcaos\FuncaoResource;
use App\Models\Setor;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;

class EditFuncao extends EditRecord
{
    protected static string $resource = FuncaoResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $setorId = $data['setor_id'] ?? null;

        if (! $setorId) {
            throw ValidationException::withMessages([
                'setor_id' => 'Selecione um Setor válido.',
            ]);
        }

        $setor = Setor::query()->find($setorId);

        if (! $setor) {
            throw ValidationException::withMessages([
                'setor_id' => 'O Setor selecionado é inválido.',
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
