<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$user = App\Models\User::create([
    'name' => 'Anderson',
    'email' => 'andersonmb1@gmail.com',
    'password' => bcrypt('123456'),
]);

echo "✅ Usuário criado com sucesso!\n";
echo "Email: andersonmb1@gmail.com\n";
echo "Senha: 123456\n";
