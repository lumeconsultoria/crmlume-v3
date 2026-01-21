<?php

declare(strict_types=1);

namespace App\Filament\Ops\Resources\RegistroPontos;

use App\Filament\Ops\Resources\RegistroPontos\Pages\CreateRegistroPonto;
use App\Filament\Ops\Resources\RegistroPontos\Pages\ListRegistroPontos;
use App\Filament\Ops\Resources\RegistroPontos\Pages\ViewRegistroPonto;
use App\Models\RegistroPonto;
use App\Models\Solicitacao;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class RegistroPontoResource extends Resource
{
    protected static ?string $model = RegistroPonto::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClock;

    protected static ?string $navigationLabel = 'Cartão de Ponto';

    protected static string|\UnitEnum|null $navigationGroup = 'Ponto';

    protected static ?int $navigationSort = 1;

    public static function shouldRegisterNavigation(): bool
    {
        return Gate::allows('viewAny', RegistroPonto::class);
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Select::make('tipo')
                    ->label('Tipo')
                    ->options([
                        'entrada' => 'Entrada',
                        'saida_intervalo' => 'Saída para intervalo',
                        'retorno_intervalo' => 'Retorno do intervalo',
                        'saida_jornada' => 'Saída da jornada',
                    ])
                    ->required()
                    ->helperText('O horário é calculado automaticamente no backend.'),
            ]);
    }

    public static function table(Table $table): Table
    {
        $user = Auth::user();

        return $table
            ->query(
                self::baseQuery($user)
            )
            ->recordUrl(fn(RegistroPonto $record) => static::getUrl('view', ['record' => $record]))
            ->columns([
                Tables\Columns\TextColumn::make('data')
                    ->label('Data')
                    ->date('d/m/Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('entrada_1')
                    ->label('Entrada 1')
                    ->state(fn(RegistroPonto $record) => $record->entrada_1)
                    ->placeholder('-'),
                Tables\Columns\TextColumn::make('saida_1')
                    ->label('Saída 1')
                    ->state(fn(RegistroPonto $record) => $record->saida_1)
                    ->placeholder('-'),
                Tables\Columns\TextColumn::make('entrada_2')
                    ->label('Entrada 2')
                    ->state(fn(RegistroPonto $record) => $record->entrada_2)
                    ->placeholder('-'),
                Tables\Columns\TextColumn::make('saida_2')
                    ->label('Saída 2')
                    ->state(fn(RegistroPonto $record) => $record->saida_2)
                    ->placeholder('-'),
                Tables\Columns\TextColumn::make('total_horas')
                    ->label('Total de horas')
                    ->state(fn(RegistroPonto $record) => self::formatTotalHoras($record))
                    ->placeholder('-'),
                Tables\Columns\TextColumn::make('status_dia')
                    ->label('Status')
                    ->badge()
                    ->state(fn(RegistroPonto $record) => self::resolveStatus($record))
                    ->color(fn(string $state) => match ($state) {
                        'pendente' => 'warning',
                        'aprovado' => 'success',
                        'rejeitado' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn(string $state) => match ($state) {
                        'pendente' => 'Pendente',
                        'aprovado' => 'Aprovado',
                        'rejeitado' => 'Rejeitado',
                        default => ucfirst($state),
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('mes')
                    ->label('Mês')
                    ->options(self::mesesDisponiveis())
                    ->query(function (Builder $query, array $data): Builder {
                        $mes = $data['value'] ?? null;

                        return $mes
                            ? $query->whereMonth('data', (int) $mes)
                            : $query;
                    }),
            ])
            ->actions([
                Action::make('ver_espelho')
                    ->label('Ver Espelho')
                    ->icon('heroicon-o-clock')
                    ->url(fn(RegistroPonto $record) => static::getUrl('view', ['record' => $record]))
                    ->visible(fn(RegistroPonto $record) => Gate::allows('view', $record)),
                Action::make('aprovar')
                    ->label('Aprovar')
                    ->icon('heroicon-o-check-circle')
                    ->requiresConfirmation()
                    ->visible(fn(RegistroPonto $record) => Gate::allows('update', $record)
                        && self::resolveSolicitacao($record))
                    ->action(function (RegistroPonto $record): void {
                        $solicitacao = self::resolveSolicitacao($record);

                        if (! $solicitacao) {
                            Notification::make()
                                ->title('Nenhuma solicitação pendente para este registro.')
                                ->warning()
                                ->send();
                            return;
                        }

                        $solicitacao->status = 'aprovado';
                        $solicitacao->save();

                        activity('solicitacao_ponto')
                            ->performedOn($solicitacao)
                            ->causedBy(Auth::user())
                            ->withProperties(['acao' => 'aprovar'])
                            ->log('Solicitação aprovada');

                        Notification::make()
                            ->title('Solicitação aprovada')
                            ->success()
                            ->send();
                    }),
                Action::make('rejeitar')
                    ->label('Rejeitar')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->visible(fn(RegistroPonto $record) => Gate::allows('update', $record)
                        && self::resolveSolicitacao($record))
                    ->action(function (RegistroPonto $record): void {
                        $solicitacao = self::resolveSolicitacao($record);

                        if (! $solicitacao) {
                            Notification::make()
                                ->title('Nenhuma solicitação pendente para este registro.')
                                ->warning()
                                ->send();
                            return;
                        }

                        $solicitacao->status = 'recusado';
                        $solicitacao->save();

                        activity('solicitacao_ponto')
                            ->performedOn($solicitacao)
                            ->causedBy(Auth::user())
                            ->withProperties(['acao' => 'recusar'])
                            ->log('Solicitação recusada');

                        Notification::make()
                            ->title('Solicitação recusada')
                            ->success()
                            ->send();
                    }),
            ])
            ->defaultSort('data', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListRegistroPontos::route('/'),
            'create' => CreateRegistroPonto::route('/create'),
            'view' => ViewRegistroPonto::route('/{record}'),
        ];
    }

    private static function resolveSolicitacao(RegistroPonto $record): ?Solicitacao
    {
        $data = $record->data instanceof \Carbon\CarbonInterface
            ? $record->data->format('d/m/Y')
            : (string) $record->data;

        return Solicitacao::query()
            ->where('colaborador_id', $record->colaborador_id)
            ->where('tipo', 'ajuste_ponto')
            ->where('descricao', 'like', '%' . $data . '%')
            ->whereIn('status', ['pendente', 'aprovado', 'recusado'])
            ->latest('id')
            ->first();
    }

    public static function resolveStatus(RegistroPonto $record): string
    {
        $solicitacao = self::resolveSolicitacao($record);

        return $solicitacao?->status ?? 'aprovado';
    }

    public static function formatTotalHoras(RegistroPonto $record): string
    {
        $entrada = $record->entrada_1;
        $saida = $record->saida_2;

        if (! $entrada || ! $saida) {
            return '-';
        }

        $entradaTime = \Carbon\Carbon::createFromFormat('H:i:s', $entrada);
        $saidaTime = \Carbon\Carbon::createFromFormat('H:i:s', $saida);

        $minutos = $entradaTime->diffInMinutes($saidaTime, false);

        if ($record->saida_1 && $record->entrada_2) {
            $saidaIntervalo = \Carbon\Carbon::createFromFormat('H:i:s', $record->saida_1);
            $retornoIntervalo = \Carbon\Carbon::createFromFormat('H:i:s', $record->entrada_2);
            $minutos -= $saidaIntervalo->diffInMinutes($retornoIntervalo, false);
        }

        if ($minutos < 0) {
            return '-';
        }

        $minutos = (int) $minutos;
        $horas = intdiv($minutos, 60);
        $resto = $minutos % 60;

        return sprintf('%02d:%02d', $horas, $resto);
    }

    private static function mesesDisponiveis(): array
    {
        return [
            '01' => 'Janeiro',
            '02' => 'Fevereiro',
            '03' => 'Março',
            '04' => 'Abril',
            '05' => 'Maio',
            '06' => 'Junho',
            '07' => 'Julho',
            '08' => 'Agosto',
            '09' => 'Setembro',
            '10' => 'Outubro',
            '11' => 'Novembro',
            '12' => 'Dezembro',
        ];
    }

    private static function baseQuery(?\App\Models\User $user): Builder
    {
        $query = RegistroPonto::query()
            ->select([
                DB::raw('MIN(id) as id'),
                'colaborador_id',
                'data',
                DB::raw("MIN(CASE WHEN tipo = 'entrada' THEN hora END) as entrada_1"),
                DB::raw("MIN(CASE WHEN tipo = 'saida_intervalo' THEN hora END) as saida_1"),
                DB::raw("MIN(CASE WHEN tipo = 'retorno_intervalo' THEN hora END) as entrada_2"),
                DB::raw("MAX(CASE WHEN tipo = 'saida_jornada' THEN hora END) as saida_2"),
            ])
            ->groupBy('colaborador_id', 'data');

        $query = applyRegistroPontoScope($query, $user);

        return $query->with(['colaborador']);
    }
}