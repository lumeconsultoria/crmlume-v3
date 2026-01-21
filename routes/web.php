<?php

use App\Http\Controllers\PrimeiroAcessoController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/primeiro-acesso', [PrimeiroAcessoController::class, 'show'])
    ->middleware(['module.enabled:auth_usuarios'])
    ->name('primeiro-acesso.show');

Route::post('/primeiro-acesso', [PrimeiroAcessoController::class, 'store'])
    ->middleware(['module.enabled:auth_usuarios', 'primeiro-acesso.rate-limit']);

Route::post('/primeiro-acesso/email', [PrimeiroAcessoController::class, 'email'])
    ->middleware(['module.enabled:auth_usuarios', 'primeiro-acesso.rate-limit'])
    ->name('primeiro-acesso.email');

Route::get('/primeiro-acesso/token/{token}', [PrimeiroAcessoController::class, 'showToken'])
    ->middleware(['module.enabled:auth_usuarios'])
    ->name('primeiro-acesso.token');

Route::post('/primeiro-acesso/senha/{token}', [PrimeiroAcessoController::class, 'criarSenha'])
    ->middleware(['module.enabled:auth_usuarios'])
    ->name('primeiro-acesso.senha');

Route::get('/ops/logout', function () {
    Auth::logout();

    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect('/ops/login');
})->middleware('web');

Route::get('/admin/logout', function () {
    Auth::logout();

    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect('/admin/login');
})->middleware('web');
