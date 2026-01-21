# Regra oficial — Primeiro Acesso do Colaborador v1

## Objetivo

Permitir o primeiro acesso do colaborador de forma segura, evitando vazamento de dados e com trilha auditável.

## Fluxo oficial

1. Tela de login padrão (email/senha).
2. Link “Primeiro acesso”.
3. Etapa 1: CPF + Data de nascimento.
4. Se não encontrar colaborador: mensagem genérica (sem vazamento).
5. Se encontrar colaborador: exibir SOMENTE Nome + Empresa + Unidade (sem função/setor/status).
6. Se colaborador possui email:
    - mostrar email mascarado;
    - opção “enviar acesso” para email existente;
    - opção “corrigir email” (gera pendência/log).
7. Se colaborador NÃO possui email:
    - coletar email + confirmação.
8. Enviar email com link seguro para definição de senha (token expira).
9. Somente após validar email e definir senha: liberar dados completos.

## Mensagens e bloqueio de enumeração

- Mensagens sempre genéricas no erro de CPF/DN ou validação inválida.
- Nunca informar se CPF existe ou não.
- Limitar tentativas por IP e por CPF.

## Rate-limit

- Aplicar rate-limit por IP e por CPF (ex.: janela 15 minutos).
- Bloqueio temporário após exceder limite.

## Auditoria e logs

- Registrar tentativas (sucesso/falha) com data/hora, IP, user-agent.
- Registrar correções de email e envio de link.
- Logs não devem expor CPF completo (usar mascaramento).

## Link seguro de senha

- Token único, expira (curto prazo).
- Token invalidado após uso.
- Envio apenas para email validado.

## Critérios de aceitação

- Fluxo impede enumeração de colaboradores.
- Exibição parcial apenas de Nome + Empresa + Unidade.
- Email mascarado quando existente.
- Correção de email gera pendência/log.
- Token expira e é de uso único.
- Dados completos só após definição de senha.
- Logs/auditoria registram eventos sem vazamento de dados sensíveis.

## Status das telas Filament

- As telas Filament de primeiro acesso **existem**, porém **não fazem parte do fluxo ativo do v1**.
- Acesso controlado por flag: `primeiro_acesso_filament` (default: false).
- Ativação será feita em etapa posterior.
