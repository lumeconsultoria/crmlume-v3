<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Unidade extends Model
{
    use LogsActivity;

    protected $fillable = [
        'indexmed_id',
        'codigo_externo',
        'empresa_id',
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

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'indexmed_id',
                'codigo_externo',
                'empresa_id',
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

    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class);
    }

    public function setores(): HasMany
    {
        return $this->hasMany(Setor::class);
    }
}
