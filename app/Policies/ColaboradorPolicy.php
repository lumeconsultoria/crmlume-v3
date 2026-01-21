<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Colaborador;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ColaboradorPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(User $user): bool
    {
        if (! $user->can('ViewAny:Colaborador')) {
            return false;
        }

        return userHasGlobalScope($user)
            || userIsRh($user)
            || userIsGestor($user)
            || userIsColaborador($user);
    }

    public function view(User $user, Colaborador $colaborador): bool
    {
        if (! $user->can('View:Colaborador')) {
            return false;
        }

        // ABAC: restringe ao colaborador/escopo do usuário.
        return userCanAccessColaborador($user, $colaborador);
    }

    public function create(User $user): bool
    {
        return $user->can('Create:Colaborador') && userHasGlobalScope($user);
    }

    public function update(User $user, Colaborador $colaborador): bool
    {
        return $user->can('Update:Colaborador') && userCanAccessColaborador($user, $colaborador);
    }

    public function delete(User $user, Colaborador $colaborador): bool
    {
        return $user->can('Delete:Colaborador') && userCanAccessColaborador($user, $colaborador);
    }

    public function restore(User $user, Colaborador $colaborador): bool
    {
        return $user->can('Restore:Colaborador');
    }

    public function forceDelete(User $user, Colaborador $colaborador): bool
    {
        return $user->can('ForceDelete:Colaborador');
    }

    public function forceDeleteAny(User $user): bool
    {
        return $user->can('ForceDeleteAny:Colaborador');
    }

    public function restoreAny(User $user): bool
    {
        return $user->can('RestoreAny:Colaborador');
    }

    public function replicate(User $user, Colaborador $colaborador): bool
    {
        return $user->can('Replicate:Colaborador');
    }

    public function reorder(User $user): bool
    {
        return $user->can('Reorder:Colaborador');
    }

}