<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Grupo extends Model
{
    use LogsActivity;

    protected $fillable = [
        'indexmed_id',
        'codigo_externo',
        'nr_cnpj',
        'logo_path',
        'nome',
        'ativo',
        'status_integracao',
    ];

    protected $casts = [
        'ativo' => 'boolean',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'indexmed_id',
                'codigo_externo',
                'nr_cnpj',
                'logo_path',
                'nome',
                'ativo',
                'status_integracao',
            ])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function empresas(): HasMany
    {
        return $this->hasMany(Empresa::class);
    }
}
