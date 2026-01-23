<?php

declare(strict_types=1);

namespace App\Filament\Resources\PrimeiroAcessos;

use App\Filament\Resources\PrimeiroAcessos\Pages\ListPrimeiroAcessos;
use App\Filament\Resources\PrimeiroAcessos\Tables\PrimeiroAcessosTable;
use App\Models\PrimeiroAcesso;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PrimeiroAcessoResource extends Resource
{
    protected static ?string $model = PrimeiroAcesso::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

    protected static string|\UnitEnum|null $navigationGroup = 'Segurança & Governança';

    protected static ?int $navigationSort = 21;

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()?->can('viewAny', PrimeiroAcesso::class) ?? false;
    }

    public static function canViewAny(): bool
    {
        $user = auth()->user();

        return $user?->can('viewAny', PrimeiroAcesso::class) ?? false;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema;
    }

    public static function table(Table $table): Table
    {
        return PrimeiroAcessosTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPrimeiroAcessos::route('/'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
