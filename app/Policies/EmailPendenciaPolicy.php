<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\EmailPendencia;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class EmailPendenciaPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(User $user): bool
    {
        if (! $user->can('ViewAny:EmailPendencia')) {
            return false;
        }

        return userHasGlobalScope($user) || (bool) $user->colaborador?->empresa_id;
    }

    public function view(User $user, EmailPendencia $emailPendencia): bool
    {
        if (! $user->can('View:EmailPendencia')) {
            return false;
        }

        // ABAC: restringe ao colaborador da pendência.
        return userCanAccessColaborador($user, $emailPendencia->colaborador);
    }

    public function create(User $user): bool
    {
        return $user->can('Create:EmailPendencia') && userHasGlobalScope($user);
    }

    public function update(User $user, EmailPendencia $emailPendencia): bool
    {
        return $user->can('Update:EmailPendencia')
            && userCanAccessColaborador($user, $emailPendencia->colaborador);
    }

    public function delete(User $user, EmailPendencia $emailPendencia): bool
    {
        return $user->can('Delete:EmailPendencia')
            && userCanAccessColaborador($user, $emailPendencia->colaborador);
    }

    public function restore(User $user, EmailPendencia $emailPendencia): bool
    {
        return $user->can('Restore:EmailPendencia');
    }

    public function forceDelete(User $user, EmailPendencia $emailPendencia): bool
    {
        return $user->can('ForceDelete:EmailPendencia');
    }

    public function forceDeleteAny(User $user): bool
    {
        return $user->can('ForceDeleteAny:EmailPendencia');
    }

    public function restoreAny(User $user): bool
    {
        return $user->can('RestoreAny:EmailPendencia');
    }

    public function replicate(User $user, EmailPendencia $emailPendencia): bool
    {
        return $user->can('Replicate:EmailPendencia');
    }

    public function reorder(User $user): bool
    {
        return $user->can('Reorder:EmailPendencia');
    }

}