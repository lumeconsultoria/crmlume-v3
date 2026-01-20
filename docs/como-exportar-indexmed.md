# Como exportar do Indexmed (CSV) + Checklist pré-upload

Data: 2026-01-19

## Objetivo

Garantir que a exportação do Indexmed atenda ao Contrato Canônico v1 do CRM Lume v3.

## Exportação recomendada

### Estrutura Organizacional

1. Exportar o relatório de estrutura (clientes/empresas/unidades/setores/funções).
2. Converter para CSV com separador vírgula (,).
3. Ajustar o cabeçalho para o contrato canônico v1:
    - Grupo_Nome,Empresa_Nome,Unidade_Nome,Setor_Nome,Funcao_Nome,Ativo

### Colaboradores

1. Exportar o relatório de funcionários.
2. Converter para CSV com separador vírgula (,).
3. Ajustar o cabeçalho para o contrato canônico v1:
    - Grupo_Nome,Empresa_Nome,Unidade_Nome,Setor_Nome,Funcao_Nome,
      Colaborador_Nome,Colaborador_Ativo,Usuario_Email,Usuario_Ativo

## Checklist pré-upload (obrigatório)

- [ ] Cabeçalho exato conforme contrato canônico v1.
- [ ] Sem colunas extras.
- [ ] Sem linhas de título/comentários.
- [ ] Todas as linhas com a mesma quantidade de colunas.
- [ ] Booleanos somente true/false.
- [ ] Sem IDs externos.
- [ ] Duplicados por Nome + Empresa + Unidade corrigidos.
- [ ] Emails válidos preenchidos quando houver criação de usuário.

## Política Colaborador x Usuário

- Colaborador pode existir sem usuário.
- Usuário só é criado quando houver e-mail válido.
- Sem e-mail → pendência RH (usuario não criado).

## Observações

- O CRM Lume não altera dados de origem automaticamente.
- A etapa de DRY-RUN é obrigatória antes de qualquer importação definitiva.
