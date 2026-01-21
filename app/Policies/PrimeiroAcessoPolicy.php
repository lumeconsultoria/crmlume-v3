<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\PrimeiroAcesso;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PrimeiroAcessoPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(User $user): bool
    {
        if (! $user->can('ViewAny:PrimeiroAcesso')) {
            return false;
        }

        return userHasGlobalScope($user) || (bool) $user->colaborador?->empresa_id;
    }

    public function view(User $user, PrimeiroAcesso $primeiroAcesso): bool
    {
        if (! $user->can('View:PrimeiroAcesso')) {
            return false;
        }

        // ABAC: restringe ao colaborador do registro.
        return userCanAccessColaborador($user, $primeiroAcesso->colaborador);
    }

    public function create(User $user): bool
    {
        return $user->can('Create:PrimeiroAcesso') && userHasGlobalScope($user);
    }

    public function update(User $user, PrimeiroAcesso $primeiroAcesso): bool
    {
        return $user->can('Update:PrimeiroAcesso')
            && userCanAccessColaborador($user, $primeiroAcesso->colaborador);
    }

    public function delete(User $user, PrimeiroAcesso $primeiroAcesso): bool
    {
        return $user->can('Delete:PrimeiroAcesso')
            && userCanAccessColaborador($user, $primeiroAcesso->colaborador);
    }

    public function restore(User $user, PrimeiroAcesso $primeiroAcesso): bool
    {
        return $user->can('Restore:PrimeiroAcesso');
    }

    public function forceDelete(User $user, PrimeiroAcesso $primeiroAcesso): bool
    {
        return $user->can('ForceDelete:PrimeiroAcesso');
    }

    public function forceDeleteAny(User $user): bool
    {
        return $user->can('ForceDeleteAny:PrimeiroAcesso');
    }

    public function restoreAny(User $user): bool
    {
        return $user->can('RestoreAny:PrimeiroAcesso');
    }

    public function replicate(User $user, PrimeiroAcesso $primeiroAcesso): bool
    {
        return $user->can('Replicate:PrimeiroAcesso');
    }

    public function reorder(User $user): bool
    {
        return $user->can('Reorder:PrimeiroAcesso');
    }

}