<?php

namespace App\Services;

class DryRunImportReport
{
    public const TYPE_BLOCKING = 'bloqueante';
    public const TYPE_ALERT = 'alerta';
    public const TYPE_INFO = 'informativo';

    public const ESTRUTURA_HEADERS = [
        'Grupo_Nome',
        'Empresa_Nome',
        'Unidade_Nome',
        'Setor_Nome',
        'Funcao_Nome',
        'Ativo',
    ];

    public const COLABORADORES_HEADERS = [
        'Grupo_Nome',
        'Empresa_Nome',
        'Unidade_Nome',
        'Setor_Nome',
        'Funcao_Nome',
        'Colaborador_Nome',
        'Colaborador_Ativo',
        'Usuario_Email',
        'Usuario_Ativo',
    ];

    public array $estrutura = [
        'grupos' => [],
        'empresas' => [],
        'unidades' => [],
        'setores' => [],
        'funcoes' => [],
    ];

    public array $colaboradores = [
        'total' => 0,
        'novos' => 0,
        'atualizar' => 0,
        'usuarios_criar' => 0,
        'usuarios_pendentes' => 0,
    ];

    public array $errors = [
        self::TYPE_BLOCKING => [],
        self::TYPE_ALERT => [],
        self::TYPE_INFO => [],
    ];

    public function addError(string $type, string $message): void
    {
        if (! array_key_exists($type, $this->errors)) {
            $type = self::TYPE_INFO;
        }

        $this->errors[$type][] = $message;
    }

    public function hasBlockingErrors(): bool
    {
        return ! empty($this->errors[self::TYPE_BLOCKING]);
    }

    public function toArray(): array
    {
        return [
            'estrutura' => $this->estrutura,
            'colaboradores' => $this->colaboradores,
            'erros' => $this->errors,
        ];
    }
}
