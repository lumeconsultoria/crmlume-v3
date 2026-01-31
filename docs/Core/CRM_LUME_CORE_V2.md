# CRM Lume – Core V2 (consolidado para importação offline V1)

## Objetivo
Consolidar o contrato mínimo para:
- aderir ao V1.1 original,
- incluir campos de integração IndexMed,
- garantir que todo dado necessário à importação offline (CSV/Excel) esteja previsto no modelo.

## Convenções
- Status externo (IndexMed): `status_integracao` = A/I/F/T.
- Status interno: `fl_status` (boolean) + `ds_status` opcional.
- IDs externos: `indexmed_id` (numérico) e `codigo_externo` (cd_interno_*).
- Documentos normalizados: apenas dígitos em CPF/CNPJ/CEP; formato pode ser aplicado na UI.
- Campos “derivado”: calculado no backend (ex.: grau_risco via CNAE).
- Campos “invisível”: existe no modelo, não aparece no form padrão.

## 1. Grupo
| Campo | Tipo | Obrigatório import | Observação |
|---|---|---|---|
| nm_grupo | string | sim | nome |
| nr_cnpj | string | sim | 14 dígitos |
| fl_status | bool | sim | A/I -> bool |
| status_integracao | char(1) | sim | A/I |
| indexmed_id | bigint | não | id externo |
| codigo_externo | string | não | cd_interno_grupo |
| auditoria | timestamps | auto |  |

## 2. Empresa
| Campo | Tipo | Obrigatório import | Observação |
|---|---|---|---|
| grupo_id | FK | sim |  |
| nm_razao_social | string | sim |  |
| nm_fantasia | string | não |  |
| nr_cnpj | string | sim | 14 dígitos |
| cd_cnae | string | sim | 5 dígitos |
| nr_grau_risco | tinyint | auto | derivado de CNAE se vazio |
| ds_telefone | string | não |  |
| endereços (cep, logradouro, numero, complemento, bairro, cidade, uf) | string | sim | pode herdar via CEP |
| fl_status | bool | sim | A/I -> bool |
| status_integracao | char(1) | sim | A/I |
| indexmed_id | bigint | não |  |
| codigo_externo | string | não | cd_interno_empresa |
| auditoria | timestamps | auto |  |

## 3. Unidade
| Campo | Tipo | Obrigatório import | Observação |
|---|---|---|---|
| empresa_id | FK | sim |  |
| nm_fantasia | string | sim |  |
| nr_cnpj | string | sim | 14 dígitos |
| cd_cnae | string | sim | 5 dígitos |
| nr_grau_risco | tinyint | auto | derivado de CNAE |
| ds_telefone | string | não |  |
| endereços (cep, logradouro, numero, complemento, bairro, cidade, uf) | string | sim |  |
| codigo_externo | string | sim | cd_interno_unidade |
| fl_status | bool | sim | A/I -> bool |
| status_integracao | char(1) | sim | A/I |
| indexmed_id | bigint | não |  |
| auditoria | timestamps | auto |  |

## 4. Setor
| Campo | Tipo | Obrigatório import | Observação |
|---|---|---|---|
| unidade_id | FK | sim |  |
| nm_setor | string | sim |  |
| descricao | text | não |  |
| codigo_externo | string | sim | cd_interno_setor |
| fl_status | bool | sim | A/I -> bool |
| status_integracao | char(1) | sim | A/I |
| indexmed_id | bigint | não |  |
| auditoria | timestamps | auto |  |

## 5. Função
| Campo | Tipo | Obrigatório import | Observação |
|---|---|---|---|
| setor_id | FK | sim |  |
| nm_funcao | string | sim |  |
| cd_cbo | string | não |  |
| descricao | text | não |  |
| codigo_externo | string | sim | cd_interno_funcao |
| fl_status | bool | sim | A/I -> bool |
| status_integracao | char(1) | sim | A/I |
| indexmed_id | bigint | não |  |
| auditoria | timestamps | auto |  |

## 6. Colaborador
| Campo | Tipo | Obrigatório import | Observação |
|---|---|---|---|
| funcao_id (deriva unidade/empresa/grupo) | FK | sim | referência única |
| nm_colaborador | string | sim |  |
| matricula | string | sim |  |
| cpf | string | sim | 11 dígitos |
| genero | string | sim | M/F/O |
| dt_nascimento | date | sim |  |
| dt_admissao | date | sim |  |
| user_email | string | sim | único |
| user_ativo | bool | auto | default true |
| email_validado_em | timestamp | auto | preenchido no fluxo de primeiro acesso |
| fl_status | bool | sim | A/I -> bool |
| status_integracao | char(1) | sim | A/I/F/T |
| indexmed_id | bigint | não |  |
| codigo_externo | string | não | (se houver) |
| auditoria | timestamps | auto |  |

## 7. Exames / Avaliações Clínicas
| Campo | Tipo | Obrigatório import | Observação |
|---|---|---|---|
| cd_avaliacao_clinica | PK | auto |  |
| cd_colaborador | FK | sim |  |
| tipo_exame | string | sim |  |
| status_exame | string | sim |  |
| dt_realizacao_exame | date | sim |  |
| dt_validade_exame | date | não |  |
| dt_proximo_exame | date | não |  |
| resultado_exame | string | não |  |
| observacoes_exame | text | não |  |
| origem_dado | string | sim | IndexMed/manual |
| protocolo_origem | string | não | id externo |
| auditoria | timestamps | auto |  |

## 8. Regras de importação (offline)
- CSV/Excel deve trazer todos os campos marcados “Obrigatório import”.
- Status externo deve vir como A/I/F/T; converter para fl_status bool + armazenar status_integracao.
- CNAE obrigatório para empresa e unidade; nr_grau_risco pode ser derivado pelo validador (CNAE → tabela NR-4).
- Hierarquia deve existir (grupo > empresa > unidade > setor > função) antes de inserir colaborador.
- Dados faltantes marcados como obrigatórios bloqueiam a importação (dry-run deve acusar).
- Campos invisíveis ainda precisam existir no modelo (mesmo sem UI).

