<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

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

    protected static function booted(): void
    {
        if (app()->runningInConsole()) {
            return;
        }

        static::addGlobalScope('registro_ponto_scope', function (Builder $query): void {
            applyRegistroPontoScope($query, Auth::user());
        });
    }

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
