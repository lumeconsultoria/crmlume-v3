<?php

namespace App\Filament\Resources\Colaboradors\Tables;

use App\Models\Empresa;
use App\Models\Funcao;
use App\Models\Grupo;
use App\Models\Setor;
use App\Models\Unidade;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
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

class ColaboradorsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->searchPlaceholder('Buscar por colaborador')
            ->filtersFormColumns(2)
            ->columns([
                TextColumn::make('nome')
                    ->label('Nome')
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
                                    ->mapWithKeys(fn ($e) => [$e->id => $e->nm_razao_social ?? '-- sem nome --'])
                                    ->all();
                            })
                            ->searchable()
                            ->preload()
                            ->native(false)
                            ->disabled(fn (Get $get) => $get('grupo_id') === null)
                            ->live(),
                        Select::make('unidade_id')
                            ->label('Unidade')
                            ->options(function (Get $get) {
                                $empresaId = $get('empresa_id');

                                return Unidade::query()
                                    ->when($empresaId, fn (Builder $query) => $query->where('empresa_id', $empresaId))
                                    ->orderBy('nome')
                                    ->get()
                                    ->mapWithKeys(fn ($u) => [$u->id => $u->nome ?? '-- sem nome --'])
                                    ->all();
                            })
                            ->searchable()
                            ->preload()
                            ->native(false)
                            ->disabled(fn (Get $get) => $get('empresa_id') === null)
                            ->live(),
                        Select::make('setor_id')
                            ->label('Setor')
                            ->options(function (Get $get) {
                                $unidadeId = $get('unidade_id');

                                return Setor::query()
                                    ->when($unidadeId, fn (Builder $query) => $query->where('unidade_id', $unidadeId))
                                    ->orderBy('nome')
                                    ->get()
                                    ->mapWithKeys(fn ($s) => [$s->id => $s->nome ?? '-- sem nome --'])
                                    ->all();
                            })
                            ->searchable()
                            ->preload()
                            ->native(false)
                            ->disabled(fn (Get $get) => $get('unidade_id') === null)
                            ->live(),
                        Select::make('funcao_id')
                            ->label('Função')
                            ->options(function (Get $get) {
                                $setorId = $get('setor_id');

                                return Funcao::query()
                                    ->when($setorId, fn (Builder $query) => $query->where('setor_id', $setorId))
                                    ->orderBy('nome')
                                    ->get()
                                    ->mapWithKeys(fn ($f) => [$f->id => $f->nome ?? '-- sem nome --'])
                                    ->all();
                            })
                            ->searchable()
                            ->preload()
                            ->native(false)
                            ->disabled(fn (Get $get) => $get('setor_id') === null),
                        TextInput::make('colaborador')
                            ->label('Colaborador')
                            ->placeholder('Digite o nome do colaborador'),
                    ])
                    ->query(function (Builder $query, array $data) {
                        $grupoId = $data['grupo_id'] ?? null;
                        $empresaId = $data['empresa_id'] ?? null;
                        $unidadeId = $data['unidade_id'] ?? null;
                        $setorId = $data['setor_id'] ?? null;
                        $funcaoId = $data['funcao_id'] ?? null;
                        $colaborador = $data['colaborador'] ?? null;

                        if ($grupoId) {
                            $query->whereHas('empresa.grupo', fn (Builder $q) => $q->where('id', $grupoId));
                        }

                        if ($empresaId) {
                            $query->where('empresa_id', $empresaId);
                        }

                        if ($unidadeId) {
                            $query->where('unidade_id', $unidadeId);
                        }

                        if ($setorId) {
                            $query->whereHas('funcao.setor', fn (Builder $q) => $q->where('id', $setorId));
                        }

                        if ($funcaoId) {
                            $query->where('funcao_id', $funcaoId);
                        }

                        if ($colaborador) {
                            $query->where('nome', 'like', "%{$colaborador}%");
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
                                'I' => 'Inativo/Demitido',
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
