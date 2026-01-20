<?php

use App\Support\Modules\ModuleManager;

if (! function_exists('moduleEnabled')) {
    function moduleEnabled(string $module, ?int $grupoId = null, ?int $empresaId = null): bool
    {
        return app(ModuleManager::class)->enabled($module, $grupoId, $empresaId);
    }
}
