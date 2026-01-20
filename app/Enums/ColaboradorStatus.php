<?php

namespace App\Enums;

enum ColaboradorStatus: string
{
    case ATIVO = 'ativo';
    case AFASTADO = 'afastado';
    case FERIAS = 'ferias';
    case SUSPENSO = 'suspenso';
    case DESLIGADO = 'desligado';
}
