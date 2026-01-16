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
        'nome',
        'ativo',
    ];

    protected $casts = [
        'ativo' => 'boolean',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['nome', 'ativo'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function empresas(): HasMany
    {
        return $this->hasMany(Empresa::class);
    }
}
