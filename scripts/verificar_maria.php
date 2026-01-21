<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;

$user = User::where('email', 'teste@gmail.com')->first();

if (!$user) {
    echo "❌ Usuário não encontrado\n";
    exit(1);
}

echo "✓ Usuário encontrado: {$user->name}\n";
echo "Email: {$user->email}\n";
echo "Roles atuais: " . ($user->roles->pluck('name')->implode(', ') ?: '[vazio]') . "\n";

// Corrigir roles se necessário
if (!$user->hasRole('colaborador')) {
    echo "\n🔧 Atribuindo role 'colaborador'...\n";
    $user->assignRole('colaborador');
    echo "✓ Role atribuída com sucesso!\n";
} else {
    echo "✓ Role 'colaborador' já está atribuída\n";
}

echo "\n✓ Faça logout e login novamente para aplicar as mudanças\n";
