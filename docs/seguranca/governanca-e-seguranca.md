# Governança e Segurança — CRM Lume v3 (v1.0)

## 1) Princípios de segurança adotados

O CRM Lume v3 adota defesa em profundidade com foco em governança técnica, rastreabilidade e execução obrigatória de políticas no backend:

1. **RBAC (Role-Based Access Control)**: define capacidades (o quê).
2. **ABAC (Attribute-Based Access Control)**: define contexto e escopo (onde/quem).
3. **Zero Trust (Backend-First)**: a interface não é confiável; toda requisição é validada no backend por Policies.
4. **Auditoria contínua**: rastreabilidade total de ações sensíveis.

**Stack obrigatória**: PHP 8.3+ (Laravel 12) e Filament v4.

## 2) Modelo de controle de acesso

O modelo de acesso é baseado na intersecção de permissões (RBAC) com escopos (ABAC):

- **Usuário**: identidade de acesso, não define privilégios.
- **Papel (Role)**: agrupador de permissões (gerenciado por spatie/laravel-permission).
- **Permissão**: ação atômica nomeada (ex.: `ponto.view`).
- **Escopo (ABAC)**: restrição por atributos (ex.: `user_id`, `grupo_id`) implementada via Global Scopes do Laravel.
- **Interface (Filament)**: apenas reflete regras de negócio; não substitui Policy.

**Papéis oficiais (business logic)**

- Super Admin
- CEO Lume
- Admin Lume
- Gestor de Grupo
- RH
- Colaborador

**Regra de ouro**: o escopo sempre restringe a permissão. Mesmo com permissões globais, dados continuam limitados pelo escopo aplicável.

## 3) Exigências obrigatórias (Policies, logs, rate-limit)

**Policies (obrigatório)**

- Todos os Models e Recursos do Filament devem ter Policy correspondente.
- A Policy deve verificar permissão (RBAC) e pertencimento do dado (ABAC).

**Logs e auditoria (obrigatório)**

- Utilizar spatie/laravel-activitylog ou observadores nativos.
- Registrar criação, edição e exclusão de registros sensíveis (ponto, contratos, permissões).
- Registrar contexto mínimo: `user_id`, `ip_address`, `role_at_time`, dados `old` vs `new`.

**Rate-limit (obrigatório)**

- Aplicar rate-limit em rotas sensíveis e fluxos de autenticação.
- O rate-limit é parte do controle operacional e deve permanecer habilitado.

## 4) Regras de onboarding seguro

- Contas iniciam com perfil mínimo.
- Reset de senha obrigatório no primeiro acesso.
- Reforço de MFA quando habilitado.
- Acesso concedido apenas após associação explícita de papéis e escopos.

## 5) Escopo e limites do modelo de segurança

- Não é permitido bypass de RBAC/ABAC.
- Toda ação sensível exige Policy explícita.
- A interface Filament não é camada de segurança.
- Escopos globais devem ser aplicados a visões restritas (ex.: `user_id` e `grupo_id`).
- Integrações externas devem respeitar o modelo de autorização e auditoria existentes.

## 6) Declaração de validade do documento

Este documento constitui o contrato técnico oficial de governança e segurança do CRM Lume v3.

- **Versão**: 1.0
- **Data**: Janeiro/2026
- **Status**: Vigente e obrigatório

Referências complementares:

- docs/seguranca/
- docs/politica-flags-modulos.md
