<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\EmailPendencia;
use App\Models\User;

class EmailPendenciaPolicy
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

    public function view(User $user, EmailPendencia $pendencia): bool
    {
        if (userHasGlobalScope($user) || userIsRhLume($user)) {
            return true;
        }

        return userIsRhCliente($user) && userCanAccessColaborador($user, $pendencia->colaborador);
    }

    public function update(User $user, EmailPendencia $pendencia): bool
    {
        if (userHasGlobalScope($user) || userIsRhLume($user)) {
            return true;
        }

        return userIsRhCliente($user) && userCanAccessColaborador($user, $pendencia->colaborador);
    }
}
