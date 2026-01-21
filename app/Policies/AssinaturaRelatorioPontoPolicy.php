<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\AssinaturaRelatorioPonto;
use App\Models\User;

class AssinaturaRelatorioPontoPolicy
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
        if (! $user->can('ViewAny:AssinaturaRelatorioPonto')) {
            return false;
        }

        return userCanExportRelatorioPonto($user);
    }

    public function view(User $user, AssinaturaRelatorioPonto $assinatura): bool
    {
        if (! $user->can('View:AssinaturaRelatorioPonto')) {
            return false;
        }

        return userCanExportRelatorioPonto($user)
            || (int) $assinatura->usuario_id === (int) $user->id;
    }

    public function create(User $user): bool
    {
        return $user->can('Create:AssinaturaRelatorioPonto') && userCanExportRelatorioPonto($user);
    }

    public function delete(User $user, AssinaturaRelatorioPonto $assinatura): bool
    {
        return $user->can('Delete:AssinaturaRelatorioPonto') && userHasGlobalScope($user);
    }
}