# Evidências de Segurança — CRM Lume v3

## 1) Models e Resources que exigem Policy

**Policies implementadas (modelo → policy):**

- AjustePonto → `AjustePontoPolicy`
- AssinaturaRelatorioPonto → `AssinaturaRelatorioPontoPolicy`
- Colaborador → `ColaboradorPolicy`
- EmailPendencia → `EmailPendenciaPolicy`
- Empresa → `EmpresaPolicy`
- Funcao → `FuncaoPolicy`
- Grupo → `GrupoPolicy`
- PrimeiroAcesso → `PrimeiroAcessoPolicy`
- RegistroPonto → `RegistroPontoPolicy`
- Role → `RolePolicy`
- Setor → `SetorPolicy`
- Unidade → `UnidadePolicy`

**Filament Resources com exigência de Policy:**

- EmpresaResource
- UnidadeResource
- SetorResource
- GrupoResource
- FuncaoResource
- ColaboradorResource
- EmailPendenciaResource
- PrimeiroAcessoResource
- RegistroPontoResource (Ops)

**Evidências (arquivos):**

- app/Policies/\*.php
- app/Filament/Resources/\*\*
- app/Filament/Ops/Resources/RegistroPontos/\*\*

## 2) Estratégia de verificação de acesso (deny by default)

- Policies verificam permissão explícita via `user->can(...)` e negam acesso quando ausente.
- ABAC é aplicado por funções auxiliares de escopo e por Global Scopes; quando o usuário é inválido ou não possui escopo, o acesso é negado por padrão.
- A interface Filament não é fonte de segurança; a validação é feita no backend.

**Evidências (arquivos):**

- app/Policies/ColaboradorPolicy.php
- app/Support/Auth/helpers.php

## 3) Pontos onde rate-limit é aplicado

- Rotas de **primeiro acesso** (`/primeiro-acesso` e `/primeiro-acesso/email`) usam middleware dedicado de rate-limit.
- Middleware `EnsurePrimeiroAcessoRateLimit` aplica limite por IP e por CPF (hash), com bloqueio por 429 em caso de excesso.

**Evidências (arquivos):**

- routes/web.php
- app/Http/Middleware/EnsurePrimeiroAcessoRateLimit.php
- bootstrap/app.php

## 4) Estratégia de logs e auditoria

- **Spatie Activity Log** ativo em Models: Colaborador, Empresa, Funcao, Grupo, Setor, Unidade, User e Solicitacao.
- **Observers** registrados para: Colaborador, User e Funcao (registro de eventos de domínio e rastreio de alterações).
- A auditoria de ações sensíveis é tratada no backend e refletida em páginas operacionais quando aplicável.

**Evidências (arquivos):**

- app/Models/Colaborador.php
- app/Models/Empresa.php
- app/Models/Funcao.php
- app/Models/Grupo.php
- app/Models/Setor.php
- app/Models/Unidade.php
- app/Models/User.php
- app/Models/Solicitacao.php
- app/Observers/ColaboradorObserver.php
- app/Observers/UserObserver.php
- app/Observers/FuncaoObserver.php
- app/Providers/AppServiceProvider.php

## 5) Limitações conhecidas e riscos aceitos

- Rate-limit evidenciado apenas nos fluxos de primeiro acesso; outros fluxos dependem de controles específicos ou de políticas internas.
- Auditoria automática evidenciada nos Models que utilizam `LogsActivity` e nos Observers registrados; demais Models dependem de instrumentação adicional quando necessário.
- A segurança não depende da UI; qualquer bloqueio via interface sem Policy é considerado insuficiente.
