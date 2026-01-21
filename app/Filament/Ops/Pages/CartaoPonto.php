<?php

declare(strict_types=1);

namespace App\Filament\Ops\Pages;

use App\Models\Colaborador;
use App\Models\RegistroPonto;
use App\Services\CartaoPontoService;
use App\Services\RelatorioPontoService;
use BackedEnum;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Actions\Action as HeaderAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TimePicker;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Auth;

class CartaoPonto extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClock;

    protected static ?string $navigationLabel = 'Cartão de Ponto';

    protected static \UnitEnum|string|null $navigationGroup = 'RH';

    protected static ?int $navigationSort = 20;

    public static function shouldRegisterNavigation(): bool
    {
        $user = Auth::user();

        return $user?->can('viewAny', RegistroPonto::class) ?? false;
    }

    public static function canAccess(): bool
    {
        $user = Auth::user();

        if (! $user) {
            return false;
        }

        if (userIsColaborador($user) || userIsAdminLume($user) || userIsVendedorLume($user)) {
            return true;
        }

        return $user->can('viewAny', RegistroPonto::class);
    }

    public function mount(): void
    {
        $user = Auth::user();

        if (! $user) {
            return;
        }

        if (userIsColaborador($user) || userIsAdminLume($user) || userIsVendedorLume($user)) {
            redirect()->to('/ops/registro-pontos');
            return;
        }

        if (! $user->can('viewAny', RegistroPonto::class)) {
            redirect()->to('/ops/registro-pontos');
        }
    }

    public function getView(): string
    {
        return 'filament.ops.pages.cartao-ponto';
    }

    public function table(Table $table): Table
    {
        $user = Auth::user();

        return $table
            ->query(
                applyRegistroPontoScope(
                    RegistroPonto::query()->with(['colaborador', 'criadoPor']),
                    $user
                )
            )
            ->columns([
                Tables\Columns\TextColumn::make('colaborador.nome')
                    ->label('Colaborador')
                    ->searchable()
                    ->sortable(),

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
                    ->sortable(),

                Tables\Columns\TextColumn::make('criadoPor.name')
                    ->label('Registrado por')
                    ->default('Sistema'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('tipo')
                    ->label('Tipo')
                    ->options([
                        'entrada' => 'Entrada',
                        'saida' => 'Saída',
                    ]),
            ])
            ->actions([
                Action::make('ajustar')
                    ->label('Ajustar')
                    ->icon('heroicon-o-pencil-square')
                    ->modalHeading('Registrar ajuste de ponto')
                    ->modalDescription('O ajuste é permanente e auditável. Informe o motivo com clareza.')
                    ->requiresConfirmation()
                    ->visible(fn(RegistroPonto $record) => Auth::user()?->can('update', $record))
                    ->form([
                        Textarea::make('motivo')
                            ->label('Motivo do ajuste')
                            ->helperText('Descreva a razão do ajuste. Esse texto ficará registrado no histórico.')
                            ->required()
                            ->rows(4),
                    ])
                    ->action(function (RegistroPonto $record, array $data) {
                        app(CartaoPontoService::class)
                            ->ajustarRegistro($record, $data['motivo'], Auth::id());

                        Notification::make()
                            ->title('Ajuste registrado')
                            ->success()
                            ->send();
                    }),
            ])
            ->defaultSort('data', 'desc');
    }

    protected function getHeaderActions(): array
    {
        return [
            HeaderAction::make('registrar_entrada')
                ->label('Registrar Entrada')
                ->icon('heroicon-o-arrow-right-circle')
                ->modalHeading('Registrar entrada')
                ->modalDescription('Confirme os dados antes de registrar. O registro é imutável.')
                ->requiresConfirmation()
                ->visible(fn() => Auth::user()?->can('create', RegistroPonto::class))
                ->form($this->getRegistroForm())
                ->action(function (array $data) {
                    $this->registrarPonto('entrada', $data);
                }),

            HeaderAction::make('registrar_saida')
                ->label('Registrar Saída')
                ->icon('heroicon-o-arrow-left-circle')
                ->modalHeading('Registrar saída')
                ->modalDescription('Confirme os dados antes de registrar. O registro é imutável.')
                ->requiresConfirmation()
                ->visible(fn() => Auth::user()?->can('create', RegistroPonto::class))
                ->form($this->getRegistroForm())
                ->action(function (array $data) {
                    $this->registrarPonto('saida', $data);
                }),

            HeaderAction::make('exportar_periodo')
                ->label('Exportar Período')
                ->icon('heroicon-o-arrow-down-tray')
                ->modalHeading('Exportar relatório de ponto')
                ->modalDescription('A exportação gera assinatura eletrônica interna (hash + usuário + timestamp).')
                ->requiresConfirmation()
                ->visible(fn() => Auth::user()?->can('export', RegistroPonto::class))
                ->form([
                    DatePicker::make('inicio')
                        ->label('Início')
                        ->required(),
                    DatePicker::make('fim')
                        ->label('Fim')
                        ->required(),
                ])
                ->action(function (array $data) {
                    $inicio = $data['inicio'] instanceof \DateTimeInterface
                        ? Carbon::instance($data['inicio'])
                        : Carbon::parse($data['inicio']);
                    $fim = $data['fim'] instanceof \DateTimeInterface
                        ? Carbon::instance($data['fim'])
                        : Carbon::parse($data['fim']);

                    $assinatura = app(RelatorioPontoService::class)
                        ->exportarPeriodo($inicio, $fim, Auth::id());

                    Notification::make()
                        ->title('Relatório exportado')
                        ->body('Hash: ' . $assinatura->hash_documento)
                        ->success()
                        ->send();
                }),
        ];
    }

    private function registrarPonto(string $tipo, array $data): void
    {
        $colaborador = Colaborador::query()->findOrFail($data['colaborador_id']);
        $dataHora = Carbon::parse($data['data'] . ' ' . $data['hora']);
        $user = Auth::user();

        if (! $user || ! $user->can('create', RegistroPonto::class)) {
            abort(403);
        }

        if (! userCanAccessRegistroPonto($user, $colaborador)) {
            abort(403);
        }

        app(CartaoPontoService::class)
            ->registrarPonto($colaborador, $tipo, Auth::id(), $dataHora, 'manual');

        Notification::make()
            ->title('Ponto registrado')
            ->success()
            ->send();
    }

    private function getRegistroForm(): array
    {
        $user = Auth::user();
        $isColaborador = $user?->hasRole('colaborador') ?? false;
        $isSelfOnly = $user && (userIsColaborador($user) || userIsAdminLume($user) || userIsVendedorLume($user));
        $colaboradorId = $user?->colaborador_id;
        $query = Colaborador::query()->orderBy('nome');

        if ($user) {
            $query = applyColaboradorScope($query, $user);
        } else {
            $query->whereRaw('1 = 0');
        }

        return [
            Select::make('colaborador_id')
                ->label('Colaborador')
                ->helperText('Selecione o colaborador que terá a marcação registrada.')
                ->options(
                    $isSelfOnly && $colaboradorId
                        ? Colaborador::query()->whereKey($colaboradorId)->pluck('nome', 'id')
                        : $query->pluck('nome', 'id')
                )
                ->searchable()
                ->required()
                ->default($colaboradorId)
                ->disabled($isSelfOnly && $colaboradorId),
            DatePicker::make('data')
                ->label('Data')
                ->helperText('Data da marcação a ser registrada.')
                ->default(now())
                ->required(),
            TimePicker::make('hora')
                ->label('Hora')
                ->helperText('Hora exata da marcação. Será registrada como evidência.')
                ->default(now()->format('H:i'))
                ->seconds(false)
                ->required(),
        ];
    }

    public static function getUrl(array $parameters = [], bool $isAbsolute = true, ?string $panel = null, ?\Illuminate\Database\Eloquent\Model $tenant = null): string
    {
        return parent::getUrl($parameters, $isAbsolute, 'ops', $tenant);
    }
}
