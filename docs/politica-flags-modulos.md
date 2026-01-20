# Política de habilitação por flags (módulos)

## Onde checar a flag

- Configuração central: `config/modules.php`.
- Resolução: `App\Support\Modules\ModuleManager::enabled()`.
- Helper global: `moduleEnabled('nome-do-modulo')`.

## Como usar no código

- Camada de aplicação/domínio: `moduleEnabled('colaboradores')`.
- Exemplo de future override (sem UI): `moduleEnabled('solicitacoes', $grupoId, $empresaId)`.

## Exemplos mínimos de uso

### Filament navigation

- Em Resource/Page/Widget: `shouldRegisterNavigation()` retornando `moduleEnabled('estrutura_organizacional')`.

### Policies

- Em Policy: `before()` retornando `false` se o módulo estiver desabilitado.

### Routes

- Middleware de rota: `->middleware('module.enabled:<modulo>')`.
- Exemplo: `Route::get('/relatorios', ...)->middleware('module.enabled:dashboards');`

## Observações

- Módulos Core devem permanecer habilitados em produção.
- Sem UI de override neste momento; apenas infraestrutura pronta.
