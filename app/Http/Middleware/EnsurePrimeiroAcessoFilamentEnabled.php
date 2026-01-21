<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePrimeiroAcessoFilamentEnabled
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! moduleEnabled('primeiro_acesso_filament')) {
            abort(404);
        }

        return $next($request);
    }
}
