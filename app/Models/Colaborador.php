<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Colaborador extends Model
{
    use LogsActivity;

    protected $table = 'colaboradores';

    protected $fillable = [
        'indexmed_id',
        'funcao_id',
        'unidade_id',
        'empresa_id',
        'fl_tipo',
        'trabalhador_sem_vinculo',
        'nome',
        'matricula',
        'cpf',
        'cpf_hash',
        'genero',
        'data_nascimento',
        'data_admissao',
        'ultima_avaliacao_clinica',
        'user_email',
        'user_ativo',
        'email_validado_em',
        'ativo',
        'codigo_externo',
        'status_integracao',
    ];

    protected $casts = [
        'ativo' => 'boolean',
        'trabalhador_sem_vinculo' => 'boolean',
        'data_nascimento' => 'date',
        'data_admissao' => 'date',
        'ultima_avaliacao_clinica' => 'date',
        'user_ativo' => 'boolean',
        'email_validado_em' => 'datetime',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'indexmed_id',
                'funcao_id',
                'unidade_id',
                'empresa_id',
                'fl_tipo',
                'trabalhador_sem_vinculo',
                'nome',
                'matricula',
                'cpf',
                'genero',
                'data_nascimento',
                'data_admissao',
                'ultima_avaliacao_clinica',
                'user_email',
                'user_ativo',
                'email_validado_em',
                'ativo',
                'codigo_externo',
                'status_integracao',
            ])
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

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    public function historicos(): HasMany
    {
        return $this->hasMany(ColaboradorHistorico::class);
    }
}
