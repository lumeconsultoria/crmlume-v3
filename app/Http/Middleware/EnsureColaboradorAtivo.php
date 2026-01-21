<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureColaboradorAtivo
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if ($user && $user->colaborador_id) {
            $colaboradorAtivo = $user->colaborador?->ativo ?? false;

            if (! $colaboradorAtivo) {
                Auth::logout();
                abort(403);
            }
        }

        return $next($request);
    }
}
