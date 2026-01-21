<?php

namespace App\Filament\Resources\Colaboradors\Schemas;

use App\Models\Empresa;
use App\Models\Funcao;
use App\Models\Grupo;
use App\Models\Setor;
use App\Models\Unidade;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ColaboradorForm
{
    public static function configure(Schema $schema): Schema
    {
        // Hierarquia obrigatória: Grupo → Empresa → Unidade → Setor → Função → Colaborador.
        return $schema
            ->components([
                Section::make('Estrutura Organizacional')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Select::make('grupo_id')
                                    ->label('Grupo Nome')
                                    ->options(fn() => Grupo::query()->orderBy('nome')->pluck('nome', 'id'))
                                    ->required()
                                    ->searchable()
                                    ->preload()
                                    ->live()
                                    ->dehydrated(false)
                                    ->afterStateHydrated(fn(Set $set, $state, $record) => $set('grupo_id', $record?->empresa?->grupo_id))
                                    ->afterStateUpdated(function (Set $set): void {
                                        $set('empresa_id', null);
                                        $set('unidade_id', null);
                                        $set('setor_id', null);
                                        $set('funcao_id', null);
                                    })
                                    ->helperText('Obrigatório. Selecione o Grupo do vínculo.'),
                                Select::make('empresa_id')
                                    ->label('Empresa Nome')
                                    ->required()
                                    ->searchable()
                                    ->preload()
                                    ->live()
                                    ->afterStateUpdated(function (Set $set): void {
                                        $set('unidade_id', null);
                                        $set('setor_id', null);
                                        $set('funcao_id', null);
                                    })
                                    ->disabled(fn(Get $get): bool => ! $get('grupo_id'))
                                    ->options(function (Get $get) {
                                        $grupoId = $get('grupo_id');

                                        if (! $grupoId) {
                                            return [];
                                        }

                                        return Empresa::query()
                                            ->where('grupo_id', $grupoId)
                                            ->orderBy('nome')
                                            ->pluck('nome', 'id');
                                    })
                                    ->helperText('Obrigatório. Selecione a Empresa do vínculo.'),
                                Select::make('unidade_id')
                                    ->label('Unidade Nome')
                                    ->options(function (Get $get) {
                                        $empresaId = $get('empresa_id');

                                        if (! $empresaId) {
                                            return [];
                                        }

                                        return Unidade::query()
                                            ->where('empresa_id', $empresaId)
                                            ->orderBy('nome')
                                            ->pluck('nome', 'id');
                                    })
                                    ->required()
                                    ->searchable()
                                    ->preload()
                                    ->live()
                                    ->afterStateUpdated(function (Set $set): void {
                                        $set('setor_id', null);
                                        $set('funcao_id', null);
                                    })
                                    ->disabled(fn(Get $get): bool => ! $get('empresa_id'))
                                    ->helperText('Obrigatório. Selecione a Unidade do vínculo.'),
                                Select::make('setor_id')
                                    ->label('Setor Nome')
                                    ->options(function (Get $get) {
                                        $unidadeId = $get('unidade_id');

                                        if (! $unidadeId) {
                                            return [];
                                        }

                                        return Setor::query()
                                            ->where('unidade_id', $unidadeId)
                                            ->orderBy('nome')
                                            ->pluck('nome', 'id');
                                    })
                                    ->required()
                                    ->searchable()
                                    ->preload()
                                    ->live()
                                    ->afterStateHydrated(fn(Set $set, $state, $record) => $set('setor_id', $record?->funcao?->setor_id))
                                    ->afterStateUpdated(fn(Set $set) => $set('funcao_id', null))
                                    ->dehydrated(false)
                                    ->disabled(fn(Get $get): bool => ! $get('unidade_id'))
                                    ->helperText('Obrigatório. Selecione o Setor do vínculo.'),
                                Select::make('funcao_id')
                                    ->label('Função Nome')
                                    ->options(function (Get $get) {
                                        $setorId = $get('setor_id');

                                        if (! $setorId) {
                                            return [];
                                        }

                                        return Funcao::query()
                                            ->where('setor_id', $setorId)
                                            ->orderBy('nome')
                                            ->pluck('nome', 'id');
                                    })
                                    ->required()
                                    ->searchable()
                                    ->preload()
                                    ->afterStateUpdated(function (Set $set, $state): void {
                                        if (! $state) {
                                            return;
                                        }

                                        $funcao = Funcao::query()
                                            ->with('setor.unidade.empresa')
                                            ->find($state);

                                        if (! $funcao) {
                                            return;
                                        }

                                        $set('setor_id', $funcao->setor_id);
                                        $set('unidade_id', $funcao->setor?->unidade_id);
                                        $set('empresa_id', $funcao->setor?->unidade?->empresa_id);
                                        $set('grupo_id', $funcao->setor?->unidade?->empresa?->grupo_id);
                                    })
                                    ->disabled(fn(Get $get): bool => ! $get('setor_id'))
                                    ->helperText('Obrigatório. Selecione a Função do vínculo.'),
                            ]),
                    ]),
                Section::make('Identificação')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('nome')
                                    ->label('Colaborador Nome')
                                    ->required()
                                    ->helperText('Obrigatório. Nome completo do colaborador.'),
                                Toggle::make('ativo')
                                    ->label('Colaborador Ativo')
                                    ->required()
                                    ->helperText('Obrigatório. Define se o colaborador está ativo.'),
                            ]),
                    ]),
                Section::make('Endereço / Dados Complementares')
                    ->collapsed()
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('user.email')
                                    ->label('Usuário Email')
                                    ->disabled()
                                    ->dehydrated(false)
                                    ->helperText('Informativo. E-mail do usuário vinculado.'),
                                Toggle::make('user.ativo')
                                    ->label('Usuário Ativo')
                                    ->disabled()
                                    ->dehydrated(false)
                                    ->helperText('Informativo. Status do usuário vinculado.'),
                            ]),
                    ]),
            ]);
    }
}
