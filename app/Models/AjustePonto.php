<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AjustePonto extends Model
{
    protected $table = 'ajuste_pontos';

    public $timestamps = false;

    protected $fillable = [
        'registro_ponto_id',
        'motivo',
        'alterado_por_user_id',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function registroPonto(): BelongsTo
    {
        return $this->belongsTo(RegistroPonto::class, 'registro_ponto_id');
    }

    public function alteradoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'alterado_por_user_id');
    }
}
