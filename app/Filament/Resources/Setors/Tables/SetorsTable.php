<?php

namespace App\Filament\Resources\Setors\Tables;

use App\Models\Empresa;
use App\Models\Grupo;
use App\Models\Unidade;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class SetorsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->searchPlaceholder('Buscar por setor')
            ->filtersFormColumns(2)
            ->columns([
                TextColumn::make('unidade.empresa.grupo.nome')
                    ->label('Grupo/Cliente')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('unidade.empresa.nm_razao_social')
                    ->label('Empresa')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('unidade.nome')
                    ->label('Unidade')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('nome')
                    ->label('Setor/Departamento')
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
                            ->native(false)
                            ->live(),
                        Select::make('empresa_id')
                            ->label('Empresa')
                            ->options(function (Get $get) {
                                $grupoId = $get('grupo_id');

                                return Empresa::query()
                                    ->when($grupoId, fn (Builder $query) => $query->where('grupo_id', $grupoId))
                                    ->orderBy('nm_razao_social')
                                    ->get()
                                    ->mapWithKeys(fn ($e) => [
                                        $e->id => $e->nm_razao_social
                                            ?: ($e->nm_fantasia ?: '-- sem nome --'),
                                    ])
                                    ->all();
                            })
                            ->searchable()
                            ->preload()
                            ->native(false)
                            ->disabled(fn (Get $get) => $get('grupo_id') === null),
                    ])
                    ->query(function (Builder $query, array $data) {
                        $grupoId = $data['grupo_id'] ?? null;
                        $empresaId = $data['empresa_id'] ?? null;

                        if ($grupoId) {
                            $query->whereHas('unidade.empresa.grupo', fn (Builder $q) => $q->where('id', $grupoId));
                        }

                        if ($empresaId) {
                            $query->whereHas('unidade.empresa', fn (Builder $q) => $q->where('id', $empresaId));
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
