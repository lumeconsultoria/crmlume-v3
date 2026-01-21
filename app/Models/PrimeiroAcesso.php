<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PrimeiroAcesso extends Model
{
    protected $table = 'primeiro_acessos';

    public $timestamps = false;

    protected $fillable = [
        'colaborador_id',
        'cpf_hash',
        'data_nascimento',
        'email_informado',
        'email_anterior',
        'status',
        'ip',
        'user_agent',
        'created_at',
    ];

    protected $casts = [
        'data_nascimento' => 'date',
        'created_at' => 'datetime',
    ];

    public function colaborador(): BelongsTo
    {
        return $this->belongsTo(Colaborador::class);
    }
}
