<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Colaborador;
use App\Models\User;

$cpf = '20392315874';
$cpfHash = hash('sha256', $cpf);
$dataNascimento = '1977-08-13';

echo "=== VERIFICANDO COLABORADOR ===" . PHP_EOL;
echo "CPF: {$cpf}" . PHP_EOL;
echo "CPF Hash: {$cpfHash}" . PHP_EOL;
echo "Data Nascimento: {$dataNascimento}" . PHP_EOL;
echo PHP_EOL;

// Buscar por CPF Hash e data de nascimento (como o sistema faz)
$colaborador = Colaborador::query()
    ->where('cpf_hash', $cpfHash)
    ->whereDate('data_nascimento', $dataNascimento)
    ->first();

if ($colaborador) {
    echo "✓ Colaborador ENCONTRADO!" . PHP_EOL;
    echo "  ID: {$colaborador->id}" . PHP_EOL;
    echo "  Nome: {$colaborador->nome}" . PHP_EOL;
    echo "  Data Nascimento: " . ($colaborador->data_nascimento ? $colaborador->data_nascimento->format('d/m/Y') : 'null') . PHP_EOL;
    echo "  CPF (campo): {$colaborador->cpf}" . PHP_EOL;
    echo "  CPF Hash (banco): {$colaborador->cpf_hash}" . PHP_EOL;
    echo "  Empresa: " . ($colaborador->empresa?->nome ?? 'null') . PHP_EOL;
    echo "  Unidade: " . ($colaborador->unidade?->nome ?? 'null') . PHP_EOL;
    echo "  User ID: " . ($colaborador->user_id ?? 'null') . PHP_EOL;

    if ($colaborador->user) {
        echo "  User Email: {$colaborador->user->email}" . PHP_EOL;
        echo "  User Ativo: " . ($colaborador->user->ativo ? 'Sim' : 'Não') . PHP_EOL;
    } else {
        echo "  ✗ Sem usuário vinculado" . PHP_EOL;
    }
} else {
    echo "✗ Colaborador NÃO encontrado com CPF Hash + Data Nascimento" . PHP_EOL;
    echo PHP_EOL;

    // Buscar apenas por CPF Hash
    echo "Buscando apenas por CPF Hash..." . PHP_EOL;
    $colaboradorPorCpf = Colaborador::where('cpf_hash', $cpfHash)->first();

    if ($colaboradorPorCpf) {
        echo "✓ Encontrado por CPF Hash!" . PHP_EOL;
        echo "  ID: {$colaboradorPorCpf->id}" . PHP_EOL;
        echo "  Nome: {$colaboradorPorCpf->nome}" . PHP_EOL;
        echo "  Data Nascimento (banco): " . ($colaboradorPorCpf->data_nascimento ? $colaboradorPorCpf->data_nascimento->format('d/m/Y') : 'null') . PHP_EOL;
        echo "  Data informada: " . date('d/m/Y', strtotime($dataNascimento)) . PHP_EOL;
        echo "  ✗ PROBLEMA: Data de nascimento não confere!" . PHP_EOL;
    } else {
        echo "✗ Não encontrado nem por CPF Hash" . PHP_EOL;

        // Buscar Anderson
        echo PHP_EOL;
        echo "Buscando por nome 'Anderson'..." . PHP_EOL;
        $andersons = Colaborador::where('nome', 'like', '%Anderson%')->get();
        echo "Total encontrados: {$andersons->count()}" . PHP_EOL;

        foreach ($andersons as $a) {
            echo "  - ID: {$a->id} | Nome: {$a->nome} | Data Nasc: " . ($a->data_nascimento ? $a->data_nascimento->format('d/m/Y') : 'null') . " | CPF Hash: " . substr($a->cpf_hash, 0, 10) . "..." . PHP_EOL;
        }
    }
}

echo PHP_EOL;
echo "=== VERIFICANDO USUÁRIO ===" . PHP_EOL;
$user = User::where('email', 'andertsonmb1@gmail.com')->first();

if ($user) {
    echo "✓ Usuário ENCONTRADO!" . PHP_EOL;
    echo "  ID: {$user->id}" . PHP_EOL;
    echo "  Nome: {$user->name}" . PHP_EOL;
    echo "  Email: {$user->email}" . PHP_EOL;
    echo "  Ativo: " . ($user->ativo ? 'Sim' : 'Não') . PHP_EOL;
    echo "  Colaborador ID: " . ($user->colaborador_id ?? 'null') . PHP_EOL;

    if ($user->colaborador) {
        echo "  Colaborador: {$user->colaborador->nome}" . PHP_EOL;
    }
} else {
    echo "✗ Usuário NÃO encontrado com email 'andertsonmb1@gmail.com'" . PHP_EOL;

    // Buscar emails parecidos
    echo PHP_EOL;
    echo "Buscando emails parecidos..." . PHP_EOL;
    $users = User::where('email', 'like', '%anderson%')->get();
    echo "Total encontrados: {$users->count()}" . PHP_EOL;

    foreach ($users as $u) {
        echo "  - ID: {$u->id} | Email: {$u->email} | Nome: {$u->name} | Ativo: " . ($u->ativo ? 'Sim' : 'Não') . PHP_EOL;
    }
}
