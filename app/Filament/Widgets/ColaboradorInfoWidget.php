<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;

class ColaboradorInfoWidget extends Widget
{
    protected string $view = 'filament.widgets.colaborador-info-widget';

    protected static ?int $sort = 1;

    public function getColaborador()
    {
        $user = Auth::user();
        
        if (!$user || !$user->colaborador) {
            return null;
        }

        return $user->colaborador->load(['funcao', 'unidade', 'empresa', 'funcao.setor']);
    }
}
