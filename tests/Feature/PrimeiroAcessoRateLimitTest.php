<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Testing\TestResponse;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PrimeiroAcessoRateLimitTest extends TestCase
{
    use RefreshDatabase;
    protected function setUp(): void
    {
        parent::setUp();
        RateLimiter::clear('primeiro-acesso:ip:127.0.0.1');
        RateLimiter::clear('primeiro-acesso:cpf:' . hash('sha256', '12345678900'));
    }

    public function testRateLimitPorIp(): void
    {
        $payload = [
            'cpf' => '123.456.789-00',
            'data_nascimento' => '1990-01-01',
        ];

        for ($i = 0; $i < 5; $i++) {
            $this->postJson('/primeiro-acesso', $payload)->assertStatus(200);
        }

        $this->postJson('/primeiro-acesso', $payload)
            ->assertStatus(429)
            ->assertJson([
                'mensagem' => 'Não foi possível validar seus dados. Verifique e tente novamente ou contate o RH.',
            ]);
    }

    public function testRateLimitPorCpf(): void
    {
        $payload = [
            'cpf' => '123.456.789-00',
            'data_nascimento' => '1990-01-01',
        ];

        for ($i = 0; $i < 5; $i++) {
            $this->postJson('/primeiro-acesso', $payload, [
                'REMOTE_ADDR' => '10.0.0.' . ($i + 1),
            ])->assertStatus(200);
        }

        $this->postJson('/primeiro-acesso', $payload, [
            'REMOTE_ADDR' => '10.0.0.99',
        ])
            ->assertStatus(429)
            ->assertJson([
                'mensagem' => 'Não foi possível validar seus dados. Verifique e tente novamente ou contate o RH.',
            ]);
    }
}
