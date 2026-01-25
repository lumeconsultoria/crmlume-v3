<?php

namespace App\Filament\Resources\Empresas\Pages;

use App\Filament\Resources\Empresas\EmpresaResource;
use App\Models\Grupo;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;

class EditEmpresa extends EditRecord
{
    protected static string $resource = EmpresaResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $grupoId = $data['grupo_id'] ?? null;

        if (! $grupoId) {
            throw ValidationException::withMessages([
                'grupo_id' => 'Selecione um Grupo valido.',
            ]);
        }

        if (! Grupo::query()->whereKey($grupoId)->exists()) {
            throw ValidationException::withMessages([
                'grupo_id' => 'Grupo invalido.',
            ]);
        }

        // Mantem coluna obrigatoria 'nome' coerente com o formulario.
        $data['nome'] = $data['nm_razao_social'] ?? $data['nm_fantasia'] ?? $data['nr_cnpj'] ?? $this->record->nome;

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
