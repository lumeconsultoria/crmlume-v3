<?php

namespace App\Filament\Resources\Unidades\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class UnidadeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
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
