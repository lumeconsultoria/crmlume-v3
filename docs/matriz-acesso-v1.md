# Matriz de acesso v1 — CRM Lume v3

## Princípios

- Flags liberam **módulos**, não dados diretamente.
- Visibilidade e ações sempre passam por **Policy + Scope**.
- Nenhuma tela deve confiar apenas em role ou flag isolada.

## Escopos

- **Colaborador:** apenas ele mesmo.
- **RH (empresa):** empresa inteira do usuário.
- **Gestor:** grupo/unidade/setor configurável (por padrão, unidade e setor do próprio colaborador).
- **Vendedor Lume:** empresas atendidas.
- **Administrativo Lume:** todas as empresas.
- **Admin Lume:** global.
- **Super Admin:** global.

## Matriz (role × ação × escopo)

| Role                | Escopo              | Pode                                                                | Não pode                                                     |
| ------------------- | ------------------- | ------------------------------------------------------------------- | ------------------------------------------------------------ |
| Colaborador         | Ele mesmo           | Registrar ponto; ver espelho pessoal                                | Ver BI; ajustar ponto; ver outros colaboradores              |
| RH (empresa)        | Empresa             | Gerenciar colaboradores; ajustar ponto; ver BI; exportar relatórios | Ver outras empresas; dados internos da Lume                  |
| Gestor              | Grupo/unidade/setor | Ver equipe; BI operacional; presença/ausência                       | Ajustar ponto; exportar relatórios oficiais; dados sensíveis |
| Vendedor Lume       | Empresas atendidas  | Ver status do cliente; módulos ativos                               | Ver ponto; dados pessoais; laudos; exames                    |
| Administrativo Lume | Todas as empresas   | Alterar status; ativar módulos; resolver pendências                 | Alterar políticas globais                                    |
| Admin Lume          | Global              | Ver tudo; corrigir dados; apoiar clientes                           | Alterar políticas globais                                    |
| Super Admin         | Global              | Definir políticas; roles; flags; regras do sistema                  | —                                                            |

## Observações de implementação

- Policies devem validar **flag + role + escopo**.
- Scopes devem ser aplicados também nas **queries** (listas e exportações).
- Ações de ajuste/exportação devem validar `update`/`export` via Policy.
- Escopo do Gestor deve ser configurável; na ausência, usa unidade/setor do próprio colaborador.
