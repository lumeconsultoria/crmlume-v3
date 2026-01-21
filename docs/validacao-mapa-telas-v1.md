# Validação do mapa de telas v1

## Objetivo

Congelar o mapa mínimo de telas do v1 antes de qualquer dashboard/BI.

## Protocolo de validação (sem código)

### Ordem obrigatória

1. Colaborador
2. RH
3. Gestor
4. Lume (interno)
5. Admin / Super Admin

### Perguntas para cada tela

- Consigo fazer o que preciso? (sim/não)
- Vejo algo que não deveria? (sim/não)
- Algo importante está escondido? (sim/não)

Se a resposta for “não / não / não” → tela aprovada.

### O que não fazer

- Não ajustar layout fino.
- Não criar dashboards.
- Não mudar fluxo.
- Não deixar “para depois”.

## Mapa aprovado (resumo)

Login
├── Primeiro Acesso
└── Home (por perfil)
├── Colaborador: Meu Ponto, Espelho
├── RH: Colaboradores, Ponto, Ajustes, Exportações
├── Gestor: Equipe, Espelho
└── Lume: Clientes, Auditoria, Administração

## Inventário atual (telas operacionais)

- Cartão de Ponto:
    - Meu Ponto / Ponto: CartaoPonto
    - Espelho: CartaoPontoDetalhe
- Colaboradores:
    - Listagem e edição: ColaboradorResource
    - Detalhe: ColaboradorDetalhe
- Primeiro Acesso:
    - Público: PrimeiroAcessoPublico (flag)
    - RH: EmailPendencias, PrimeiroAcessos
- Estrutura organizacional:
    - Grupos, Empresas, Unidades, Setores, Funções
- Importação:
    - DryRunImportacao (operação)

## Ajustes aplicados para o v1 mínimo

- Dashboards e widgets removidos dos panels (sem BI/indicadores no v1).

## Pendências de validação (mapa mínimo)

- Confirmar quais telas de Estrutura Organizacional entram em "Administração" (Lume).
- Confirmar se DryRunImportacao permanece acessível ou ficará fora do mapa v1.
- Confirmar uso do painel de Colaborador (há telas mínimas mapeadas no Ops).

## Congelamento

Após validação das pendências acima, manter apenas as telas do mapa aprovado até v2.
