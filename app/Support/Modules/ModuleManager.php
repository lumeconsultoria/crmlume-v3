<?php

declare(strict_types=1);

namespace App\Support\Modules;

class ModuleManager
{
    public function enabled(string $module, ?int $grupoId = null, ?int $empresaId = null): bool
    {
        $baseEnabled = (bool) config("modules.$module.enabled", false);

        if (! $baseEnabled) {
            return false;
        }

        $override = $this->resolveOverride($module, $grupoId, $empresaId);

        return $override ?? $baseEnabled;
    }

    private function resolveOverride(string $module, ?int $grupoId, ?int $empresaId): ?bool
    {
        return null;
    }
}
