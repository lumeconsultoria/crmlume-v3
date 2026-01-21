<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\RegistroPonto;
use App\Models\User;

class RegistroPontoPolicy
{
    public function before(User $user, string $ability): ?bool
    {
        if (! moduleEnabled('cartao_de_ponto')) {
            return false;
        }

        return null;
    }

    public function viewAny(User $user): bool
    {
        if (userIsSuperAdmin($user)) {
            return true;
        }

        if (userIsRhLume($user) || userIsRhCliente($user) || userIsGestor($user)) {
            return (bool) $user->colaborador?->empresa_id;
        }

        return false;
    }

    public function view(User $user, RegistroPonto $registro): bool
    {
        return userCanAccessRegistroPonto($user, $registro->colaborador);
    }

    public function create(User $user): bool
    {
        if (userIsSuperAdmin($user) || userIsRh($user) || userIsGestor($user)) {
            return true;
        }

        if (userIsAdminLume($user) || userIsVendedorLume($user) || userIsColaborador($user)) {
            return userAtivoParaPonto($user, $user->colaborador);
        }

        return false;
    }

    public function update(User $user, RegistroPonto $registro): bool
    {
        if (userIsSuperAdmin($user)) {
            return true;
        }

        return userIsRh($user) && userCanAccessRegistroPonto($user, $registro->colaborador);
    }

    public function export(User $user): bool
    {
        return userCanExportRelatorioPonto($user);
    }
}
