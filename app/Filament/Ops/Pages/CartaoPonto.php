<?php

declare(strict_types=1);

namespace App\Filament\Ops\Pages;

use App\Models\Colaborador;
use App\Models\RegistroPonto;
use App\Services\CartaoPontoService;
use App\Services\RelatorioPontoService;
use BackedEnum;
use Carbon\Carbon;
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
        return moduleEnabled('cartao_de_ponto');
    }

    public static function canAccess(): bool
    {
        $user = Auth::user();

        return moduleEnabled('cartao_de_ponto')
            && $user
            && ($user->hasRole('rh') || $user->hasRole('admin') || $user->hasRole('colaborador'));
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
                RegistroPonto::query()
                    ->with(['colaborador', 'criadoPor'])
                    ->when(
                        $user?->hasRole('colaborador') && $user->colaborador_id,
                        fn($query) => $query->where('colaborador_id', $user->colaborador_id)
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
                    ->formatStateUsing(fn(string $state) => $state === 'entrada' ? 'Entrada' : 'Saída')
                    ->color(fn(string $state) => $state === 'entrada' ? 'success' : 'danger'),

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
                Tables\Actions\Action::make('ajustar')
                    ->label('Ajustar')
                    ->icon('heroicon-o-pencil-square')
                    ->modalHeading('Registrar ajuste de ponto')
                    ->modalDescription('O ajuste é permanente e auditável. Informe o motivo com clareza.')
                    ->requiresConfirmation()
                    ->visible(fn() => Auth::user()?->hasRole('rh'))
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

                Tables\Actions\Action::make('espelho')
                    ->label('Espelho')
                    ->icon('heroicon-o-eye')
                    ->url(fn(RegistroPonto $record): string => CartaoPontoDetalhe::getUrl(['record' => $record->id])),
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
                ->visible(fn() => Auth::user()?->hasRole('rh') || Auth::user()?->hasRole('admin'))
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
        $colaboradorId = $user?->colaborador_id;

        return [
            Select::make('colaborador_id')
                ->label('Colaborador')
                ->helperText('Selecione o colaborador que terá a marcação registrada.')
                ->options(
                    $isColaborador && $colaboradorId
                        ? Colaborador::query()->whereKey($colaboradorId)->pluck('nome', 'id')
                        : Colaborador::query()->orderBy('nome')->pluck('nome', 'id')
                )
                ->searchable()
                ->required()
                ->default($colaboradorId)
                ->disabled($isColaborador && $colaboradorId),
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
