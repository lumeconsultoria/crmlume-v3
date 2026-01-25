<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Validation\ValidationException;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Empresa extends Model
{
    use LogsActivity;

    protected $fillable = [
        'indexmed_id',
        'codigo_externo',
        'grupo_id',
        'nm_razao_social',
        'nm_fantasia',
        'nr_cnpj',
        'cd_cnae',
        'nr_grau_risco',
        'ds_telefone',
        'ds_cep',
        'ds_logradouro',
        'ds_numero',
        'ds_complemento',
        'ds_bairro',
        'ds_cidade',
        'sgl_estado',
        'ativo',
        'status_integracao',
    ];

    protected $casts = [
        'ativo' => 'boolean',
        'nr_grau_risco' => 'integer',
    ];

    protected static function booted(): void
    {
        static::saving(function (Empresa $empresa) {
            if (! $empresa->grupo_id) {
                throw ValidationException::withMessages([
                    'grupo_id' => 'Empresa precisa estar vinculada a um Grupo.',
                ]);
            }
        });
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'indexmed_id',
                'codigo_externo',
                'grupo_id',
                'nm_razao_social',
                'nm_fantasia',
                'nr_cnpj',
                'cd_cnae',
                'nr_grau_risco',
                'ds_telefone',
                'ds_cep',
                'ds_logradouro',
                'ds_numero',
                'ds_complemento',
                'ds_bairro',
                'ds_cidade',
                'sgl_estado',
                'ativo',
                'status_integracao',
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
