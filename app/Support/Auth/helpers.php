<?php

use App\Models\Colaborador;
use App\Models\Empresa;
use App\Models\RegistroPonto;
use App\Models\User;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;

if (! function_exists('userAtivoParaPonto')) {
    function userAtivoParaPonto(?User $user, ?Colaborador $colaborador): bool
    {
        if (! $user || ! $colaborador) {
            return false;
        }

        if ($user->email_verified_at === null) {
            return false;
        }

        if (! $user->ativo || ! $colaborador->ativo) {
            return false;
        }

        if ($user->colaborador_id === $colaborador->id) {
            return true;
        }

        return $user->colaboradores()
            ->whereKey($colaborador->id)
            ->exists();
    }
}

if (! function_exists('userHasAnyRole')) {
    /**
     * @param list<string> $roles
     */
    function userHasAnyRole(?User $user, array $roles): bool
    {
        if (! $user) {
            return false;
        }

        foreach ($roles as $role) {
            if ($user->hasRole($role)) {
                return true;
            }
        }

        return false;
    }
}

if (! function_exists('userIsSuperAdmin')) {
    function userIsSuperAdmin(?User $user): bool
    {
        return userHasAnyRole($user, ['super_admin']);
    }
}

if (! function_exists('userIsAdminLume')) {
    function userIsAdminLume(?User $user): bool
    {
        return userHasAnyRole($user, ['admin_lume', 'administrativo_lume', 'adm_lume', 'admin', 'ceo']);
    }
}

if (! function_exists('userIsVendedorLume')) {
    function userIsVendedorLume(?User $user): bool
    {
        return userHasAnyRole($user, ['vendedor_lume']);
    }
}

if (! function_exists('userIsRh')) {
    function userIsRh(?User $user): bool
    {
        return userHasAnyRole($user, ['rh']);
    }
}

if (! function_exists('empresaIsLume')) {
    function empresaIsLume(?Empresa $empresa): bool
    {
        $nome = $empresa?->nome ?? '';

        return $nome !== '' && mb_stripos($nome, 'lume') !== false;
    }
}

if (! function_exists('userIsRhLume')) {
    function userIsRhLume(?User $user): bool
    {
        return userIsRh($user) && empresaIsLume($user?->colaborador?->empresa);
    }
}

if (! function_exists('userIsRhCliente')) {
    function userIsRhCliente(?User $user): bool
    {
        return userIsRh($user) && ! userIsRhLume($user);
    }
}

if (! function_exists('userIsGestor')) {
    function userIsGestor(?User $user): bool
    {
        return userHasAnyRole($user, ['gestor']);
    }
}

if (! function_exists('userIsColaborador')) {
    function userIsColaborador(?User $user): bool
    {
        return userHasAnyRole($user, ['colaborador']);
    }
}

if (! function_exists('userHasGlobalScope')) {
    function userHasGlobalScope(?User $user): bool
    {
        return userIsSuperAdmin($user) || userIsAdminLume($user);
    }
}

if (! function_exists('userEmpresaScopeIds')) {
    /**
     * @return list<int>
     */
    function userEmpresaScopeIds(User $user): array
    {
        if (userHasGlobalScope($user)) {
            return [];
        }

        if (userIsVendedorLume($user) && method_exists($user, 'empresasAtendidas')) {
            /** @phpstan-ignore-next-line */
            return $user->empresasAtendidas()->pluck('id')->all();
        }

        $empresaId = $user->colaborador?->empresa_id;

        return $empresaId ? [$empresaId] : [];
    }
}

if (! function_exists('userCanAccessEmpresa')) {
    function userCanAccessEmpresa(User $user, ?int $empresaId): bool
    {
        if (userHasGlobalScope($user)) {
            return true;
        }

        if (! $empresaId) {
            return false;
        }

        return in_array($empresaId, userEmpresaScopeIds($user), true);
    }
}

if (! function_exists('userGestorScope')) {
    /**
     * @return array{empresa_id?: int|null, unidade_id?: int|null, setor_id?: int|null}
     */
    function userGestorScope(User $user): array
    {
        $colaborador = $user->colaborador;

        return [
            'empresa_id' => $colaborador?->empresa_id,
            'unidade_id' => $colaborador?->unidade_id,
            'setor_id' => $colaborador?->funcao?->setor_id,
        ];
    }
}

if (! function_exists('userCanAccessColaborador')) {
    function userCanAccessColaborador(User $user, ?Colaborador $colaborador): bool
    {
        if (! $colaborador) {
            return false;
        }

        if (userHasGlobalScope($user)) {
            return true;
        }

        if (userIsRh($user)) {
            return userCanAccessEmpresa($user, $colaborador->empresa_id);
        }

        if (userIsGestor($user)) {
            $scope = userGestorScope($user);

            if (! $scope['empresa_id'] || $scope['empresa_id'] !== $colaborador->empresa_id) {
                return false;
            }

            if ($scope['unidade_id'] && $scope['unidade_id'] !== $colaborador->unidade_id) {
                return false;
            }

            if ($scope['setor_id'] && $scope['setor_id'] !== $colaborador->funcao?->setor_id) {
                return false;
            }

            return true;
        }

        return userIsColaborador($user)
            && userAtivoParaPonto($user, $colaborador)
            && $user->colaborador_id === $colaborador->id;
    }
}

if (! function_exists('applyColaboradorScope')) {
    function applyColaboradorScope(Builder $query, ?User $user): Builder
    {
        if (! $user) {
            return $query->whereRaw('1 = 0');
        }

        if (userHasGlobalScope($user)) {
            return $query;
        }

        if (userIsRhLume($user)) {
            return $query;
        }

        if (userIsRhCliente($user)) {
            $empresaId = $user->colaborador?->empresa_id;

            return $empresaId
                ? $query->where('empresa_id', $empresaId)
                : $query->whereRaw('1 = 0');
        }

        if (userIsVendedorLume($user)) {
            $empresaIds = userEmpresaScopeIds($user);

            return $empresaIds
                ? $query->whereIn('empresa_id', $empresaIds)
                : $query->whereRaw('1 = 0');
        }

        if (userIsGestor($user)) {
            $scope = userGestorScope($user);

            if (! $scope['empresa_id']) {
                return $query->whereRaw('1 = 0');
            }

            $query->where('empresa_id', $scope['empresa_id']);

            if ($scope['unidade_id']) {
                $query->where('unidade_id', $scope['unidade_id']);
            }

            if ($scope['setor_id']) {
                $query->whereHas('funcao', fn(Builder $builder) => $builder->where('setor_id', $scope['setor_id']));
            }

            return $query;
        }

        if (userIsColaborador($user)) {
            $colaboradorId = $user->colaborador_id ?? 0;

            return $query->whereKey($colaboradorId);
        }

        return $query->whereRaw('1 = 0');
    }
}

if (! function_exists('applyRegistroPontoScope')) {
    function applyRegistroPontoScope(Builder $query, ?User $user): Builder
    {
        if (! $user) {
            return $query->whereRaw('1 = 0');
        }

        if (userIsSuperAdmin($user)) {
            return $query;
        }

        if (userIsRhLume($user)) {
            return $query->whereHas('colaborador.empresa', function (Builder $builder) {
                $builder->where('nome', 'like', '%Lume%');
            });
        }

        if (userIsRhCliente($user)) {
            $empresaId = $user->colaborador?->empresa_id;

            return $empresaId
                ? $query->whereHas('colaborador', fn(Builder $builder) => $builder->where('empresa_id', $empresaId))
                : $query->whereRaw('1 = 0');
        }

        if (userIsGestor($user)) {
            $scope = userGestorScope($user);

            if (! $scope['empresa_id']) {
                return $query->whereRaw('1 = 0');
            }

            return $query->whereHas('colaborador', function (Builder $builder) use ($scope) {
                $builder->where('empresa_id', $scope['empresa_id']);

                if ($scope['unidade_id']) {
                    $builder->where('unidade_id', $scope['unidade_id']);
                }

                if ($scope['setor_id']) {
                    $builder->whereHas('funcao', fn(Builder $funcaoBuilder) => $funcaoBuilder->where('setor_id', $scope['setor_id']));
                }
            });
        }

        if (userIsColaborador($user)) {
            return $query->where('colaborador_id', $user->colaborador_id);
        }

        if (userIsAdminLume($user) || userIsVendedorLume($user)) {
            return $query->where('colaborador_id', $user->colaborador_id);
        }

        return $query->whereRaw('1 = 0');
    }
}

if (! function_exists('applyColaboradorRelationScope')) {
    function applyColaboradorRelationScope(Builder $query, ?User $user, string $relation = 'colaborador'): Builder
    {
        if (! $user) {
            return $query->whereRaw('1 = 0');
        }

        if (userHasGlobalScope($user)) {
            return $query;
        }

        if (userIsRhLume($user)) {
            return $query;
        }

        if (userIsRhCliente($user)) {
            $empresaId = $user->colaborador?->empresa_id;

            return $empresaId
                ? $query->whereHas($relation, fn(Builder $builder) => $builder->where('empresa_id', $empresaId))
                : $query->whereRaw('1 = 0');
        }

        if (userIsVendedorLume($user)) {
            $empresaIds = userEmpresaScopeIds($user);

            return $empresaIds
                ? $query->whereHas($relation, fn(Builder $builder) => $builder->whereIn('empresa_id', $empresaIds))
                : $query->whereRaw('1 = 0');
        }

        if (userIsGestor($user)) {
            $scope = userGestorScope($user);

            if (! $scope['empresa_id']) {
                return $query->whereRaw('1 = 0');
            }

            return $query->whereHas($relation, function (Builder $builder) use ($scope) {
                $builder->where('empresa_id', $scope['empresa_id']);

                if ($scope['unidade_id']) {
                    $builder->where('unidade_id', $scope['unidade_id']);
                }

                if ($scope['setor_id']) {
                    $builder->whereHas('funcao', fn(Builder $funcaoBuilder) => $funcaoBuilder->where('setor_id', $scope['setor_id']));
                }
            });
        }

        if (userIsColaborador($user)) {
            $colaboradorId = $user->colaborador_id ?? 0;

            return $query->whereHas($relation, fn(Builder $builder) => $builder->whereKey($colaboradorId));
        }

        return $query->whereRaw('1 = 0');
    }
}

if (! function_exists('userCanExportRelatorioPonto')) {
    function userCanExportRelatorioPonto(User $user): bool
    {
        if (! moduleEnabled('cartao_de_ponto')) {
            return false;
        }

        return userIsSuperAdmin($user) || userIsRh($user);
    }
}

if (! function_exists('userCanAccessRegistroPonto')) {
    function userCanAccessRegistroPonto(User $user, ?Colaborador $colaborador): bool
    {
        if (! $colaborador) {
            return false;
        }

        if (userIsSuperAdmin($user)) {
            return true;
        }

        if (userIsRhLume($user)) {
            return empresaIsLume($colaborador->empresa);
        }

        if (userIsRhCliente($user)) {
            return userCanAccessEmpresa($user, $colaborador->empresa_id);
        }

        if (userIsGestor($user)) {
            $scope = userGestorScope($user);

            if (! $scope['empresa_id'] || $scope['empresa_id'] !== $colaborador->empresa_id) {
                return false;
            }

            if ($scope['unidade_id'] && $scope['unidade_id'] !== $colaborador->unidade_id) {
                return false;
            }

            if ($scope['setor_id'] && $scope['setor_id'] !== $colaborador->funcao?->setor_id) {
                return false;
            }

            return true;
        }

        if (userIsAdminLume($user) || userIsVendedorLume($user)) {
            return $user->colaborador_id === $colaborador->id
                && userAtivoParaPonto($user, $colaborador);
        }

        return userIsColaborador($user)
            && userAtivoParaPonto($user, $colaborador)
            && $user->colaborador_id === $colaborador->id;
    }
}

if (! function_exists('userCanAjustarPonto')) {
    function userCanAjustarPonto(User $user, RegistroPonto $registro): bool
    {
        if (! moduleEnabled('cartao_de_ponto')) {
            return false;
        }

        if (userHasGlobalScope($user)) {
            return true;
        }

        return userIsRh($user) && userCanAccessColaborador($user, $registro->colaborador);
    }
}

if (! function_exists('userCanManagePrimeiroAcesso')) {
    function userCanManagePrimeiroAcesso(User $user): bool
    {
        if (! moduleEnabled('auth_usuarios')) {
            return false;
        }

        return userHasGlobalScope($user) || userIsRh($user);
    }
}

if (! function_exists('userIsLumeStaff')) {
    function userIsLumeStaff(?User $user): bool
    {
        return userHasGlobalScope($user) || userIsVendedorLume($user);
    }
}

if (! function_exists('notifyUsersByRoles')) {
    /**
     * @param list<string> $roles
     */
    function notifyUsersByRoles(array $roles, string $title, ?string $body = null): void
    {
        $users = User::role($roles)->get();

        foreach ($users as $user) {
            Notification::make()
                ->title($title)
                ->body($body ?? '')
                ->success()
                ->sendToDatabase($user);
        }
    }
}

if (! function_exists('notifyLumeAdmins')) {
    function notifyLumeAdmins(string $title, ?string $body = null): void
    {
        notifyUsersByRoles(['admin_lume', 'administrativo_lume', 'adm_lume', 'admin', 'ceo', 'super_admin'], $title, $body);
    }
}

if (! function_exists('notifyVendedoresLume')) {
    function notifyVendedoresLume(string $title, ?string $body = null): void
    {
        notifyUsersByRoles(['vendedor_lume'], $title, $body);
    }
}
