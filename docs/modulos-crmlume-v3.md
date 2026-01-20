# Mapa oficial de módulos — CRM Lume v3

Objetivo: estabelecer um mapa oficial de módulos com classificação (Core, Funcional, Futuro), dependências e possibilidade de desligamento sem quebrar o core. Este documento não altera regras de negócio nem refatora o core atual.

## Classificações

- **Core**: obrigatório para o funcionamento básico do sistema.
- **Funcional (ativável)**: pode ser ativado/desativado sem afetar o core.
- **Futuro**: apenas planejamento, não implementado.

## Mapa de módulos

### 1) Estrutura Organizacional (Core)

- **Responsabilidade**: manter a hierarquia organizacional base.
- **Entidades principais**: Grupo, Empresa, Unidade, Setor, Função.
- **Dependências**: nenhuma.
- **Pode ser desativado sem quebrar o core**: não.

### 2) Colaboradores (Core)

- **Responsabilidade**: cadastro e vínculo de pessoas à estrutura.
- **Entidades principais**: Colaborador.
- **Dependências**: Estrutura Organizacional.
- **Pode ser desativado sem quebrar o core**: não.

### 3) Usuários & Acesso (Core)

- **Responsabilidade**: autenticação, usuários e permissões.
- **Entidades principais**: User, Roles, Permissions.
- **Dependências**: Colaboradores (vínculo).
- **Pode ser desativado sem quebrar o core**: não.

### 4) Auditoria & Logs (Funcional)

- **Responsabilidade**: trilha de auditoria e logs de atividade.
- **Entidades principais**: Activity Log.
- **Dependências**: Usuários & Acesso.
- **Pode ser desativado sem quebrar o core**: sim.

### 5) Admin/Ops (Funcional)

- **Responsabilidade**: backoffice de administração (Filament).
- **Entidades principais**: páginas e recursos administrativos.
- **Dependências**: Usuários & Acesso, Estrutura Organizacional, Colaboradores.
- **Pode ser desativado sem quebrar o core**: sim.

### 6) Importação Indexmed (Funcional)

- **Responsabilidade**: importar estrutura e colaboradores por CSV.
- **Entidades principais**: serviços de importação, comandos, relatórios.
- **Dependências**: Estrutura Organizacional, Colaboradores, Usuários & Acesso.
- **Pode ser desativado sem quebrar o core**: sim.

### 7) Solicitações (Funcional)

- **Responsabilidade**: abrir e acompanhar solicitações internas.
- **Entidades principais**: Solicitacao.
- **Dependências**: Colaboradores, Usuários & Acesso.
- **Pode ser desativado sem quebrar o core**: sim.

### 8) Cartão de Ponto (Futuro)

- **Responsabilidade**: controle de jornada e apontamentos.
- **Entidades principais**: a definir.
- **Dependências**: Colaboradores, Usuários & Acesso.
- **Pode ser desativado sem quebrar o core**: sim (não implementado).

### 9) Dashboards (Futuro)

- **Responsabilidade**: indicadores e BI operacional.
- **Entidades principais**: a definir.
- **Dependências**: módulos de dados (Estrutura, Colaboradores, Solicitações etc.).
- **Pode ser desativado sem quebrar o core**: sim (não implementado).

---

# Padrão único de módulo (template)

## Objetivo

Padronizar a estrutura mínima de um módulo para tornar habilitar/desabilitar simples e reversível.

## Estrutura recomendada

```
app/Modules/<Modulo>/
  Domain/
    Models/
    Policies/
  Application/
    Services/
    DTOs/
  Presentation/
    Filament/
      Resources/
      Pages/
    Controllers/
  Infrastructure/
    Providers/
    Routes/
    Migrations/
    Seeders/
    Tests/
```

## Rotas

- Criar arquivo dedicado: `routes/modules/<modulo>.php`.
- Registrar apenas se `config('modules.<modulo>.enabled')` for `true`.

## Permissões

- Padrão: `<modulo>.<acao>` (ex.: `estrutura.listar`, `colaboradores.editar`).
- Permissões ficam no módulo, sem reuso cruzado não declarado.

## Configuração

- Flags centralizadas em `config/modules.php`.
- Cada módulo deve ter:
    - `enabled` (bool)
    - `type` (`core` | `funcional` | `futuro`)

## Regras de segurança

- Módulos Core nunca devem ser desativados em produção.
- Módulos Funcionais podem ser desativados sem afetar o core.
- Módulos Futuro não têm código ativo.

---

# Referência inicial formalizada

- Módulo-base formalizado: **Estrutura Organizacional**.
- Este documento serve como referência para os demais módulos, sem alterar o core atual.
