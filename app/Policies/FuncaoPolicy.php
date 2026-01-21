<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Funcao;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class FuncaoPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(User $user): bool
    {
        if (! $user->can('ViewAny:Funcao')) {
            return false;
        }

        return userHasGlobalScope($user) || (bool) $user->colaborador?->empresa_id;
    }

    public function view(User $user, Funcao $funcao): bool
    {
        if (! $user->can('View:Funcao')) {
            return false;
        }

        // ABAC: restringe à empresa da função.
        return userCanAccessEmpresa($user, $funcao->setor?->unidade?->empresa_id);
    }

    public function create(User $user): bool
    {
        return $user->can('Create:Funcao') && userHasGlobalScope($user);
    }

    public function update(User $user, Funcao $funcao): bool
    {
        return $user->can('Update:Funcao') && $this->view($user, $funcao);
    }

    public function delete(User $user, Funcao $funcao): bool
    {
        return $user->can('Delete:Funcao') && $this->view($user, $funcao);
    }

    public function restore(User $user, Funcao $funcao): bool
    {
        return $user->can('Restore:Funcao');
    }

    public function forceDelete(User $user, Funcao $funcao): bool
    {
        return $user->can('ForceDelete:Funcao');
    }

    public function forceDeleteAny(User $user): bool
    {
        return $user->can('ForceDeleteAny:Funcao');
    }

    public function restoreAny(User $user): bool
    {
        return $user->can('RestoreAny:Funcao');
    }

    public function replicate(User $user, Funcao $funcao): bool
    {
        return $user->can('Replicate:Funcao');
    }

    public function reorder(User $user): bool
    {
        return $user->can('Reorder:Funcao');
    }

}