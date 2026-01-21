<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PrimeiroAcessoNaoEnumeracaoTest extends TestCase
{
    use RefreshDatabase;
    protected function setUp(): void
    {
        parent::setUp();
        RateLimiter::clear('primeiro-acesso:ip:127.0.0.1');
        RateLimiter::clear('primeiro-acesso:cpf:' . hash('sha256', '98765432100'));
    }

    public function testMensagemNeutraQuandoNaoEncontrado(): void
    {
        $payload = [
            'cpf' => '987.654.321-00',
            'data_nascimento' => '1991-02-02',
        ];

        $this->postJson('/primeiro-acesso', $payload)
            ->assertStatus(200)
            ->assertJson([
                'mensagem' => 'Não foi possível validar seus dados. Verifique e tente novamente ou contate o RH.',
            ]);
    }
}
