# Contrato Canônico v1 (CRM Lume v3)

Data: 2026-01-19

## Objetivo

Estabelecer o contrato canônico de dados para estrutura organizacional e colaboradores, garantindo governança e integração futura com módulos operacionais (ex.: Cartão de Ponto).

## Escopo (Core imutável)

Hierarquia canônica:

1. Grupo
2. Empresa
3. Unidade
4. Setor
5. Função
6. Colaborador
7. User (deriva de Colaborador)

## Contrato Canônico v1

### Estrutura Organizacional (CSV)

Campos obrigatórios (por nome):

- Grupo_Nome
- Empresa_Nome
- Unidade_Nome
- Setor_Nome
- Funcao_Nome
- Ativo (true/false)

### Colaboradores (CSV)

Campos obrigatórios (por nome):

- Grupo_Nome
- Empresa_Nome
- Unidade_Nome
- Setor_Nome
- Funcao_Nome
- Colaborador_Nome
- Colaborador_Ativo (true/false)
- Usuario_Email (pode ser vazio)
- Usuario_Ativo (true/false)

## Política Colaborador x Usuário

- Colaborador pode existir sem usuário.
- Usuário só é criado quando houver e-mail válido.
- Nome do usuário é derivado de Colaborador_Nome.
- Cartão de Ponto deve funcionar sem login (baseado em Colaborador).

## DRY-RUN (processo oficial)

O DRY-RUN é a etapa obrigatória de validação e auditoria antes de qualquer importação definitiva.

### Tipos de erro

- Bloqueante: impede importação definitiva.
- Alerta: permite importação, mas exige revisão futura.
- Informativo: notificação sem impacto na importação.

### Regras principais

- Resolver toda hierarquia por nome (sem IDs externos).
- Colaborador duplicado: Nome + Empresa + Unidade.
- Mudança de Função/Unidade atualiza o registro atual (sem histórico dedicado).

## Status oficial do Colaborador

- Ativo
- Afastado
- Férias
- Suspenso
- Desligado

### Impacto previsto no Cartão de Ponto

- Ativo: marcações permitidas.
- Afastado: marcações bloqueadas; manter para histórico.
- Férias: marcações bloqueadas; retornar ao término.
- Suspenso: marcações bloqueadas; retorno condicionado.
- Desligado: marcações bloqueadas; somente consulta.

## Observações

- Este contrato é a base para governança e escalabilidade.
- Alterações futuras exigem versionamento (v2, v3, ...).
