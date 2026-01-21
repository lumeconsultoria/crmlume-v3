<?php

declare(strict_types=1);

namespace App\Filament\Resources\EmailPendencias;

use App\Filament\Resources\EmailPendencias\Pages\ListEmailPendencias;
use App\Filament\Resources\EmailPendencias\Tables\EmailPendenciasTable;
use App\Models\EmailPendencia;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class EmailPendenciaResource extends Resource
{
    protected static ?string $model = EmailPendencia::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedEnvelope;

    protected static string|\UnitEnum|null $navigationGroup = 'RH';

    protected static ?int $navigationSort = 50;

    public static function shouldRegisterNavigation(): bool
    {
        $user = auth()->user();

        return $user?->can('viewAny', EmailPendencia::class) ?? false;
    }

    public static function canViewAny(): bool
    {
        $user = auth()->user();

        return $user?->can('viewAny', EmailPendencia::class) ?? false;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema;
    }

    public static function table(Table $table): Table
    {
        return EmailPendenciasTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListEmailPendencias::route('/'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
