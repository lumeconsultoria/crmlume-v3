<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Solicitacao extends Model
{
    use LogsActivity;

    protected $table = 'solicitacoes';

    protected $fillable = [
        'colaborador_id',
        'solicitante_id',
        'tipo',
        'descricao',
        'status',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['colaborador_id', 'solicitante_id', 'tipo', 'descricao', 'status'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function colaborador(): BelongsTo
    {
        return $this->belongsTo(Colaborador::class);
    }

    public function solicitante(): BelongsTo
    {
        return $this->belongsTo(User::class, 'solicitante_id');
    }
}
