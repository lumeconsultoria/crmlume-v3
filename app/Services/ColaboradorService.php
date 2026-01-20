<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Colaborador;
use App\Models\ColaboradorHistorico;
use Illuminate\Support\Facades\DB;

class ColaboradorService
{
    public function atualizarFuncaoUnidade(
        Colaborador $colaborador,
        ?int $novaFuncaoId,
        ?int $novaUnidadeId,
        ?int $alteradoPorUserId = null,
        ?string $motivo = null
    ): Colaborador {
        $funcaoAnterior = $colaborador->funcao_id;
        $unidadeAnterior = $colaborador->unidade_id;

        $mudouFuncao = $novaFuncaoId !== null && $novaFuncaoId !== $funcaoAnterior;
        $mudouUnidade = $novaUnidadeId !== null && $novaUnidadeId !== $unidadeAnterior;

        if (! $mudouFuncao && ! $mudouUnidade) {
            return $colaborador;
        }

        return DB::transaction(function () use (
            $colaborador,
            $novaFuncaoId,
            $novaUnidadeId,
            $alteradoPorUserId,
            $motivo,
            $funcaoAnterior,
            $unidadeAnterior,
            $mudouFuncao,
            $mudouUnidade
        ): Colaborador {
            if ($mudouFuncao) {
                $colaborador->funcao_id = $novaFuncaoId;
            }

            if ($mudouUnidade) {
                $colaborador->unidade_id = $novaUnidadeId;
            }

            $colaborador->save();

            ColaboradorHistorico::query()->create([
                'colaborador_id' => $colaborador->id,
                'funcao_id_anterior' => $mudouFuncao ? $funcaoAnterior : $funcaoAnterior,
                'funcao_id_nova' => $mudouFuncao ? $novaFuncaoId : $funcaoAnterior,
                'unidade_id_anterior' => $mudouUnidade ? $unidadeAnterior : $unidadeAnterior,
                'unidade_id_nova' => $mudouUnidade ? $novaUnidadeId : $unidadeAnterior,
                'alterado_por_user_id' => $alteradoPorUserId,
                'motivo' => $motivo,
                'created_at' => now(),
            ]);

            return $colaborador;
        });
    }
}
