<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\\Contracts\\Console\\Kernel')->bootstrap();

use App\Models\User;

$user = User::where('email', 'andersonmb1@gmail.com')->with('roles', 'colaborador.empresa')->first();

if (! $user) {
    echo "❌ Usuário não encontrado\n";
    exit(1);
}

echo "✓ Usuário: {$user->name}\n";
echo "Email: {$user->email}\n";
echo "Ativo: " . ($user->ativo ? '1' : '0') . "\n";
echo "Roles: " . ($user->roles->pluck('name')->implode(', ') ?: '[vazio]') . "\n";
echo "Colaborador ID: " . ($user->colaborador_id ?? 'null') . "\n";
echo "Colaborador ativo: " . (($user->colaborador?->ativo) ? '1' : '0') . "\n";
echo "Empresa: " . ($user->colaborador?->empresa?->nome ?? 'null') . "\n";
echo "Email verificado: " . ($user->email_verified_at ? '1' : '0') . "\n";
