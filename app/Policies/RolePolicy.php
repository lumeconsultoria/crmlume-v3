<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Auth\Access\HandlesAuthorization;

class RolePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(User $user): bool
    {
        return $user->can('ViewAny:Role') && userIsSuperAdmin($user);
    }

    public function view(User $user, Role $role): bool
    {
        return $user->can('View:Role') && userIsSuperAdmin($user);
    }

    public function create(User $user): bool
    {
        return $user->can('Create:Role') && userIsSuperAdmin($user);
    }

    public function update(User $user, Role $role): bool
    {
        return $user->can('Update:Role') && userIsSuperAdmin($user);
    }

    public function delete(User $user, Role $role): bool
    {
        return $user->can('Delete:Role') && userIsSuperAdmin($user);
    }

    public function restore(User $user, Role $role): bool
    {
        return $user->can('Restore:Role') && userIsSuperAdmin($user);
    }

    public function forceDelete(User $user, Role $role): bool
    {
        return $user->can('ForceDelete:Role') && userIsSuperAdmin($user);
    }

    public function forceDeleteAny(User $user): bool
    {
        return $user->can('ForceDeleteAny:Role') && userIsSuperAdmin($user);
    }

    public function restoreAny(User $user): bool
    {
        return $user->can('RestoreAny:Role') && userIsSuperAdmin($user);
    }

    public function replicate(User $user, Role $role): bool
    {
        return $user->can('Replicate:Role') && userIsSuperAdmin($user);
    }

    public function reorder(User $user): bool
    {
        return $user->can('Reorder:Role') && userIsSuperAdmin($user);
    }

}