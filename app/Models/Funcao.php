<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Funcao extends Model
{
    use LogsActivity;

    protected $table = 'funcoes';

    protected $fillable = [
        'indexmed_id',
        'codigo_externo',
        'setor_id',
        'nome',
        'cd_cbo',
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
            ->logOnly(['indexmed_id', 'codigo_externo', 'setor_id', 'nome', 'cd_cbo', 'descricao', 'ativo', 'status_integracao'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function setor(): BelongsTo
    {
        return $this->belongsTo(Setor::class);
    }

    public function colaboradores(): HasMany
    {
        return $this->hasMany(Colaborador::class);
    }
}
