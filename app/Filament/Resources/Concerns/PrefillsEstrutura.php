<?php

declare(strict_types=1);

namespace App\Filament\Resources\Concerns;

trait PrefillsEstrutura
{
    /**
    * Preenche o estado inicial do formulário com os parâmetros
    * hierárquicos recebidos por query string, evitando duplicação
    * de lógica entre pages de criação.
    */
    protected function getDefaultFormState(): array
    {
        $state = parent::getDefaultFormState();

        $request = request();

        foreach (['grupo_id', 'empresa_id', 'unidade_id', 'setor_id', 'funcao_id'] as $key) {
            if (! $request->filled($key)) {
                continue;
            }

            $value = $request->input($key);

            $state[$key] = is_numeric($value) ? (int) $value : $value;
        }

        return $state;
    }
}

