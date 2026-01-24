<?php

namespace App\Filament\Resources\Setors\Schemas;

use App\Models\Unidade;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SetorForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Estrutura')
                    ->schema([
                        Select::make('unidade_id')
                            ->label('Unidade')
                            ->relationship('unidade', 'nome')
                            ->getOptionLabelFromRecordUsing(fn (Unidade $record) => $record->nome ?? '-- sem nome --')
                            ->required()
                            ->searchable()
                            ->preload(),
                    ]),

                Section::make('Dados do Setor')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('nome')
                                ->label('Nome do Setor')
                                ->required(),
                            TextInput::make('codigo_externo')
                                ->label('Código Externo')
                                ->maxLength(50)
                                ->helperText('cd_interno_setor'),
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