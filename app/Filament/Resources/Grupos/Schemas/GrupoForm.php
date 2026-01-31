<?php

namespace App\Filament\Resources\Grupos\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class GrupoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Identificação')
                    ->schema([
                        Grid::make(3)->schema([
                            Grid::make(2)->schema([
                                TextInput::make('nome')
                                    ->label('Nome/Referência')
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('nr_cnpj')
                                    ->label('CNPJ')
                                    ->placeholder('00.000.000/0000-00')
                                    ->mask('99.999.999/9999-99')
                                    ->dehydrateStateUsing(fn ($state) => preg_replace('/\D/', '', $state ?? ''))
                                    ->rule('nullable', 'digits:14'),
                            ])->columnSpan(2),
                            FileUpload::make('logo_path')
                                ->label('Logo')
                                ->image()
                                ->imageEditor()
                                ->disk('public')
                                ->directory('grupos-logos')
                                ->maxSize(2048)
                                ->helperText('Imagens quadradas com fundo transparente/branco funcionam melhor (até 2MB).'),
                        ]),
                    ]),

                Section::make('Integração / Status')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('status_integracao')
                                ->label('Status Integração')
                                ->options([
                                    'A' => 'Ativo',
                                    'I' => 'Inativo',
                                ])
                                ->default('A')
                                ->required(),
                            TextInput::make('codigo_externo')
                                ->label('Código Externo')
                                ->maxLength(50)
                                ->helperText('cd_interno_grupo'),
                        ]),
                        Grid::make(2)->schema([
                            TextInput::make('indexmed_id')
                                ->label('ID IndexMed')
                                ->numeric()
                                ->minValue(1)
                                ->nullable(),
                            Toggle::make('ativo')
                                ->label('Ativo')
                                ->required()
                                ->default(true),
                        ]),
                    ]),
            ]);
    }
}

