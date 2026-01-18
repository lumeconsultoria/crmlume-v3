<?php

namespace App\Filament\Resources\Colaboradors\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ColaboradorForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('funcao_id')
                    ->relationship('funcao', 'nome')
                    ->required(),
                Select::make('unidade_id')
                    ->relationship('unidade', 'nome')
                    ->required(),
                Select::make('empresa_id')
                    ->relationship('empresa', 'nome')
                    ->required(),
                TextInput::make('nome')
                    ->required(),
                Toggle::make('ativo')
                    ->required(),
            ]);
    }
}
