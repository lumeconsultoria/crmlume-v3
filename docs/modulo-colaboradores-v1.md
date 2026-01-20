# Módulo Colaboradores v1 — Guia (Admin/Ops)

## Regra obrigatória de atualização (função/unidade)

- **Único caminho válido**: `ColaboradorService::atualizarFuncaoUnidade()`.
- Qualquer alteração direta em `funcao_id` ou `unidade_id` fora do service é **inválida** no módulo.

## Escopo desta regra

- Aplica-se a **todas as mudanças manuais** de função/unidade no fluxo Admin/Ops.
- **Não** se aplica a importações, seeds ou scripts automatizados (que não devem registrar histórico).

## Observação

- Não existem outros caminhos válidos para alteração de função/unidade no módulo.
