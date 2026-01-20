<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\AssinaturaRelatorioPonto;
use App\Models\RegistroPonto;
use Carbon\CarbonInterface;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class RelatorioPontoService
{
    public function exportarPeriodo(CarbonInterface $inicio, CarbonInterface $fim, int $usuarioId): AssinaturaRelatorioPonto
    {
        $empresaId = $this->resolveEmpresaId($usuarioId);
        $pasta = sprintf(
            'ponto/%s/%s/%s',
            $inicio->format('Y'),
            $inicio->format('m'),
            $empresaId
        );

        $registros = RegistroPonto::query()
            ->with(['colaborador', 'ajustes', 'ajustes.alteradoPor'])
            ->whereDate('data', '>=', $inicio->toDateString())
            ->whereDate('data', '<=', $fim->toDateString())
            ->orderBy('data')
            ->orderBy('hora')
            ->get();

        $csv = $this->buildCsv($registros);
        $hash = hash('sha256', $csv);

        $nomeArquivo = sprintf(
            '%s/relatorio_ponto_%s_%s_%s_%s.csv',
            $pasta,
            $inicio->format('Ymd'),
            $fim->format('Ymd'),
            now()->format('Ymd_His'),
            Str::lower(Str::random(6))
        );

        Storage::disk('local')->makeDirectory($pasta);
        Storage::disk('local')->put($nomeArquivo, $csv);

        return AssinaturaRelatorioPonto::query()->create([
            'hash_documento' => $hash,
            'algoritmo' => 'sha256',
            'usuario_id' => $usuarioId,
            'periodo_inicio' => $inicio->toDateString(),
            'periodo_fim' => $fim->toDateString(),
            'arquivo_path' => $nomeArquivo,
            'created_at' => now(),
        ]);
    }

    private function resolveEmpresaId(int $usuarioId): int
    {
        $user = \App\Models\User::query()->with('colaborador')->find($usuarioId);

        return (int) ($user?->colaborador?->empresa_id ?? 0);
    }

    private function buildCsv($registros): string
    {
        $handle = fopen('php://temp', 'w+');

        fputcsv($handle, [
            'tipo_registro',
            'registro_id',
            'colaborador_id',
            'colaborador_nome',
            'data',
            'hora',
            'tipo',
            'origem',
            'criado_por_user_id',
            'ajuste_id',
            'ajuste_motivo',
            'ajuste_user_id',
            'created_at',
        ]);

        foreach ($registros as $registro) {
            fputcsv($handle, [
                'registro',
                $registro->id,
                $registro->colaborador_id,
                $registro->colaborador?->nome,
                $registro->data?->toDateString(),
                $registro->hora,
                $registro->tipo,
                $registro->origem,
                $registro->criado_por_user_id,
                null,
                null,
                null,
                $registro->created_at?->toDateTimeString(),
            ]);

            foreach ($registro->ajustes as $ajuste) {
                fputcsv($handle, [
                    'ajuste',
                    $registro->id,
                    $registro->colaborador_id,
                    $registro->colaborador?->nome,
                    $registro->data?->toDateString(),
                    $registro->hora,
                    $registro->tipo,
                    $registro->origem,
                    $registro->criado_por_user_id,
                    $ajuste->id,
                    $ajuste->motivo,
                    $ajuste->alterado_por_user_id,
                    $ajuste->created_at?->toDateTimeString(),
                ]);
            }
        }

        rewind($handle);
        $csv = stream_get_contents($handle) ?: '';
        fclose($handle);

        return $csv;
    }
}
