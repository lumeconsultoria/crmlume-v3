<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Grupo;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class GrupoPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(User $user): bool
    {
        if (! $user->can('ViewAny:Grupo')) {
            return false;
        }

        return userHasGlobalScope($user) || (bool) $user->colaborador?->empresa?->grupo_id;
    }

    public function view(User $user, Grupo $grupo): bool
    {
        if (! $user->can('View:Grupo')) {
            return false;
        }

        if (userHasGlobalScope($user)) {
            return true;
        }

        // ABAC: restringe ao grupo do usuário.
        $grupoId = $user->colaborador?->empresa?->grupo_id;

        return $grupoId !== null && (int) $grupo->id === (int) $grupoId;
    }

    public function create(User $user): bool
    {
        return $user->can('Create:Grupo') && userHasGlobalScope($user);
    }

    public function update(User $user, Grupo $grupo): bool
    {
        return $user->can('Update:Grupo') && $this->view($user, $grupo);
    }

    public function delete(User $user, Grupo $grupo): bool
    {
        return $user->can('Delete:Grupo') && $this->view($user, $grupo);
    }

    public function restore(User $user, Grupo $grupo): bool
    {
        return $user->can('Restore:Grupo');
    }

    public function forceDelete(User $user, Grupo $grupo): bool
    {
        return $user->can('ForceDelete:Grupo');
    }

    public function forceDeleteAny(User $user): bool
    {
        return $user->can('ForceDeleteAny:Grupo');
    }

    public function restoreAny(User $user): bool
    {
        return $user->can('RestoreAny:Grupo');
    }

    public function replicate(User $user, Grupo $grupo): bool
    {
        return $user->can('Replicate:Grupo');
    }

    public function reorder(User $user): bool
    {
        return $user->can('Reorder:Grupo');
    }

}