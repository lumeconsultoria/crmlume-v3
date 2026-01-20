<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssinaturaRelatorioPonto extends Model
{
    protected $table = 'assinatura_relatorio_pontos';

    public $timestamps = false;

    protected $fillable = [
        'hash_documento',
        'algoritmo',
        'usuario_id',
        'periodo_inicio',
        'periodo_fim',
        'arquivo_path',
        'created_at',
    ];

    protected $casts = [
        'periodo_inicio' => 'date',
        'periodo_fim' => 'date',
        'created_at' => 'datetime',
    ];

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}
