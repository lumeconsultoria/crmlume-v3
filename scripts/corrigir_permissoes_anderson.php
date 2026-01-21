<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;

$email = 'andersonmb1@gmail.com';

echo "=== CORRIGINDO PERMISSÕES DO USUÁRIO ===" . PHP_EOL;
echo PHP_EOL;

$user = User::where('email', $email)->first();

if ($user) {
    echo "✓ Usuário encontrado: {$user->name}" . PHP_EOL;
    echo "  Email: {$user->email}" . PHP_EOL;
    echo "  Roles atuais: " . $user->roles->pluck('name')->join(', ') . PHP_EOL;
    echo PHP_EOL;

    // Atribuir apenas role de colaborador
    $user->syncRoles(['colaborador']);

    echo "✓ Roles atualizadas!" . PHP_EOL;
    echo "  Nova role: colaborador" . PHP_EOL;
    echo PHP_EOL;
    echo "✓ Agora o usuário tem acesso apenas ao painel /ops (colaboradores)" . PHP_EOL;
    echo "  Faça logout e login novamente para aplicar as mudanças" . PHP_EOL;
} else {
    echo "✗ Usuário não encontrado com email: {$email}" . PHP_EOL;
}
