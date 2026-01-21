<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\PrimeiroAcesso;
use App\Models\User;

class PrimeiroAcessoPolicy
{
    public function before(User $user, string $ability): ?bool
    {
        if (! moduleEnabled('auth_usuarios')) {
            return false;
        }

        return null;
    }

    public function viewAny(User $user): bool
    {
        if (userHasGlobalScope($user) || userIsRhLume($user)) {
            return true;
        }

        return userIsRhCliente($user) && (bool) $user->colaborador?->empresa_id;
    }

    public function view(User $user, PrimeiroAcesso $registro): bool
    {
        if (userHasGlobalScope($user) || userIsRhLume($user)) {
            return true;
        }

        return userIsRhCliente($user) && userCanAccessColaborador($user, $registro->colaborador);
    }
}
