<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\\Contracts\\Console\\Kernel')->bootstrap();

use App\Models\User;

$users = User::with('roles')->orderBy('name')->get();

if ($users->isEmpty()) {
    echo "Nenhum usuário encontrado.\n";
    exit(0);
}

foreach ($users as $user) {
    $roles = $user->roles->pluck('name')->implode(', ');
    $roles = $roles !== '' ? $roles : '[sem role]';
    echo "- {$user->name} | {$user->email} | {$roles}\n";
}
