<?php

namespace App\Filament\Resources\Setors\Pages;

use App\Filament\Resources\Setors\SetorResource;
use App\Models\Unidade;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;

class EditSetor extends EditRecord
{
    protected static string $resource = SetorResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $unidadeId = $data['unidade_id'] ?? null;

        if (! $unidadeId) {
            throw ValidationException::withMessages([
                'unidade_id' => 'Selecione uma Unidade válida.',
            ]);
        }

        $unidade = Unidade::query()->find($unidadeId);

        if (! $unidade) {
            throw ValidationException::withMessages([
                'unidade_id' => 'A Unidade selecionada é inválida.',
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
