<?php

namespace App\Filament\Resources\Colaboradors\Pages;

use App\Filament\Resources\Colaboradors\ColaboradorResource;
use App\Models\Funcao;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Validation\ValidationException;

class EditColaborador extends EditRecord
{
    protected static string $resource = ColaboradorResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $state = $this->form->getState();
        $grupoId = $state['grupo_id'] ?? null;
        $empresaId = $state['empresa_id'] ?? null;
        $unidadeId = $state['unidade_id'] ?? null;
        $setorId = $state['setor_id'] ?? null;
        $funcaoId = $data['funcao_id'] ?? null;

        if (! $grupoId || ! $empresaId || ! $unidadeId || ! $setorId || ! $funcaoId) {
            throw ValidationException::withMessages([
                'funcao_id' => 'Selecione Grupo, Empresa, Unidade, Setor e Função válidos.',
            ]);
        }

        $funcao = Funcao::query()->with('setor.unidade.empresa.grupo')->find($funcaoId);

        if (! $funcao) {
            throw ValidationException::withMessages([
                'funcao_id' => 'A Função selecionada é inválida.',
            ]);
        }

        $expectedSetorId = $funcao->setor_id;
        $expectedUnidadeId = $funcao->setor?->unidade_id;
        $expectedEmpresaId = $funcao->setor?->unidade?->empresa_id;
        $expectedGrupoId = $funcao->setor?->unidade?->empresa?->grupo_id;

        if (
            $expectedSetorId !== (int) $setorId
            || $expectedUnidadeId !== (int) $unidadeId
            || $expectedEmpresaId !== (int) $empresaId
            || $expectedGrupoId !== (int) $grupoId
        ) {
            throw ValidationException::withMessages([
                'funcao_id' => 'A Função selecionada não pertence à hierarquia informada.',
            ]);
        }

        $data['unidade_id'] = $expectedUnidadeId;
        $data['empresa_id'] = $expectedEmpresaId;

        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
