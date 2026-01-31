<?php

namespace App\Filament\Resources\Grupos\Tables;

use Filament\Forms\Components\TextInput;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class GruposTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->searchPlaceholder('Buscar por nome do grupo')
            ->filtersFormColumns(2)
            ->columns([
                TextColumn::make('nome')
                    ->label('Nome')
                    ->searchable(),
                TextColumn::make('status_integracao')
                    ->label('Status integração')
                    ->sortable()
                    ->badge()
                    ->colors([
                        'success' => 'A',
                        'danger' => 'I',
                    ]),
                IconColumn::make('ativo')
                    ->label('Ativo')
                    ->boolean(),
                TextColumn::make('updated_at')
                    ->label('Atualizado em')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Filter::make('grupo_nome')
                    ->label('Grupo/Cliente')
                    ->form([
                        TextInput::make('grupo')
                            ->label('Grupo/Cliente')
                            ->placeholder('Digite o nome do grupo'),
                    ])
                    ->query(function (Builder $query, array $data) {
                        $nome = $data['grupo'] ?? null;

                        if (! $nome) {
                            return;
                        }

                        $query->where('nome', 'like', "%{$nome}%");
                    }),
                SelectFilter::make('ativo')
                    ->label('Status interno')
                    ->options([
                        '1' => 'Ativo',
                        '0' => 'Inativo',
                    ]),
                SelectFilter::make('status_integracao')
                    ->label('Status integração (A/I)')
                    ->options([
                        'A' => 'Ativo',
                        'I' => 'Inativo',
                    ]),
            ], layout: FiltersLayout::AboveContent)
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}