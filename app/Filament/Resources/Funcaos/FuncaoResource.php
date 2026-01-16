<?php

namespace App\Filament\Resources\Funcaos;

use App\Filament\Resources\Funcaos\Pages\CreateFuncao;
use App\Filament\Resources\Funcaos\Pages\EditFuncao;
use App\Filament\Resources\Funcaos\Pages\ListFuncaos;
use App\Filament\Resources\Funcaos\Schemas\FuncaoForm;
use App\Filament\Resources\Funcaos\Tables\FuncaosTable;
use App\Models\Funcao;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class FuncaoResource extends Resource
{
    protected static ?string $model = Funcao::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Funcao';

    public static function form(Schema $schema): Schema
    {
        return FuncaoForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FuncaosTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListFuncaos::route('/'),
            'create' => CreateFuncao::route('/create'),
            'edit' => EditFuncao::route('/{record}/edit'),
        ];
    }
}
