<?php

namespace App\Filament\Resources\Colaboradors;

use App\Filament\Resources\Colaboradors\Pages\CreateColaborador;
use App\Filament\Resources\Colaboradors\Pages\EditColaborador;
use App\Filament\Resources\Colaboradors\Pages\ListColaboradors;
use App\Filament\Resources\Colaboradors\Schemas\ColaboradorForm;
use App\Filament\Resources\Colaboradors\Tables\ColaboradorsTable;
use App\Models\Colaborador;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ColaboradorResource extends Resource
{
    protected static ?string $model = Colaborador::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Colaborador';

    public static function form(Schema $schema): Schema
    {
        return ColaboradorForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ColaboradorsTable::configure($table);
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
            'index' => ListColaboradors::route('/'),
            'create' => CreateColaborador::route('/create'),
            'edit' => EditColaborador::route('/{record}/edit'),
        ];
    }
}
