<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Models\Empresa;
use App\Models\Funcao;
use App\Models\Grupo;
use App\Models\Setor;
use App\Models\Unidade;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class GovernancaAcesso extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationLabel = 'Governança de Acesso';
    protected static string|\UnitEnum|null $navigationGroup = 'Segurança & Governança';
    protected static ?int $navigationSort = 1;
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-shield-check';

    public static function canAccess(): bool
    {
        $user = Auth::user();

        // Permite super admin e staff administrativo Lume acessar sem travar o painel.
        return userIsSuperAdmin($user) || userIsAdminLume($user);
    }

    public function getView(): string
    {
        return 'filament.pages.governanca-acesso';
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                User::query()->with([
                    'roles',
                    'colaborador.empresa.grupo',
                    'colaborador.unidade',
                    'colaborador.funcao.setor',
                ])
            )
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nome')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('roles.name')
                    ->label('Roles')
                    ->badge()
                    ->separator(', ')
                    ->sortable(),
                Tables\Columns\TextColumn::make('colaborador.empresa.grupo.nome')
                    ->label('Grupo')
                    ->sortable(),
                Tables\Columns\TextColumn::make('colaborador.empresa.nome')
                    ->label('Empresa')
                    ->sortable(),
                Tables\Columns\IconColumn::make('ativo')
                    ->label('Status')
                    ->boolean(),
            ])
            ->actions([
                Action::make('editar_acesso')
                    ->label('Editar Acesso')
                    ->icon('heroicon-o-lock-closed')
                    ->modalHeading('Governança de Acesso')
                    ->modalDescription('Ajuste apenas o escopo organizacional do usuário selecionado.')
                    ->form([
                        Section::make('Escopo Organizacional')
                            ->schema([
                                Select::make('grupo_id')
                                    ->label('Grupo')
                                    ->options(fn() => Grupo::orderBy('nome')->pluck('nome', 'id')->all())
                                    ->searchable(),
                                Select::make('empresa_id')
                                    ->label('Empresa')
                                    ->options(fn() => Empresa::orderBy('nome')->pluck('nome', 'id')->all())
                                    ->searchable(),
                                Select::make('unidade_id')
                                    ->label('Unidade')
                                    ->options(fn() => Unidade::orderBy('nome')->pluck('nome', 'id')->all())
                                    ->searchable(),
                                Select::make('setor_id')
                                    ->label('Setor')
                                    ->options(fn() => Setor::orderBy('nome')->pluck('nome', 'id')->all())
                                    ->searchable(),
                            ]),
                    ])
                    ->mountUsing(function (Action $action, User $record): void {
                        // Segurança: roles não são atribuídas aqui. Apenas escopo organizacional.
                        $action->fillForm([
                            'grupo_id' => $record->colaborador?->empresa?->grupo_id,
                            'empresa_id' => $record->colaborador?->empresa_id,
                            'unidade_id' => $record->colaborador?->unidade_id,
                            'setor_id' => $record->colaborador?->funcao?->setor_id,
                        ]);
                    })
                    ->action(function (User $record, array $data): void {
                        $actor = Auth::user();
                        $escopoBefore = [
                            'grupo_id' => $record->colaborador?->empresa?->grupo_id,
                            'empresa_id' => $record->colaborador?->empresa_id,
                            'unidade_id' => $record->colaborador?->unidade_id,
                            'setor_id' => $record->colaborador?->funcao?->setor_id,
                        ];

                        $colaborador = $record->colaborador;
                        if ($colaborador) {
                            $grupoId = $data['grupo_id'] ?? null;
                            $empresaId = $data['empresa_id'] ?? null;
                            $unidadeId = $data['unidade_id'] ?? null;
                            $setorId = $data['setor_id'] ?? null;

                            if ($grupoId && $empresaId) {
                                $empresa = Empresa::find($empresaId);
                                if (! $empresa || $empresa->grupo_id !== (int) $grupoId) {
                                    Notification::make()
                                        ->title('Empresa não pertence ao grupo selecionado')
                                        ->danger()
                                        ->send();
                                    return;
                                }
                            }

                            if ($empresaId && $unidadeId) {
                                $unidade = Unidade::find($unidadeId);
                                if (! $unidade || $unidade->empresa_id !== (int) $empresaId) {
                                    Notification::make()
                                        ->title('Unidade não pertence à empresa selecionada')
                                        ->danger()
                                        ->send();
                                    return;
                                }
                            }

                            if ($unidadeId && $setorId) {
                                $setor = Setor::find($setorId);
                                if (! $setor || $setor->unidade_id !== (int) $unidadeId) {
                                    Notification::make()
                                        ->title('Setor não pertence à unidade selecionada')
                                        ->danger()
                                        ->send();
                                    return;
                                }
                            }

                            if ($empresaId) {
                                $colaborador->empresa_id = (int) $empresaId;
                            }

                            if ($unidadeId) {
                                $colaborador->unidade_id = (int) $unidadeId;
                            }

                            if ($setorId) {
                                $funcao = Funcao::where('setor_id', $setorId)->orderBy('id')->first();

                                if (! $funcao) {
                                    Notification::make()
                                        ->title('Nenhuma função encontrada para o setor selecionado')
                                        ->danger()
                                        ->send();
                                    return;
                                }

                                $colaborador->funcao_id = $funcao->id;
                            }

                            $colaborador->save();
                        }

                        $escopoAfter = [
                            'grupo_id' => $record->colaborador?->empresa?->grupo_id,
                            'empresa_id' => $record->colaborador?->empresa_id,
                            'unidade_id' => $record->colaborador?->unidade_id,
                            'setor_id' => $record->colaborador?->funcao?->setor_id,
                        ];

                        activity('governanca_acesso')
                            ->performedOn($record)
                            ->causedBy($actor)
                            ->withProperties([
                                'observacao' => 'Ajuste apenas de escopo organizacional. Roles são gerenciadas no cadastro do usuário.',
                                'escopo_before' => $escopoBefore,
                                'escopo_after' => $escopoAfter,
                            ])
                            ->log('Atualização de acesso');

                        Notification::make()
                            ->title('Acesso atualizado com sucesso')
                            ->success()
                            ->send();
                    }),
            ])
            ->defaultSort('name');
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('ajuda')
                ->label('Ajuda')
                ->icon('heroicon-o-information-circle')
                ->action(function () {
                    Notification::make()
                        ->title('Use esta página apenas para ajustes de acesso e escopo. Todas as alterações são auditadas.')
                        ->info()
                        ->send();
                }),
        ];
    }
}
