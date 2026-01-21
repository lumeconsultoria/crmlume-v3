<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Unidade;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UnidadePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(User $user): bool
    {
        if (! $user->can('ViewAny:Unidade')) {
            return false;
        }

        return userHasGlobalScope($user) || (bool) $user->colaborador?->empresa_id;
    }

    public function view(User $user, Unidade $unidade): bool
    {
        if (! $user->can('View:Unidade')) {
            return false;
        }

        // ABAC: restringe à empresa da unidade.
        return userCanAccessEmpresa($user, $unidade->empresa_id);
    }

    public function create(User $user): bool
    {
        return $user->can('Create:Unidade') && userHasGlobalScope($user);
    }

    public function update(User $user, Unidade $unidade): bool
    {
        return $user->can('Update:Unidade') && $this->view($user, $unidade);
    }

    public function delete(User $user, Unidade $unidade): bool
    {
        return $user->can('Delete:Unidade') && $this->view($user, $unidade);
    }

    public function restore(User $user, Unidade $unidade): bool
    {
        return $user->can('Restore:Unidade');
    }

    public function forceDelete(User $user, Unidade $unidade): bool
    {
        return $user->can('ForceDelete:Unidade');
    }

    public function forceDeleteAny(User $user): bool
    {
        return $user->can('ForceDeleteAny:Unidade');
    }

    public function restoreAny(User $user): bool
    {
        return $user->can('RestoreAny:Unidade');
    }

    public function replicate(User $user, Unidade $unidade): bool
    {
        return $user->can('Replicate:Unidade');
    }

    public function reorder(User $user): bool
    {
        return $user->can('Reorder:Unidade');
    }

}