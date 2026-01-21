<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\User;

class UserObserver
{
    public function created(User $user): void
    {
        $user->loadMissing(['colaborador.empresa']);

        $empresa = $user->colaborador?->empresa?->nome ?? 'Empresa não informada';
        $colaborador = $user->colaborador?->nome ?? 'Colaborador não informado';

        $titulo = 'Novo usuário criado';
        $detalhe = "{$user->name} ({$user->email}) - Colaborador: {$colaborador} / Empresa: {$empresa}";

        notifyLumeAdmins($titulo, $detalhe);
        notifyVendedoresLume($titulo, $detalhe);
    }
}
