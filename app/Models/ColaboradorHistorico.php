<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ColaboradorHistorico extends Model
{
    protected $table = 'colaborador_historicos';

    public $timestamps = false;

    protected $fillable = [
        'colaborador_id',
        'funcao_id_anterior',
        'funcao_id_nova',
        'unidade_id_anterior',
        'unidade_id_nova',
        'alterado_por_user_id',
        'motivo',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function colaborador(): BelongsTo
    {
        return $this->belongsTo(Colaborador::class);
    }
}
