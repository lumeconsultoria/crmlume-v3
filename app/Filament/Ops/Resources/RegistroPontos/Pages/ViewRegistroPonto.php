<?php

declare(strict_types=1);

namespace App\Filament\Ops\Resources\RegistroPontos\Pages;

use App\Filament\Ops\Resources\RegistroPontos\RegistroPontoResource;
use App\Models\RegistroPonto;
use App\Models\Solicitacao;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Illuminate\Support\Facades\Gate;
use Spatie\Activitylog\Models\Activity;

class ViewRegistroPonto extends ViewRecord
{
    protected static string $resource = RegistroPontoResource::class;

    public function getTitle(): string
    {
        return 'Espelho de Ponto';
    }

    protected function mutateRecordDataBeforeFill(array $data): array
    {
        if (! Gate::allows('view', $this->record)) {
            abort(403);
        }

        return $data;
    }

    public function infolist(Schema $schema): Schema
    {
        $batidas = $this->buildTimeline();

        return $schema
            ->record($this->record)
            ->components([
                Section::make('Resumo do Dia')
                    ->schema([
                        TextEntry::make('data')
                            ->label('Data')
                            ->date('d/m/Y'),
                        TextEntry::make('colaborador.nome')
                            ->label('Colaborador'),
                        TextEntry::make('total_horas')
                            ->label('Total de horas')
                            ->state(fn() => RegistroPontoResource::formatTotalHoras($this->record)),
                        TextEntry::make('status_dia')
                            ->label('Status')
                            ->badge()
                            ->state(fn() => RegistroPontoResource::resolveStatus($this->record))
                            ->color(fn(string $state) => match ($state) {
                                'pendente' => 'warning',
                                'aprovado' => 'success',
                                'rejeitado' => 'danger',
                                default => 'gray',
                            }),
                    ])
                    ->columns(2),
                Section::make('Timeline')
                    ->schema([
                        RepeatableEntry::make('batidas')
                            ->label('Batidas')
                            ->state($batidas)
                            ->schema([
                                TextEntry::make('tipo')
                                    ->label('Batida')
                                    ->badge()
                                    ->color(fn(string $state) => match ($state) {
                                        'Entrada' => 'success',
                                        'Saída para intervalo' => 'warning',
                                        'Retorno do intervalo' => 'success',
                                        'Saída da jornada' => 'danger',
                                        default => 'gray',
                                    }),
                                TextEntry::make('hora')
                                    ->label('Horário'),
                                TextEntry::make('status')
                                    ->label('Status')
                                    ->badge()
                                    ->color(fn(string $state) => match ($state) {
                                        'Pendente' => 'warning',
                                        'Aprovado' => 'success',
                                        'Rejeitado' => 'danger',
                                        default => 'gray',
                                    }),
                                TextEntry::make('auditoria')
                                    ->label('Auditoria'),
                            ])
                            ->columns(4),
                    ]),
                        ]);
    }

    /**
     * @return array<int, array{tipo: string, hora: string, status: string, auditoria: string}>
     */
    private function buildTimeline(): array
    {
        /** @var RegistroPonto $record */
        $record = $this->record;

        $registros = RegistroPonto::query()
            ->where('colaborador_id', $record->colaborador_id)
            ->whereDate('data', $record->data)
            ->orderBy('hora')
            ->get();

        $solicitacao = $this->resolveSolicitacao($record);
        $status = match ($solicitacao?->status) {
            'pendente' => 'Pendente',
            'aprovado' => 'Aprovado',
            'recusado' => 'Rejeitado',
            default => 'Aprovado',
        };

        $auditoria = $this->resolveAuditoria($solicitacao);

        return $registros
            ->map(function (RegistroPonto $registro) use ($status, $auditoria): array {
                return [
                    'tipo' => match ($registro->tipo) {
                        'entrada' => 'Entrada',
                        'saida_intervalo' => 'Saída para intervalo',
                        'retorno_intervalo' => 'Retorno do intervalo',
                        'saida_jornada' => 'Saída da jornada',
                        default => ucfirst($registro->tipo),
                    },
                    'hora' => $registro->hora,
                    'status' => $status,
                    'auditoria' => $auditoria,
                ];
            })
            ->all();
    }

    private function resolveSolicitacao(RegistroPonto $record): ?Solicitacao
    {
        $data = $record->data?->format('d/m/Y') ?? (string) $record->data;

        return Solicitacao::query()
            ->where('colaborador_id', $record->colaborador_id)
            ->where('tipo', 'ajuste_ponto')
            ->where('descricao', 'like', '%' . $data . '%')
            ->whereIn('status', ['pendente', 'aprovado', 'recusado'])
            ->latest('id')
            ->first();
    }

    private function resolveAuditoria(?Solicitacao $solicitacao): string
    {
        if (! $solicitacao) {
            return 'Sem auditoria';
        }

        $activity = Activity::query()
            ->where('subject_type', Solicitacao::class)
            ->where('subject_id', $solicitacao->id)
            ->latest('id')
            ->first();

        if (! $activity) {
            return sprintf('Status %s (sem auditoria)', $solicitacao->status);
        }

        $user = $activity->causer?->name ?? 'Sistema';
        $when = $activity->created_at?->format('d/m/Y H:i') ?? '-';

        return sprintf('%s em %s', $user, $when);
    }
}