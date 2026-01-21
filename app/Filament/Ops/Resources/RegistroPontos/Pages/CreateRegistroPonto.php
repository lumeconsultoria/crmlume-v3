<?php

declare(strict_types=1);

namespace App\Filament\Ops\Resources\RegistroPontos\Pages;

use App\Filament\Ops\Resources\RegistroPontos\RegistroPontoResource;
use App\Models\RegistroPonto;
use App\Services\CartaoPontoService;
use Carbon\Carbon;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class CreateRegistroPonto extends CreateRecord
{
    protected static string $resource = RegistroPontoResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $user = Auth::user();
        $colaborador = $user?->colaborador;

        if (! $user || ! $colaborador) {
            abort(403);
        }

        // Segurança: colaborador_id e horário são definidos no backend.
        return app(CartaoPontoService::class)
            ->registrarPonto($colaborador, $data['tipo'], $user->id, Carbon::now(), 'manual');
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}