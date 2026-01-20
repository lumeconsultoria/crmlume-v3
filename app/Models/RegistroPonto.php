<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RegistroPonto extends Model
{
    protected $table = 'registro_pontos';

    public $timestamps = false;

    protected $fillable = [
        'colaborador_id',
        'data',
        'hora',
        'tipo',
        'origem',
        'criado_por_user_id',
        'created_at',
    ];

    protected $casts = [
        'data' => 'date',
        'created_at' => 'datetime',
    ];

    public function colaborador(): BelongsTo
    {
        return $this->belongsTo(Colaborador::class);
    }

    public function criadoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'criado_por_user_id');
    }

    public function ajustes(): HasMany
    {
        return $this->hasMany(AjustePonto::class, 'registro_ponto_id');
    }
}
