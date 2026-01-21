<?php

declare(strict_types=1);

namespace App\Filament\Ops\Pages;

use App\Models\Solicitacao;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class SolicitacoesPonto extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationLabel = 'Solicitações de Ajuste';
    protected static string|\UnitEnum|null $navigationGroup = 'Ponto';
    protected static ?int $navigationSort = 2;
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-clipboard-document-check';

    public static function canAccess(): bool
    {
        $user = Auth::user();

        return userIsSuperAdmin($user) || userIsRh($user);
    }

    public function getView(): string
    {
        return 'filament.ops.pages.solicitacoes-ponto';
    }

    public function table(Table $table): Table
    {
        $user = Auth::user();
        $query = Solicitacao::query()
            ->with(['colaborador.empresa', 'solicitante'])
            ->where('tipo', 'ajuste_ponto');

        if ($user) {
            $query = applyColaboradorRelationScope($query, $user, 'colaborador');
        } else {
            $query->whereRaw('1 = 0');
        }

        return $table
            ->query($query)
            ->columns([
                Tables\Columns\TextColumn::make('colaborador.nome')
                    ->label('Colaborador')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('colaborador.empresa.nome')
                    ->label('Empresa')
                    ->sortable(),
                Tables\Columns\TextColumn::make('solicitante.name')
                    ->label('Solicitante')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn(string $state) => match ($state) {
                        'pendente' => 'Pendente',
                        'aprovado' => 'Aprovado',
                        'recusado' => 'Recusado',
                        default => ucfirst($state),
                    })
                    ->color(fn(string $state) => match ($state) {
                        'pendente' => 'warning',
                        'aprovado' => 'success',
                        'recusado' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Criado em')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('descricao')
                    ->label('Descrição')
                    ->wrap(),
            ])
            ->actions([
                Action::make('aprovar')
                    ->label('Aprovar')
                    ->color('success')
                    ->icon('heroicon-o-check-circle')
                    ->visible(fn(Solicitacao $record) => $record->status === 'pendente')
                    ->form([
                        Textarea::make('motivo')
                            ->label('Motivo (opcional)')
                            ->rows(3)
                            ->placeholder('Informe o motivo da aprovação...'),
                    ])
                    ->action(function (Solicitacao $record, array $data): void {
                        $record->status = 'aprovado';
                        $record->save();

                        activity('solicitacao_ponto')
                            ->performedOn($record)
                            ->causedBy(Auth::user())
                            ->withProperties([
                                'acao' => 'aprovar',
                                'motivo' => $data['motivo'] ?? null,
                            ])
                            ->log('Solicitação aprovada');

                        if ($record->solicitante) {
                            Notification::make()
                                ->title('Sua solicitação de ajuste foi aprovada')
                                ->success()
                                ->sendToDatabase($record->solicitante);
                        }
                    }),
                Action::make('recusar')
                    ->label('Recusar')
                    ->color('danger')
                    ->icon('heroicon-o-x-circle')
                    ->visible(fn(Solicitacao $record) => $record->status === 'pendente')
                    ->form([
                        Textarea::make('motivo')
                            ->label('Motivo')
                            ->required()
                            ->rows(3)
                            ->placeholder('Informe o motivo da recusa...'),
                    ])
                    ->action(function (Solicitacao $record, array $data): void {
                        $record->status = 'recusado';
                        $record->save();

                        activity('solicitacao_ponto')
                            ->performedOn($record)
                            ->causedBy(Auth::user())
                            ->withProperties([
                                'acao' => 'recusar',
                                'motivo' => $data['motivo'] ?? null,
                            ])
                            ->log('Solicitação recusada');

                        if ($record->solicitante) {
                            Notification::make()
                                ->title('Sua solicitação de ajuste foi recusada')
                                ->danger()
                                ->sendToDatabase($record->solicitante);
                        }
                    }),
            ])
            ->defaultSort('created_at', 'desc');
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('ajuda')
                ->label('Ajuda')
                ->icon('heroicon-o-information-circle')
                ->action(function () {
                    Notification::make()
                        ->title('Aprove ou recuse com justificativa. Todas as ações ficam auditadas.')
                        ->info()
                        ->send();
                }),
        ];
    }

    public static function getUrl(array $parameters = [], bool $isAbsolute = true, ?string $panel = null, ?\Illuminate\Database\Eloquent\Model $tenant = null): string
    {
        return parent::getUrl($parameters, $isAbsolute, 'ops', $tenant);
    }
}
