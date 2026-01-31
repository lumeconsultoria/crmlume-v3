<?php

namespace App\Filament\Resources\Empresas\Tables;

use App\Models\Grupo;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class EmpresasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->searchPlaceholder('Buscar por nome ou CNPJ')
            ->filtersFormColumns(2)
            ->columns([
                TextColumn::make('grupo.nome')
                    ->label('Grupo/Cliente')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('nm_razao_social')
                    ->label('Nome/Razão Social')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('status_integracao')
                    ->label('Status integração')
                    ->badge()
                    ->colors([
                        'success' => 'A',
                        'danger' => 'I',
                        'warning' => 'F',
                        'gray' => 'T',
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
                Filter::make('estrutura')
                    ->label('Estrutura')
                    ->columns(2)
                    ->form([
                        Select::make('grupo_id')
                            ->label('Grupo/Cliente')
                            ->options(fn () => Grupo::query()
                                ->orderBy('nome')
                                ->get()
                                ->mapWithKeys(fn ($g) => [$g->id => $g->nome ?? '-- sem nome --'])
                                ->all()
                            )
                            ->searchable()
                            ->preload()
                            ->native(false),
                    ])
                    ->query(function (Builder $query, array $data) {
                        $grupoId = $data['grupo_id'] ?? null;

                        if ($grupoId) {
                            $query->where('grupo_id', $grupoId);
                        }
                    }),
                Filter::make('status')
                    ->label('Status')
                    ->columns(2)
                    ->form([
                        Select::make('status_integracao')
                            ->label('Status integração')
                            ->options([
                                'A' => 'Ativo',
                                'I' => 'Inativo',
                                'F' => 'Férias',
                                'T' => 'Afastado',
                            ])
                            ->native(false),
                        Select::make('ativo')
                            ->label('Status interno')
                            ->options([
                                '1' => 'Ativo',
                                '0' => 'Inativo',
                            ])
                            ->native(false),
                    ])
                    ->query(function (Builder $query, array $data) {
                        $statusIntegracao = $data['status_integracao'] ?? null;
                        $ativo = $data['ativo'] ?? null;

                        if ($statusIntegracao) {
                            $query->where('status_integracao', $statusIntegracao);
                        }

                        if ($ativo !== null && $ativo !== '') {
                            $query->where('ativo', $ativo);
                        }
                    }),
            ], layout: FiltersLayout::AboveContent)
            ->deferFilters(false)
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
