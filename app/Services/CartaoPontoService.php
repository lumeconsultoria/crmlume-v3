<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\AjustePonto;
use App\Models\Colaborador;
use App\Models\RegistroPonto;
use Carbon\CarbonInterface;
use Illuminate\Support\Facades\DB;

class CartaoPontoService
{
    public function registrarPonto(
        Colaborador $colaborador,
        string $tipo,
        ?int $userId,
        ?CarbonInterface $dataHora = null,
        string $origem = 'manual'
    ): RegistroPonto {
        $tipo = mb_strtolower(trim($tipo));

        if (! in_array($tipo, ['entrada', 'saida_intervalo', 'retorno_intervalo', 'saida_jornada'], true)) {
            throw new \InvalidArgumentException('Tipo de marcação inválido.');
        }

        $dataHora = $dataHora ?? now();

        return RegistroPonto::query()->create([
            'colaborador_id' => $colaborador->id,
            'data' => $dataHora->toDateString(),
            'hora' => $dataHora->format('H:i:s'),
            'tipo' => $tipo,
            'origem' => $origem,
            'criado_por_user_id' => $userId,
            'created_at' => $dataHora,
        ]);
    }

    public function ajustarRegistro(RegistroPonto $registro, string $motivo, ?int $userId): AjustePonto
    {
        $motivo = trim($motivo);

        if ($motivo === '') {
            throw new \InvalidArgumentException('Motivo do ajuste é obrigatório.');
        }

        return DB::transaction(function () use ($registro, $motivo, $userId): AjustePonto {
            return AjustePonto::query()->create([
                'registro_ponto_id' => $registro->id,
                'motivo' => $motivo,
                'alterado_por_user_id' => $userId,
                'created_at' => now(),
            ]);
        });
    }
}
