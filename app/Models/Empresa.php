<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Empresa extends Model
{
    use LogsActivity;

    protected $fillable = [
        'grupo_id',
        'nome',
        'tipo_documento',
        'documento',
        'cnae',
        'atividade',
        'grau_risco',
        'cep',
        'logradouro',
        'bairro',
        'cidade',
        'uf',
        'ativo',
    ];

    protected $casts = [
        'ativo' => 'boolean',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'grupo_id',
                'nome',
                'tipo_documento',
                'documento',
                'cnae',
                'atividade',
                'grau_risco',
                'cep',
                'logradouro',
                'bairro',
                'cidade',
                'uf',
                'ativo',
            ])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function grupo(): BelongsTo
    {
        return $this->belongsTo(Grupo::class);
    }

    public function unidades(): HasMany
    {
        return $this->hasMany(Unidade::class);
    }
}
