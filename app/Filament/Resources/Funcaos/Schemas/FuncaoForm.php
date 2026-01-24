<?php

namespace App\Filament\Resources\Funcaos\Schemas;

use App\Models\Setor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class FuncaoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Estrutura')
                    ->schema([
                        Select::make('setor_id')
                            ->label('Setor')
                            ->relationship('setor', 'nome')
                            ->getOptionLabelFromRecordUsing(fn (Setor $record) => $record->nome ?? '-- sem nome --')
                            ->required()
                            ->searchable()
                            ->preload(),
                    ]),

                Section::make('Dados da Função')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('nome')
                                ->label('Nome da Função')
                                ->required(),
                            TextInput::make('cd_cbo')
                                ->label('CBO')
                                ->maxLength(20),
                            TextInput::make('codigo_externo')
                                ->label('Código Externo')
                                ->maxLength(50)
                                ->helperText('cd_interno_funcao'),
                        ]),
                        TextInput::make('descricao')
                            ->label('Descrição')
                            ->maxLength(255),
                    ]),

                Section::make('Status / Integração')
                    ->schema([
                        Grid::make(2)->schema([
                            Toggle::make('ativo')
                                ->label('Ativo')
                                ->required()
                                ->default(true),
                            Select::make('status_integracao')
                                ->label('Status Integração')
                                ->options([
                                    'A' => 'Ativo',
                                    'I' => 'Inativo',
                                ])
                                ->default('A')
                                ->required(),
                        ]),
                        TextInput::make('indexmed_id')
                            ->label('ID IndexMed')
                            ->numeric()
                            ->nullable(),
                    ]),
            ]);
    }
}