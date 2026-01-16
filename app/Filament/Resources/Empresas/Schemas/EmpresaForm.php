<?php

namespace App\Filament\Resources\Empresas\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class EmpresaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('grupo_id')
                    ->relationship('grupo', 'id')
                    ->required(),
                TextInput::make('nome')
                    ->required(),
                Toggle::make('ativo')
                    ->required(),
            ]);
    }
}
