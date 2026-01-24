# CRM Lume v3

## 1) Visão geral

CRM Lume v3 é um CRM modular para operação de processos comerciais e administrativos. A plataforma usa Laravel 12 e Filament v4, com foco em governança, rastreabilidade e continuidade operacional. O projeto foi planejado para evolução contínua e integração futura com serviços externos.

**Stack principal**

- Laravel 12
- Filament v4
- Arquitetura modular (Core / Funcional / Futuro)

## 2) Arquitetura do sistema

O CRM Lume v3 adota arquitetura modular com separação explícita de responsabilidade:

- **Core**: entidades fundamentais, autenticação, autorização, auditoria, módulos-base.
- **Funcional**: domínios operacionais (ex.: colaboradores, contratos, agendas, relatórios).
- **Futuro**: integrações e recursos planejados (WAHA, n8n, conectores externos).

O desenho privilegia segurança, coesão de domínios e governança por políticas obrigatórias.

## 3) Mapa de módulos

**Core**

- Autenticação e sessão
- RBAC/ABAC e policies
- Auditoria e trilhas de operação
- Catálogos e cadastros-base

**Funcionais**

- Colaboradores
- Estrutura organizacional
- Relatórios operacionais
- Gestão de documentos e anexos

**Futuros**

- Integração com WAHA
- Automação via n8n
- Módulos externos e conectores de dados

## 4) Fluxos críticos

**Cadastro**

- Fluxo de criação guiado por políticas e validações do domínio.
- Registro de auditoria obrigatório.

**Primeiro acesso**

- Usuário criado com perfil inicial mínimo.
- Reset de senha obrigatório e reforço de MFA quando habilitado.

**Permissões**

- Permissões são concedidas por RBAC e refinadas por ABAC.
- Políticas são obrigatórias para todas as ações sensíveis.

## 5) Segurança e governança

A governança é baseada em Zero Trust, RBAC/ABAC e policies mandatórias. Todas as operações críticas devem passar por camadas de autorização e auditoria.

Documentação de segurança:

- [docs/seguranca/](docs/seguranca/)
- [docs/politica-flags-modulos.md](docs/politica-flags-modulos.md)

## 6) Como rodar o projeto localmente

Pré-requisitos: PHP 8.3+, Composer, Node.js 20+, banco de dados compatível com Laravel.

1. Instalar dependências PHP:
    ```bash
    composer install
    ```
2. Instalar dependências front-end:
    ```bash
    npm install
    ```
3. Configurar ambiente:
    ```bash
    copy .env.example .env
    php artisan key:generate
    ```
4. Configurar banco e rodar migrações:
    ```bash
    php artisan migrate
    ```
5. Subir o servidor local:
    ```bash
    php artisan serve
    ```
6. Executar o Vite:
    ```bash
    npm run dev
    ```

## 7) Regras de contribuição e limites do projeto

- Mudanças devem respeitar a arquitetura modular (Core/Funcional/Futuro).
- Todas as ações sensíveis exigem policy explícita.
- Não é permitido bypass de RBAC/ABAC.
- Integrações externas devem ser encapsuladas em módulos próprios.
- Documentação operacional é obrigatória para fluxos críticos.

Limites do projeto:

- Este repositório não distribui configurações de produção.
- Integrações futuras (WAHA, n8n e externas) são planejadas, não obrigatórias no estado atual.
- Ajustes de segurança requerem revisão técnica e documentação.
