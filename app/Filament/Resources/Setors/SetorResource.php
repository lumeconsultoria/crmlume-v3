<?php

namespace App\Filament\Resources\Setors;

use App\Filament\Resources\Setors\Pages\CreateSetor;
use App\Filament\Resources\Setors\Pages\EditSetor;
use App\Filament\Resources\Setors\Pages\ListSetors;
use App\Filament\Resources\Setors\Schemas\SetorForm;
use App\Filament\Resources\Setors\Tables\SetorsTable;
use App\Models\Setor;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class SetorResource extends Resource
{
    protected static ?string $model = Setor::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string|\UnitEnum|null $navigationGroup = 'Cadastro';

    protected static ?int $navigationSort = 4;

    protected static ?string $recordTitleAttribute = 'Setor';

    public static function form(Schema $schema): Schema
    {
        return SetorForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SetorsTable::configure($table);
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
            'index' => ListSetors::route('/'),
            'create' => CreateSetor::route('/create'),
            'edit' => EditSetor::route('/{record}/edit'),
        ];
    }
}
