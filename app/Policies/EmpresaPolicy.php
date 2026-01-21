<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Empresa;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class EmpresaPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(User $user): bool
    {
        if (! $user->can('ViewAny:Empresa')) {
            return false;
        }

        return userHasGlobalScope($user) || (bool) $user->colaborador?->empresa_id;
    }

    public function view(User $user, Empresa $empresa): bool
    {
        if (! $user->can('View:Empresa')) {
            return false;
        }

        // ABAC: restringe à empresa permitida.
        return userCanAccessEmpresa($user, $empresa->id);
    }

    public function create(User $user): bool
    {
        return $user->can('Create:Empresa') && userHasGlobalScope($user);
    }

    public function update(User $user, Empresa $empresa): bool
    {
        return $user->can('Update:Empresa') && $this->view($user, $empresa);
    }

    public function delete(User $user, Empresa $empresa): bool
    {
        return $user->can('Delete:Empresa') && $this->view($user, $empresa);
    }

    public function restore(User $user, Empresa $empresa): bool
    {
        return $user->can('Restore:Empresa');
    }

    public function forceDelete(User $user, Empresa $empresa): bool
    {
        return $user->can('ForceDelete:Empresa');
    }

    public function forceDeleteAny(User $user): bool
    {
        return $user->can('ForceDeleteAny:Empresa');
    }

    public function restoreAny(User $user): bool
    {
        return $user->can('RestoreAny:Empresa');
    }

    public function replicate(User $user, Empresa $empresa): bool
    {
        return $user->can('Replicate:Empresa');
    }

    public function reorder(User $user): bool
    {
        return $user->can('Reorder:Empresa');
    }

}