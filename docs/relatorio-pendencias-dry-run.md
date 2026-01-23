# Relatório consolidado de pendências do DRY-RUN

Data: 2026-01-19

## Origem dos dados

- Fonte: Indexmed
- Arquivo: \_temp_gpt_schema/colaboradores_crmlume_v1.csv

## Responsáveis pela correção na origem

- Primário: RH da empresa cliente
- Fallback: RH Lume (quando o cliente não possuir RH definido)

## Resumo

- Total de colaboradores lidos: 1132
- Duplicados (Nome + Empresa + Unidade): 1
- Emails vazios: 1132

## Pendências

### 1) Duplicidade (bloqueante)

- Linha 52 duplicada (mesma chave da linha 51)
- Colaborador: Celina Fátima de Camargo Dias
- Empresa: UNIMEF - UNIDADE DE MEDICINA FETAL DE SAO PAULO S/S LTDA
- Unidade: UNIMEF - UNIDADE DE MEDICINA FETAL DE SAO PAULO S/S LTDA

### 2) Emails vazios (alerta / pendência RH)

- Todos os 1132 registros estão sem e-mail.
- Ações:
    - RH deve providenciar e-mails válidos na origem.
    - Usuários não serão criados sem e-mail.
    - Colaboradores permanecem com pendência de regularização.

## Observações

- O CRM Lume não altera dados de origem automaticamente.
- Este relatório deve ser compartilhado com o RH responsável para saneamento.

## Impacto em Segurança e Onboarding

- E-mail válido é obrigatório para ativação de acesso.
- Primeiro acesso permanece bloqueado enquanto os dados não forem saneados.
- A responsabilidade de correção é operacional (RH na origem dos dados).
