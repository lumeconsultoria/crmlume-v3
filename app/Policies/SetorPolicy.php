<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Setor;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SetorPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(User $user): bool
    {
        if (! $user->can('ViewAny:Setor')) {
            return false;
        }

        return userHasGlobalScope($user) || (bool) $user->colaborador?->empresa_id;
    }

    public function view(User $user, Setor $setor): bool
    {
        if (! $user->can('View:Setor')) {
            return false;
        }

        // ABAC: restringe à empresa do setor.
        return userCanAccessEmpresa($user, $setor->unidade?->empresa_id);
    }

    public function create(User $user): bool
    {
        return $user->can('Create:Setor') && userHasGlobalScope($user);
    }

    public function update(User $user, Setor $setor): bool
    {
        return $user->can('Update:Setor') && $this->view($user, $setor);
    }

    public function delete(User $user, Setor $setor): bool
    {
        return $user->can('Delete:Setor') && $this->view($user, $setor);
    }

    public function restore(User $user, Setor $setor): bool
    {
        return $user->can('Restore:Setor');
    }

    public function forceDelete(User $user, Setor $setor): bool
    {
        return $user->can('ForceDelete:Setor');
    }

    public function forceDeleteAny(User $user): bool
    {
        return $user->can('ForceDeleteAny:Setor');
    }

    public function restoreAny(User $user): bool
    {
        return $user->can('RestoreAny:Setor');
    }

    public function replicate(User $user, Setor $setor): bool
    {
        return $user->can('Replicate:Setor');
    }

    public function reorder(User $user): bool
    {
        return $user->can('Reorder:Setor');
    }

}