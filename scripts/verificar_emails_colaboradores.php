<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\\Contracts\\Console\\Kernel')->bootstrap();

use App\Models\User;

$users = User::role('colaborador')->get();

if ($users->isEmpty()) {
    echo "Nenhum colaborador encontrado.\n";
    exit(0);
}

foreach ($users as $user) {
    if ($user->email_verified_at) {
        echo "✓ {$user->email} já verificado\n";
        continue;
    }

    $user->email_verified_at = now();
    $user->save();

    echo "✓ Verificado: {$user->email}\n";
}
