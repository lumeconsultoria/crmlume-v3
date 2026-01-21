<?php

declare(strict_types=1);

namespace App\Filament\Ops\Pages;

use App\Models\RegistroPonto;
use App\Models\Solicitacao;
use App\Services\CartaoPontoService;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class MeuPonto extends Page implements HasTable
{
    use InteractsWithTable;

    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $navigationLabel = 'Meu Ponto';
    protected static string|\UnitEnum|null $navigationGroup = 'Ponto';
    protected static ?int $navigationSort = 1;
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-clock';

    public static function canAccess(): bool
    {
        return false;
    }

    public function getView(): string
    {
        return 'filament.ops.pages.meu-ponto';
    }

    public function mount(): void
    {
        redirect()->to('/ops/registro-pontos');
    }

    public function table(Table $table): Table
    {
        $user = Auth::user();
        $colaboradorId = $user?->colaborador_id ?? 0;

        return $table
            ->query(
                RegistroPonto::query()
                    ->with(['colaborador', 'criadoPor'])
                    ->where('colaborador_id', $colaboradorId)
            )
            ->columns([
                Tables\Columns\TextColumn::make('data')
                    ->label('Data')
                    ->date('d/m/Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('hora')
                    ->label('Hora')
                    ->sortable(),
                Tables\Columns\TextColumn::make('tipo')
                    ->label('Tipo')
                    ->badge()
                    ->formatStateUsing(fn(string $state) => match ($state) {
                        'entrada' => 'Entrada',
                        'saida_intervalo' => 'Saída para intervalo',
                        'retorno_intervalo' => 'Retorno do intervalo',
                        'saida_jornada' => 'Saída da jornada',
                        default => ucfirst($state),
                    })
                    ->color(fn(string $state) => match ($state) {
                        'entrada' => 'success',
                        'retorno_intervalo' => 'success',
                        'saida_intervalo' => 'warning',
                        'saida_jornada' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('origem')
                    ->label('Origem')
                    ->default('manual'),
            ])
            ->actions([
                Action::make('solicitar_ajuste')
                    ->label('Solicitar Ajuste')
                    ->icon('heroicon-o-pencil-square')
                    ->form([
                        Textarea::make('motivo')
                            ->label('Motivo')
                            ->required()
                            ->rows(4)
                            ->placeholder('Explique o motivo do ajuste...'),
                    ])
                    ->action(function (RegistroPonto $record, array $data): void {
                        $user = Auth::user();

                        if (! $user || ! userCanAccessRegistroPonto($user, $record->colaborador)) {
                            abort(403);
                        }

                        Solicitacao::create([
                            'colaborador_id' => $record->colaborador_id,
                            'solicitante_id' => $user->id,
                            'tipo' => 'ajuste_ponto',
                            'descricao' => sprintf(
                                'Registro %s %s (%s) - Motivo: %s',
                                $record->data?->format('d/m/Y'),
                                $record->hora,
                                $record->tipo,
                                $data['motivo']
                            ),
                            'status' => 'pendente',
                        ]);

                        Notification::make()
                            ->title('Solicitação enviada')
                            ->success()
                            ->send();
                    }),
            ])
            ->defaultSort('data', 'desc');
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('registrar_entrada')
                ->label('Registrar Entrada')
                ->icon('heroicon-o-arrow-right-circle')
                ->requiresConfirmation()
                ->action(fn() => $this->registrarPonto('entrada')),
            Action::make('registrar_saida_intervalo')
                ->label('Registrar Saída para Intervalo')
                ->icon('heroicon-o-arrow-right-circle')
                ->requiresConfirmation()
                ->action(fn() => $this->registrarPonto('saida_intervalo')),
            Action::make('registrar_retorno_intervalo')
                ->label('Registrar Retorno do Intervalo')
                ->icon('heroicon-o-arrow-left-circle')
                ->requiresConfirmation()
                ->action(fn() => $this->registrarPonto('retorno_intervalo')),
            Action::make('registrar_saida_jornada')
                ->label('Registrar Saída da Jornada')
                ->icon('heroicon-o-arrow-left-circle')
                ->requiresConfirmation()
                ->action(fn() => $this->registrarPonto('saida_jornada')),
        ];
    }

    private function registrarPonto(string $tipo): void
    {
        $user = Auth::user();
        $colaborador = $user?->colaborador;

        if (! $user || ! $colaborador || ! userAtivoParaPonto($user, $colaborador)) {
            abort(403);
        }

        $dataHora = Carbon::now();

        app(CartaoPontoService::class)
            ->registrarPonto($colaborador, $tipo, $user->id, $dataHora, 'manual');

        Notification::make()
            ->title('Ponto registrado')
            ->success()
            ->send();
    }

    public static function getUrl(array $parameters = [], bool $isAbsolute = true, ?string $panel = null, ?\Illuminate\Database\Eloquent\Model $tenant = null): string
    {
        return parent::getUrl($parameters, $isAbsolute, 'ops', $tenant);
    }
}
