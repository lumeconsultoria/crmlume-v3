<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureOpsPanelAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (! $user) {
            return $next($request);
        }

        if (userIsSuperAdmin($user) || userIsAdminLume($user)) {
            abort(403);
        }

        if (! (userIsRh($user) || userIsGestor($user) || userIsVendedorLume($user) || userIsColaborador($user))) {
            abort(403);
        }

        return $next($request);
    }
}
