<?php

use App\Models\Colaborador;
use App\Models\User;
use App\Services\CartaoPontoService;
use App\Services\RelatorioPontoService;
use Carbon\Carbon;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\Storage;

require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();

$colaborador = Colaborador::query()->first();
if (! $colaborador) {
    fwrite(STDERR, "ERRO: falta colaborador.\n");
    exit(1);
}

$user = User::query()->first();
if (! $user) {
    fwrite(STDERR, "ERRO: falta usuário.\n");
    exit(1);
}

$cartaoService = app(CartaoPontoService::class);
$entrada = $cartaoService->registrarPonto($colaborador, 'entrada', $user->id, Carbon::now()->subMinutes(5));
$saida = $cartaoService->registrarPonto($colaborador, 'saida', $user->id, Carbon::now());
$ajuste = $cartaoService->ajustarRegistro($entrada, 'Correcao de teste', $user->id);

$relatorioService = app(RelatorioPontoService::class);
$assinatura = $relatorioService->exportarPeriodo(Carbon::now()->subDay(), Carbon::now(), $user->id);

$disk = Storage::disk('local');

if (! $disk->exists($assinatura->arquivo_path)) {
    fwrite(STDERR, "ERRO: arquivo exportado não encontrado.\n");
    exit(1);
}

$csv = $disk->get($assinatura->arquivo_path);
$hash = hash('sha256', $csv);
$tamanho = strlen($csv);

fwrite(STDOUT, "entrada_id={$entrada->id}\n");
fwrite(STDOUT, "saida_id={$saida->id}\n");
fwrite(STDOUT, "ajuste_id={$ajuste->id}\n");
fwrite(STDOUT, "relatorio_id={$assinatura->id}\n");
fwrite(STDOUT, "assinatura_hash={$assinatura->hash_documento}\n");
fwrite(STDOUT, "file_hash={$hash}\n");
fwrite(STDOUT, "file_size={$tamanho}\n");
