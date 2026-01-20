<?php

declare(strict_types=1);

namespace App\Filament\Ops\Pages;

use App\Models\RegistroPonto;
use Filament\Actions\Action;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Concerns\InteractsWithInfolists;
use Filament\Infolists\Contracts\HasInfolists;
use Filament\Infolists\Infolist;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

class CartaoPontoDetalhe extends Page implements HasInfolists
{
    use InteractsWithInfolists;

    protected static bool $shouldRegisterNavigation = false;

    public ?RegistroPonto $registro = null;

    public static function canAccess(): bool
    {
        $user = Auth::user();

        return moduleEnabled('cartao_de_ponto')
            && $user
            && ($user->hasRole('rh') || $user->hasRole('admin') || $user->hasRole('colaborador'));
    }

    public function getView(): string
    {
        return 'filament.ops.pages.cartao-ponto-detalhe';
    }

    public function mount(int $record): void
    {
        $this->registro = RegistroPonto::with(['colaborador', 'criadoPor', 'ajustes.alteradoPor'])
            ->findOrFail($record);

        $user = Auth::user();
        if ($user?->hasRole('colaborador') && $user->colaborador_id !== $this->registro->colaborador_id) {
            abort(403);
        }
    }

    public function getTitle(): string
    {
        return 'Espelho de Ponto';
    }

    public function registroInfolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->record($this->registro)
            ->schema([
                Section::make('Registro')
                    ->schema([
                        TextEntry::make('colaborador.nome')
                            ->label('Colaborador'),
                        TextEntry::make('data')
                            ->label('Data')
                            ->date('d/m/Y'),
                        TextEntry::make('hora')
                            ->label('Hora'),
                        TextEntry::make('tipo')
                            ->label('Tipo')
                            ->formatStateUsing(fn(string $state) => $state === 'entrada' ? 'Entrada' : 'Saída'),
                        TextEntry::make('origem')
                            ->label('Origem'),
                        TextEntry::make('criadoPor.name')
                            ->label('Registrado por')
                            ->default('Sistema'),
                        TextEntry::make('created_at')
                            ->label('Registrado em')
                            ->dateTime('d/m/Y H:i'),
                    ])
                    ->columns(2),

                Section::make('Ajustes')
                    ->schema([
                        TextEntry::make('ajustes_resumo')
                            ->label('Histórico de Ajustes')
                            ->state(function (RegistroPonto $record): string {
                                if ($record->ajustes->isEmpty()) {
                                    return 'Sem ajustes';
                                }

                                return $record->ajustes
                                    ->map(function ($ajuste) {
                                        $usuario = $ajuste->alteradoPor?->name ?? 'Sistema';

                                        return sprintf(
                                            '[%s] %s — %s',
                                            $ajuste->created_at?->format('d/m/Y H:i'),
                                            $usuario,
                                            $ajuste->motivo
                                        );
                                    })
                                    ->implode("\n");
                            })
                            ->markdown(),
                    ]),
            ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('voltar')
                ->label('Voltar')
                ->icon('heroicon-o-arrow-left')
                ->url(CartaoPonto::getUrl()),
        ];
    }

    public static function getUrl(array $parameters = [], bool $isAbsolute = true, ?string $panel = null, ?\Illuminate\Database\Eloquent\Model $tenant = null): string
    {
        return parent::getUrl($parameters, $isAbsolute, 'ops', $tenant);
    }
}
