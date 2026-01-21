<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Funcao;

class FuncaoObserver
{
    public function created(Funcao $funcao): void
    {
        $funcao->loadMissing(['setor.unidade.empresa']);

        $empresa = $funcao->setor?->unidade?->empresa?->nome ?? 'Empresa não informada';
        $setor = $funcao->setor?->nome ?? 'Setor não informado';

        $titulo = 'Nova função criada';
        $detalhe = "{$funcao->nome} (Setor: {$setor} / Empresa: {$empresa})";

        notifyLumeAdmins($titulo, $detalhe);
        notifyVendedoresLume($titulo, $detalhe);
    }
}
