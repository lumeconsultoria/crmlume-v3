# Importação de dados — V1 (sem API)

## Escopo
- V1 roda 100% offline/CLI (sem endpoint público).
- Fonte: Excel ou CSV exportado do _temp_gpt_schema (abas Grupos-Clientes, Empresas, Unidades, Setores, Funções, Colaboradores).
- Objetivo: validar, saneador e importar para o Core já aderente ao contrato `crm_lume_v_1.md`.

## Pastas de trabalho (sugeridas)
- `storage/imports/pending` — uploads brutos.
- `storage/imports/validated` — arquivos limpos após validação.
- `storage/imports/reports` — relatórios de inconsistências (CSV/JSON).

## Formato esperado (por aba/CSV) — alinhado ao CORE V2
| Aba/arquivo | Colunas mínimas (header) | Observações |
| --- | --- | --- |
| Grupos-Clientes | nm_grupo*, nr_cnpj*, fl_status*, status_integracao*, codigo_externo?, indexmed_id? | `nr_cnpj` 14 dígitos; status_integracao A/I |
| Empresas | nm_grupo*, nm_razao_social*, nm_fantasia, nr_cnpj*, cd_cnae*, nr_grau_risco?, ds_telefone, endereço completo (ds_logradouro, ds_numero, ds_complemento, ds_bairro, ds_cep*, sgl_estado*, nm_cidade*), fl_status*, status_integracao*, codigo_externo?, indexmed_id? | grau_risco pode ser derivado do CNAE |
| Unidades | nm_grupo*, nm_razao_social*, nm_fantasia*, nr_cnpj*, cd_cnae*, nr_grau_risco?, endereço completo, ds_telefone, codigo_externo* (cd_interno_unidade), fl_status*, status_integracao*, indexmed_id? |  |
| Setores | nm_grupo*, nm_razao_social*, nm_unidade*, nm_setor*, descricao, codigo_externo* (cd_interno_setor), fl_status*, status_integracao*, indexmed_id? |  |
| Funções | nm_grupo*, nm_razao_social*, nm_unidade*, nm_setor*, nm_funcao*, cd_cbo, descricao, codigo_externo* (cd_interno_funcao), fl_status*, status_integracao*, indexmed_id? |  |
| Colaboradores | nm_grupo*, nm_razao_social*, nm_unidade*, nm_setor*, nm_funcao*, nm_colaborador*, matricula*, cpf*, genero*, dt_nascimento*, dt_admissao*, user_email*, user_ativo?, email_validado_em?, fl_status*, status_integracao*, indexmed_id? | `cpf` 11 dígitos; status_integracao A/I/F/T |

`*` campo obrigatório para importar.

## Validações de dry-run
- Presença de obrigatórios por aba (marcados com *).
- Unicidade por chave de negócio:
  - Grupo: `nr_cnpj` ou `nm_grupo` (normalizado).
  - Empresa: (`nm_grupo`,`nr_cnpj`) ou (`nm_grupo`,`nm_razao_social`).
  - Unidade: (`nm_grupo`,`nm_razao_social`,`nm_fantasia`).
  - Setor: (`nm_unidade`,`nm_setor`).
  - Função: (`nm_unidade`,`nm_setor`,`nm_funcao`).
  - Colaborador: (`cpf`) e (`nm_unidade`,`nm_colaborador`) para detectar duplicidade nominal.
- Formatos: CNPJ/CPF numéricos e válidos; CEP 8 dígitos; e-mail regex simples + DNS opcional.
- Referências cruzadas: cada linha de níveis abaixo deve existir nas abas superiores (ex.: Unidade precisa existir na aba Unidades; Colaborador depende de Função/Setor/Unidade/Empresa/Grupo).
- CNAE → Grau de risco: se `nr_grau_risco` vazio, preencher consultando `database/cnae_nr04_validado.csv`; se CNAE ausente ou não encontrado, marcar erro bloqueante.
- Status: `fl_status` deve ser boolean; valores inválidos geram erro.
- Status integração: deve ser A/I/F/T (colab) ou A/I (demais); converte para `fl_status` conforme regra.

## Pipeline sugerido (CLI)
1) `php artisan import:validar --file path.xlsx --out storage/imports/validated/clean.csv --report storage/imports/reports/report.json`
   - Lê todas as abas; aplica validações acima.
   - Gera CSV consolidado e relatório de erros/avisos.
2) `php artisan import:executar --file storage/imports/validated/clean.csv --batch=500`
   - Importa em transações por lote.
   - Preenche `user_ativo` default true quando não informado.
   - Não cria usuários sem `user_email`.
   - Registra log de importação (sucesso/erro por linha).

## Regras de preenchimento automático
- `nr_grau_risco`: calculado via CNAE quando faltante.
- `cd_grupo`, `cd_empresa`, `cd_unidade`, `cd_setor`, `cd_funcao`: resolvidos por busca/ criação incremental seguindo hierarquia (evitar IDs fixos).
- `email_validado_em`: vazio no import; será preenchido pelo fluxo de primeiro acesso.
- Auditoria (`created_by`, `updated_by`): setar usuário do processo de importação.

## Saídas do dry-run
- `report.json` com:
  - erros (bloqueiam importação)
  - avisos (dados saneados automaticamente)
  - estatísticas (linhas processadas, rejeitadas, corrigidas)
- `clean.csv` pronto para o passo de execução.

## Décalogo operacional (V1)
- Sem API pública; somente CLI.
- Não importar se houver qualquer erro bloqueante.
- Commits de schema/contrato devem preceder novas execuções.
- Manter os arquivos originais em `pending` para auditoria.
- Registrar hash do arquivo importado no log de execução.
