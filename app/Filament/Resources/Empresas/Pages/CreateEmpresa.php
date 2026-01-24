<?php

namespace App\Filament\Resources\Empresas\Pages;

use App\Filament\Resources\Empresas\EmpresaResource;
use App\Filament\Resources\Concerns\PrefillsEstrutura;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateEmpresa extends CreateRecord
{
    use PrefillsEstrutura;

    protected static string $resource = EmpresaResource::class;

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
        // Valida e cria a empresa
        $record = $this->handleRecordCreation($this->form->getState());
        $this->record = $record;

        $this->notify('success', 'Empresa salva com sucesso. Agora cadastre a Unidade.');

        // Redireciona para criar unidade já vinculada
        $this->redirect(route('filament.admin.resources.unidades.create', [
            'empresa_id' => $record->getKey(),
        ]));
    }

    protected function handleRecordCreation(array $data): Model
    {
        return Model::unguarded(fn() => static::getModel()::create($data));
    }
}
