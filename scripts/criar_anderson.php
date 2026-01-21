<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Colaborador;
use App\Models\Empresa;
use App\Models\Unidade;
use App\Models\Funcao;
use Carbon\Carbon;

echo "=== CRIANDO COLABORADOR ANDERSON ===" . PHP_EOL;
echo PHP_EOL;

// Dados do Anderson
$cpf = '20392315874';
$cpfHash = hash('sha256', $cpf);
$dataNascimento = Carbon::parse('1977-08-13');

// Buscar ou criar empresa Lume Engenharia
$empresa = Empresa::firstOrCreate(
    ['nome' => 'Lume Engenharia'],
    ['grupo_id' => 1, 'ativo' => true]
);

echo "✓ Empresa: {$empresa->nome} (ID: {$empresa->id})" . PHP_EOL;

// Buscar ou criar unidade
$unidade = Unidade::firstOrCreate(
    ['empresa_id' => $empresa->id, 'nome' => 'Matriz'],
    ['ativo' => true]
);

echo "✓ Unidade: {$unidade->nome} (ID: {$unidade->id})" . PHP_EOL;

// Buscar ou criar função
$funcao = Funcao::first();
if (!$funcao) {
    echo "✗ ERRO: Nenhuma função encontrada no banco!" . PHP_EOL;
    echo "Execute: php artisan db:seed" . PHP_EOL;
    exit(1);
}

echo "✓ Função: {$funcao->nome} (ID: {$funcao->id})" . PHP_EOL;
echo PHP_EOL;

// Verificar se já existe
$colaboradorExistente = Colaborador::where('cpf_hash', $cpfHash)->first();

if ($colaboradorExistente) {
    echo "⚠ Colaborador já existe!" . PHP_EOL;
    echo "  ID: {$colaboradorExistente->id}" . PHP_EOL;
    echo "  Nome: {$colaboradorExistente->nome}" . PHP_EOL;
    echo "  Data Nascimento: " . $colaboradorExistente->data_nascimento->format('d/m/Y') . PHP_EOL;
    exit(0);
}

// Criar colaborador
$colaborador = Colaborador::create([
    'funcao_id' => $funcao->id,
    'unidade_id' => $unidade->id,
    'empresa_id' => $empresa->id,
    'nome' => 'Anderson',
    'cpf' => '203.923.158-74',
    'cpf_hash' => $cpfHash,
    'data_nascimento' => $dataNascimento,
    'ativo' => true,
]);

echo "✓ Colaborador criado com sucesso!" . PHP_EOL;
echo "  ID: {$colaborador->id}" . PHP_EOL;
echo "  Nome: {$colaborador->nome}" . PHP_EOL;
echo "  CPF: {$colaborador->cpf}" . PHP_EOL;
echo "  Data Nascimento: " . $colaborador->data_nascimento->format('d/m/Y') . PHP_EOL;
echo "  Empresa: {$colaborador->empresa->nome}" . PHP_EOL;
echo "  Unidade: {$colaborador->unidade->nome}" . PHP_EOL;
echo PHP_EOL;
echo "✓ Agora você pode fazer o primeiro acesso em:" . PHP_EOL;
echo "  http://127.0.0.1:8000/primeiro-acesso" . PHP_EOL;
echo "  CPF: 203.923.158-74" . PHP_EOL;
echo "  Data Nascimento: 13/08/1977" . PHP_EOL;
