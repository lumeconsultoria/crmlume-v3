<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

class EnsurePrimeiroAcessoRateLimit
{
    private const MAX_ATTEMPTS = 5;

    private const DECAY_SECONDS = 900;

    private const MESSAGE = 'Não foi possível validar seus dados. Verifique e tente novamente ou contate o RH.';

    public function handle(Request $request, Closure $next): Response
    {
        $ipKey = $this->ipKey($request->ip());
        $cpfKey = $this->cpfKey($request->input('cpf'));

        if (
            RateLimiter::tooManyAttempts($ipKey, self::MAX_ATTEMPTS)
            || ($cpfKey && RateLimiter::tooManyAttempts($cpfKey, self::MAX_ATTEMPTS))
        ) {
            return response()->json([
                'mensagem' => self::MESSAGE,
            ], 429);
        }

        RateLimiter::hit($ipKey, self::DECAY_SECONDS);

        if ($cpfKey) {
            RateLimiter::hit($cpfKey, self::DECAY_SECONDS);
        }

        return $next($request);
    }

    private function ipKey(?string $ip): string
    {
        return 'primeiro-acesso:ip:' . ($ip ?: 'unknown');
    }

    private function cpfKey(?string $cpf): ?string
    {
        if (! $cpf) {
            return null;
        }

        $cpfNormalizado = preg_replace('/\D+/', '', $cpf) ?? '';
        if ($cpfNormalizado === '') {
            return null;
        }

        return 'primeiro-acesso:cpf:' . hash('sha256', $cpfNormalizado);
    }
}
