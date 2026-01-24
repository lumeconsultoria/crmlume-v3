<?php

namespace App\Filament\Resources\Funcaos\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class FuncaoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('setor_id')
                    ->relationship('setor', 'nome')
                    ->required(),
                TextInput::make('nome')
                    ->required(),
                Toggle::make('ativo')
                    ->required(),
            ]);
    }
}
