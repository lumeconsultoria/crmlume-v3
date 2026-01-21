<?php

namespace App\Filament\Resources\Setors\Schemas;

use App\Models\Empresa;
use App\Models\Grupo;
use App\Models\Unidade;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class SetorForm
{
    public static function configure(Schema $schema): Schema
    {
        // Hierarquia obrigatória: Grupo → Empresa → Unidade → Setor.
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
                                    ->afterStateHydrated(fn(Set $set, $state, $record) => $set('grupo_id', $record?->unidade?->empresa?->grupo_id))
                                    ->afterStateUpdated(function (Set $set): void {
                                        $set('empresa_id', null);
                                        $set('unidade_id', null);
                                    })
                                    ->helperText('Obrigatório. Selecione o Grupo do vínculo.'),
                                Select::make('empresa_id')
                                    ->label('Empresa Nome')
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
                                    ->required()
                                    ->searchable()
                                    ->preload()
                                    ->live()
                                    ->dehydrated(false)
                                    ->afterStateHydrated(fn(Set $set, $state, $record) => $set('empresa_id', $record?->unidade?->empresa_id))
                                    ->afterStateUpdated(fn(Set $set) => $set('unidade_id', null))
                                    ->disabled(fn(Get $get): bool => ! $get('grupo_id'))
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
                                    ->disabled(fn(Get $get): bool => ! $get('empresa_id'))
                                    ->helperText('Obrigatório. Selecione a Unidade do vínculo.'),
                            ]),
                    ]),
                Section::make('Identificação')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('nome')
                                    ->label('Setor Nome')
                                    ->required()
                                    ->helperText('Obrigatório. Nome oficial do Setor.'),
                            ]),
                    ]),
                Section::make('Endereço / Dados Complementares')
                    ->collapsed()
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Toggle::make('ativo')
                                    ->label('Ativo')
                                    ->required()
                                    ->helperText('Obrigatório. Define se o Setor está ativo.'),
                            ]),
                    ]),
            ]);
    }
}
