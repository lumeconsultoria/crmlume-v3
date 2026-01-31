<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Setor extends Model
{
    use LogsActivity;

    protected $table = 'setores';

    protected $fillable = [
        'indexmed_id',
        'codigo_externo',
        'unidade_id',
        'nome',
        'descricao',
        'ativo',
        'status_integracao',
    ];

    protected $casts = [
        'ativo' => 'boolean',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['indexmed_id', 'codigo_externo', 'unidade_id', 'nome', 'descricao', 'ativo', 'status_integracao'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function unidade(): BelongsTo
    {
        return $this->belongsTo(Unidade::class);
    }

    public function funcoes(): HasMany
    {
        return $this->hasMany(Funcao::class);
    }
}
