<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\\Contracts\\Console\\Kernel')->bootstrap();

use App\Models\User;

$user = User::where('email', 'andersonmb1@gmail.com')->first();

if (! $user) {
    echo "❌ Usuário não encontrado\n";
    exit(1);
}

$user->email_verified_at = now();
$user->save();

echo "✓ Email verificado para {$user->email}\n";
