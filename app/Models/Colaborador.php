<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Colaborador extends Model
{
    use LogsActivity;

    protected $table = 'colaboradores';

    protected $fillable = [
        'funcao_id',
        'unidade_id',
        'empresa_id',
        'nome',
        'ativo',
    ];

    protected $casts = [
        'ativo' => 'boolean',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['funcao_id', 'unidade_id', 'empresa_id', 'nome', 'ativo'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function funcao(): BelongsTo
    {
        return $this->belongsTo(Funcao::class);
    }

    public function unidade(): BelongsTo
    {
        return $this->belongsTo(Unidade::class);
    }

    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class);
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class);
    }
}
