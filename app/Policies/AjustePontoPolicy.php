<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\AjustePonto;
use App\Models\User;

class AjustePontoPolicy
{
    public function before(User $user, string $ability): ?bool
    {
        if (! moduleEnabled('cartao_de_ponto')) {
            return false;
        }

        if (userIsSuperAdmin($user)) {
            return true;
        }

        return null;
    }

    public function viewAny(User $user): bool
    {
        if (! $user->can('ViewAny:AjustePonto')) {
            return false;
        }

        return userIsRh($user) || userIsGestor($user);
    }

    public function view(User $user, AjustePonto $ajuste): bool
    {
        if (! $user->can('View:AjustePonto')) {
            return false;
        }

        return userCanAccessRegistroPonto($user, $ajuste->registroPonto?->colaborador);
    }

    public function create(User $user): bool
    {
        return $user->can('Create:AjustePonto') && (userIsRh($user) || userIsGestor($user));
    }

    public function update(User $user, AjustePonto $ajuste): bool
    {
        if (! $user->can('Update:AjustePonto')) {
            return false;
        }

        return $ajuste->registroPonto
            ? userCanAjustarPonto($user, $ajuste->registroPonto)
            : false;
    }

    public function delete(User $user, AjustePonto $ajuste): bool
    {
        if (! $user->can('Delete:AjustePonto')) {
            return false;
        }

        return $ajuste->registroPonto
            ? userCanAjustarPonto($user, $ajuste->registroPonto)
            : false;
    }
}