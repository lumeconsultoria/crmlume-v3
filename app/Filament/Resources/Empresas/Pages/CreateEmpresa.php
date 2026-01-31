<?php

namespace App\Filament\Resources\Empresas\Pages;

use App\Filament\Resources\Concerns\PrefillsEstrutura;
use App\Filament\Resources\Empresas\EmpresaResource;
use App\Models\Grupo;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;

class CreateEmpresa extends CreateRecord
{
    use PrefillsEstrutura;

    protected static string $resource = EmpresaResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
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

        // Garante preenchimento da coluna obrigatoria no banco.
        $data['nome'] = $data['nm_razao_social'] ?? $data['nm_fantasia'] ?? $data['nr_cnpj'] ?? 'Empresa';

        return $data;
    }

    protected function getFormActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Salvar')
                ->color('primary'),
            Actions\Action::make('save_and_continue')
                ->label('Salvar Empresa e continuar')
                ->color('primary')
                ->requiresConfirmation(false)
                ->action('saveAndContinue'),
        ];
    }

    public function saveAndContinue(): void
    {
        $this->form->validate();
        $data = $this->mutateFormDataBeforeCreate($this->form->getState());
        $record = $this->handleRecordCreation($data);
        $this->record = $record;

        Notification::make()
            ->success()
            ->title('Empresa salva com sucesso. Agora cadastre a Unidade.')
            ->send();

        $this->redirect(route('filament.admin.resources.unidades.create', [
            'empresa_id' => $record->getKey(),
            'grupo_id' => $record->grupo_id,
        ]));
    }

    protected function handleRecordCreation(array $data): Model
    {
        return Model::unguarded(fn () => static::getModel()::create($data));
    }
}
