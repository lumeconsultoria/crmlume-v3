<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Colaborador;
use Illuminate\Support\Facades\Auth;

class ColaboradorObserver
{
    public function updated(Colaborador $colaborador): void
    {
        $actor = Auth::user();

        if (! $actor || userIsLumeStaff($actor)) {
            return;
        }

        $colaborador->loadMissing(['empresa', 'unidade']);

        $empresa = $colaborador->empresa?->nome ?? 'Empresa não informada';
        $unidade = $colaborador->unidade?->nome ?? 'Unidade não informada';

        notifyLumeAdmins(
            'Alteração em colaborador',
            "{$colaborador->nome} (Empresa: {$empresa} / Unidade: {$unidade})"
        );
    }
}
