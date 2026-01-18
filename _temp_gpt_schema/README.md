# _temp_gpt_schema – Esquema (Laravel 12) gerado a partir das planilhas

Este diretório contém **migrations PHP separadas** para materializar a hierarquia:

**Grupo → Empresa → Unidade → Setor → Função → Funcionário**

As migrations foram geradas com base em:

- `ExportacaoEstrutura.xlsx` (abas: **Grupos-Clientes, Empresas, Unidades, Setores, Funções**)
- `Relatorio de Funcionarios - 14-1-2026.xlsx` (aba: **Relatorio de Funcionarios**)

## 1. Estratégia de IDs e relacionamentos

### 1.1 IDs internos vs. IDs externos (`cd_*`)
Cada tabela possui:

- `id` (chave primária interna, padrão Laravel)
- Campos **externos** conforme a exportação (`cd_grupo`, `cd_empresa`, `cd_unidade`, `cd_setor`, `cd_funcao`) com **índices/unique**

Isso permite:
- manter rastreabilidade com o sistema de origem;
- realizar importações idempotentes (upsert por `cd_*`);
- evoluir o schema sem depender do formato externo.

### 1.2 Foreign keys explícitas
Além das FKs usuais por `*_id` (internas), incluímos também FKs **por códigos externos** quando o dado existe na exportação:

- `empresas.cd_grupo` → `grupos.cd_grupo`
- `unidades.cd_empresa` → `empresas.cd_empresa`
- `unidades.cd_grupo` → `grupos.cd_grupo`
- `setores.cd_empresa` → `empresas.cd_empresa` (nullable)
- `setores.cd_grupo` → `grupos.cd_grupo` (nullable)
- `funcoes.cd_empresa` → `empresas.cd_empresa` (nullable)
- `funcoes.cd_grupo` → `grupos.cd_grupo` (nullable)

> Observação: o vínculo externo é útil na importação; o vínculo operacional do sistema normalmente deve usar os `*_id` internos.

## 2. Caso especial: `nm_unidade = "(Todas)"`

Nas abas **Setores** e **Funções**, a planilha **não traz `cd_unidade`**; ela traz apenas `nm_unidade` (texto),
e em diversos registros aparece **"(Todas)"**.

Por isso:

- `setores.unidade_id` é **nullable**
- `funcoes.unidade_id` e `funcoes.setor_id` são **nullable**

### 2.1 Como tratar na importação (recomendado)
Durante o ETL/import:

1. Se `nm_unidade` for um nome de unidade real, localizar a unidade em `unidades` (por `cd_empresa` + `nm_fantasia`/`nm_razao_social`, conforme seu padrão) e preencher `unidade_id`.
2. Se `nm_unidade == "(Todas)"`, manter `unidade_id = NULL` (indicando “aplica a todas as unidades”).
3. Para Funções, se houver regra de mapeamento Setor↔Função por nome na sua base, preencher `setor_id`; caso contrário, manter NULL.

## 3. Campos e tipagem

- Todas as **colunas identificadas nas planilhas** foram incluídas, preservando os nomes originais (`cd_*`, `nm_*`, `ds_*`, `fl_*`, `nr_*`, `dt_*`, `ts_*`) nas tabelas estruturais.
- Para `funcionarios`, os nomes da planilha foram normalizados para snake_case ASCII (ex.: `Data de Admissão` → `data_de_admissao`).
- Em geral, campos operacionais e textuais foram marcados como `nullable()` porque a exportação tem valores ausentes em várias linhas.
- Campos `ds_funcao` e `ds_setor` foram criados como `longText` pelo potencial de conteúdo extenso.

## 4. Índices incluídos (base operacional)

Foram adicionados índices nas colunas mais usadas em filtros/junções:

- códigos externos (`cd_*`) e status (`fl_status`)
- CNPJ/CPF e CNAE (`nr_cnpj`, `cpf`, `cd_cnae`)
- localização (`sgl_estado`, `nm_cidade`)
- chaves compostas para importação idempotente (ex.: `unique(['cd_grupo','cd_empresa'])`)

## 5. Arquivos gerados

- `2026_01_14_000001_create_grupos_table.php`
- `2026_01_14_000002_create_empresas_table.php`
- `2026_01_14_000003_create_unidades_table.php`
- `2026_01_14_000004_create_setores_table.php`
- `2026_01_14_000005_create_funcoes_table.php`
- `2026_01_14_000006_create_funcionarios_table.php`

## 6. Próximos passos sugeridos

1. Copiar os arquivos para `database/migrations/` do seu projeto Laravel 12.
2. Rodar:
   - `php artisan migrate`
3. Implementar um importador com:
   - upsert por `cd_*` nas tabelas estruturais;
   - resolução de `unidade_id` via `nm_unidade` (tratando "(Todas)");
   - vínculo de funcionário por nomes (Setor/Função) e, quando disponível, por `cd_*`.

