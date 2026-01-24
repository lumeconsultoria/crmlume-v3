# CRM Lume – V1.1 do Core

## Objetivo do documento
Este documento define **o contrato de dados do Core (V1.1)** do CRM Lume.

Ele é o **documento oficial de validação** para:
- Estrutura de **migrations**
- Estrutura de **models**
- Campos exibidos em **relatórios**
- Campos disponíveis (visíveis ou não) nos **formulários V1.1**

Este documento deve ser usado como **referência única** antes de qualquer desenvolvimento. Somente após a validação deste contrato é que os dados poderão ser trazidos para o sistema.

### Escopo da V1.1
- Fonte principal de dados: **cadastros internos do CRM Lume**
- Integrações ativas nesta versão:
  - IndexMed (SST / PCMSO)
- Integrações **fora do escopo da V1.1** (planejadas para versões futuras):
  - ClickUp (tarefas, serviços, contratos)
  - RD Station (leads, contatos, empresas e histórico comercial)

**Regra-mãe:**
> Todo campo listado aqui **existe no modelo**, mesmo que não apareça no formulário.

**Regra importante sobre RowNumber:**
> `RowNumber` **NÃO é um campo de modelo**.
> Ele é um campo **derivado de consulta/relatório** (ROW_NUMBER, índice de exportação ou ordenação de grid).
> ❌ Não deve existir em migrations, models ou formulários.
> ✅ Pode existir apenas em views, queries SQL, exports (CSV/Excel) e relatórios.



---

## 1. GRUPO / CLIENTE (Nó Raiz da Árvore)

### 1.1 Campos do Modelo (database / model)
| Campo | Tipo | Observação |
|------|------|------------|
| cd_grupo | PK | Identificador do grupo |
| nm_grupo | string | Nome interno do grupo (exibição CRM) |
| nm_razao_social | string | Razão social (jurídico) |
| nr_cnpj | string | CNPJ do grupo |
| ds_status | string | Descrição do status |
| fl_status | boolean | Ativo / Inativo |
| fl_pre_agendamento | boolean | Regra operacional |
| ds_pre_agendamento | text | Observação de pré-agendamento |
| fl_esocial_automatico | boolean | Integração automática eSocial |
| ds_esocial_automatico | text | Observação eSocial |
| dt_minima_eventos | date | Data mínima para eventos |
| cd_assinatura | FK | Contrato / plano |
| cd_user_cadm | FK | Usuário que cadastrou |
| nm_user_cadm | string | Nome do usuário |
| ts_user_cadm | timestamp | Data do cadastro |
| cd_user_manu | FK | Último usuário que alterou |
| nm_user_manu | string | Nome do usuário |
| ts_user_manu | timestamp | Data da última alteração |

### 1.2 Visibilidade no Formulário (V1.1)
**Visíveis:**
- nm_grupo
- nm_razao_social
- nr_cnpj
- fl_status

**Invisíveis / avançados:**
- Demais campos (existem apenas no modelo e relatórios)

---

## 2. EMPRESA / EMPREGADOR

### 2.1 Campos do Modelo (consolidados – V1.1)
| Campo | Tipo | Observação |
|------|------|------------|
| cd_empresa | PK | Identificador da empresa |
| cd_grupo | FK | Relacionamento com Grupo / Cliente |
| nm_grupo | string | Nome do grupo (denormalizado para relatório) |
| nm_razao_social | string | Razão social da empresa |
| nm_fantasia | string | Nome fantasia |
| nr_cnpj | string | CNPJ normalizado (somente números) |
| nr_cnpj_frm | string | CNPJ formatado (visual / histórico) |
| cd_tipo_inscricao | int | Tipo de inscrição (CNPJ / CAEPF / etc.) |
| ds_tipo_inscricao | string | Descrição do tipo de inscrição |
| cd_cnae | string | CNAE principal |
| ds_cnae | string | Descrição do CNAE |
| nr_grau_risco | int | Grau de risco (NR-4) |
| fl_matriz | boolean | Indica se é matriz |
| status_matriz | string | Status da matriz (informativo) |
| fl_status | boolean | Empresa ativa / inativa |
| ds_status | string | Descrição do status |

#### Endereço
| Campo | Tipo | Observação |
|------|------|------------|
| ds_logradouro | string | Logradouro |
| ds_numero | string | Número |
| ds_complemento | string | Complemento |
| ds_bairro | string | Bairro |
| ds_cep | string | CEP |
| cd_estado | FK | Estado |
| sgl_estado | string | Sigla do estado |
| cd_cidade | FK | Cidade |
| nm_cidade | string | Nome da cidade |

#### Contato e Web
| Campo | Tipo | Observação |
|------|------|------------|
| ds_website | string | Site da empresa |
| ds_telefone | string | Telefone principal |

#### Médico Coordenador (PCMSO)
| Campo | Tipo | Observação |
|------|------|------------|
| nm_medico_coordenador | string | Nome do médico |
| nr_crm | string | CRM |
| cd_estado_medico | FK | Estado do CRM |
| sgl_estado_medico | string | Sigla do estado do CRM |
| nr_cpf_medico | string | CPF do médico |
| ds_status_medico | string | Status do médico |

#### Regras PCMSO / eSocial
| Campo | Tipo | Observação |
|------|------|------------|
| fl_regra_padrao_pcmso | boolean | Regra padrão PCMSO |
| cd_grupo_esocial | FK | Grupo eSocial |

#### Auditoria
| Campo | Tipo | Observação |
|------|------|------------|
| cd_user_cadm | FK | Usuário que cadastrou |
| nm_user_cadm | string | Nome do usuário |
| ts_user_cadm | timestamp | Data do cadastro |
| cd_user_manu | FK | Usuário que alterou |
| nm_user_manu | string | Nome do usuário |
| ts_user_manu | timestamp | Data da última alteração |

### 2.2 Visibilidade no Formulário (V1.1)
**Visíveis (cadastro padrão):**
- cd_grupo
- nm_razao_social
- nm_fantasia
- nr_cnpj
- cd_cnae / ds_cnae
- nr_grau_risco
- endereço completo
- ds_telefone
- fl_matriz
- fl_status

**Invisíveis / avançados (existem no modelo):**
- nr_cnpj_frm
- cd_tipo_inscricao / ds_tipo_inscricao
- dados do médico coordenador
- regras PCMSO / eSocial
- campos de auditoria

(REMOVIDO – seção duplicada antiga de Empresa. A seção válida de Empresa é a **2. EMPRESA / EMPREGADOR – Campos do Modelo (consolidados – V1.1)** acima.)

---

## 3. UNIDADE / ESTABELECIMENTO

### 3.1 Campos do Modelo (consolidados – V1.1)
| Campo | Tipo | Observação |
|------|------|------------|
| cd_unidade | PK | Identificador da unidade |
| cd_empresa | FK | Empresa / Empregador |
| cd_grupo | FK | Grupo / Cliente |
| cd_interno_unidade | string | Código interno |
| nm_grupo | string | Nome do grupo (relatório) |
| nm_razao_social | string | Razão social da empresa |
| nm_fantasia_empresa | string | Nome fantasia da empresa |
| nm_fantasia | string | Nome fantasia da unidade |
| nr_cnpj | string | CNPJ |
| nr_cnpj_frm | string | CNPJ formatado |
| ds_tipo_empresa | string | Tipo da empresa |
| cd_cnae | string | CNAE |
| ds_cnae | string | Descrição CNAE |
| nr_grau_risco | int | Grau de risco |
| nr_vidas | int | Número de vidas |

#### Endereço
| Campo | Tipo | Observação |
|------|------|------------|
| ds_logradouro | string | Logradouro |
| ds_numero | string | Número |
| ds_complemento | string | Complemento |
| ds_bairro | string | Bairro |
| ds_cep | string | CEP |
| cd_estado | FK | Estado |
| sgl_estado | string | Sigla do estado |
| nm_estado | string | Nome do estado |
| cd_cidade | FK | Cidade |
| nm_cidade | string | Nome da cidade |

#### Estrutura Física / Ambiente
| Campo | Tipo | Observação |
|------|------|------------|
| nr_metro_quadrado | decimal | Área construída |
| nr_andares | int | Número de andares |
| cd_tipo_edificacao | FK | Tipo de edificação |
| ds_tipo_edificacao | string | Descrição |
| cd_tipo_fechamento | FK | Tipo de fechamento |
| ds_tipo_fechamento | string | Descrição |
| cd_tipo_piso | FK | Tipo de piso |
| ds_tipo_piso | string | Descrição |
| cd_tipo_iluminacao | FK | Tipo de iluminação |
| ds_tipo_iluminacao | string | Descrição |
| cd_tipo_ventilacao | FK | Tipo de ventilação |
| ds_tipo_ventilacao | string | Descrição |
| cd_localAmb | FK | Local / ambiente |
| ds_localAmb | string | Descrição |
| cd_localAmb1 | FK | Local / ambiente complementar |
| ds_localAmb1 | string | Descrição complementar |

#### Responsáveis / Contato
| Campo | Tipo | Observação |
|------|------|------------|
| nm_gestor | string | Nome do gestor |
| ds_email_gestor | string | E-mail do gestor |
| nr_telefone_gestor | string | Telefone do gestor |
| nr_cpf_responsavel | string | CPF do responsável |
| ds_telefone | string | Telefone principal |

#### Médico Coordenador
| Campo | Tipo | Observação |
|------|------|------------|
| cd_medico_coordenador_empresa | FK | Médico da empresa |
| cd_medico_coordenador | FK | Médico da unidade |
| nm_medico_coordenador | string | Nome do médico |
| nr_crm | string | CRM |
| nr_cpf_medico | string | CPF do médico |
| cd_estado_medico | FK | Estado CRM |
| sgl_estado_medico | string | Sigla estado CRM |
| ds_status_medico | string | Status do médico |

#### Status / Regras
| Campo | Tipo | Observação |
|------|------|------------|
| fl_status | boolean | Unidade ativa / inativa |
| ds_status | string | Descrição do status |
| cd_profissional_seguranca | FK | Profissional SST |
| nr_mtb | string | Registro MTB |

#### Auditoria
| Campo | Tipo | Observação |
|------|------|------------|
| cd_user_cadm | FK | Usuário cadastro |
| nm_user_cadm | string | Nome |
| ts_user_cadm | timestamp | Data |
| cd_user_manu | FK | Usuário alteração |
| nm_user_manu | string | Nome |
| ts_user_manu | timestamp | Data |

### 3.2 Regra UX V1.1
- Unidade nasce **pré-preenchida** com dados da Empresa
- Usuário pode editar

---

## 4. SETOR

### 4.1 Campos do Modelo (V1.1)
| Campo | Tipo | Observação |
|------|------|------------|
| cd_setor | PK | Identificador |
| cd_empresa | FK | Empresa |
| cd_grupo | FK | Grupo |
| nm_empresa | string | Nome da empresa (relatório) |
| nm_unidade | string | Nome da unidade (relatório) |
| nm_grupo | string | Nome do grupo (relatório) |
| nm_setor | string | Nome do setor |
| ds_setor | text | Descrição |
| cd_centro_custo | string | Centro de custo |
| cd_interno_setor | string | Código interno |
| nr_metro_quadrado | decimal | Área |
| nr_altura_pe_direito | decimal | Pé-direito |
| cd_tipo_edificacao | FK | Tipo edificação |
| cd_tipo_fechamento | FK | Tipo fechamento |
| cd_tipo_piso | FK | Tipo piso |
| cd_tipo_iluminacao | FK | Tipo iluminação |
| cd_tipo_ventilacao | FK | Tipo ventilação |
| cd_tipo_cobertura | FK | Tipo cobertura |
| fl_ds_setor_esocial | boolean | Setor eSocial |
| fl_status | boolean | Ativo / Inativo |
| ds_status | string | Descrição status |
| cd_user_cadm | FK | Usuário cadastro |
| nm_user_cadm | string | Nome |
| ts_user_cadm | timestamp | Data |
| cd_user_manu | FK | Usuário alteração |
| nm_user_manu | string | Nome |
| ts_user_manu | timestamp | Data |

---

## 5. FUNÇÃO

### 5.1 Campos do Modelo (V1.1)
| Campo | Tipo | Observação |
|------|------|------------|
| cd_funcao | PK | Identificador |
| cd_empresa | FK | Empresa |
| cd_grupo | FK | Grupo |
| nm_empresa | string | Nome empresa (relatório) |
| nm_unidade | string | Nome unidade (relatório) |
| nm_grupo | string | Nome grupo (relatório) |
| nm_funcao | string | Nome da função |
| cd_cbo | string | CBO |
| ds_funcao | text | Descrição |
| cd_interno_funcao | string | Código interno |
| nr_funcionario | int | Quantidade de funcionários |
| fl_status | boolean | Ativa / Inativa |
| ds_status | string | Descrição status |
| cd_user_cadm | FK | Usuário cadastro |
| nm_user_cadm | string | Nome |
| ts_user_cadm | timestamp | Data |
| cd_user_manu | FK | Usuário alteração |
| nm_user_manu | string | Nome |
| ts_user_manu | timestamp | Data |

---

## 6. COLABORADOR

### 6.1 Campos do Modelo (V1.1 – consolidados)
| Campo | Tipo | Observação |
|------|------|------------|
| cd_colaborador | PK | Identificador do colaborador |
| nm_colaborador | string | Nome do funcionário |
| matricula | string | Matrícula interna |
| cpf | string | CPF |
| genero | string | Gênero |
| user_email | string | E-mail do usuário (obrigatório, único) |
| user_ativo | boolean | Flag de usuário ativo |
| email_validado_em | timestamp | Data/hora de validação de e-mail |
| cd_grupo | FK | Grupo / Cliente |
| cd_empresa | FK | Empresa / Empregador |
| cd_unidade | FK | Unidade / Estabelecimento |
| cd_setor | FK | Setor |
| cd_funcao | FK | Função |
| ghe_id | FK | GHE (SST) |
| dt_nascimento | date | Data de nascimento |
| dt_admissao | date | Data de admissão |
| fl_status | boolean | Ativo / Inativo |

#### Auditoria
| Campo | Tipo | Observação |
|------|------|------------|
| created_at | timestamp | Data de cadastro |
| created_by | FK | Usuário que cadastrou |
| updated_at | timestamp | Última alteração |
| updated_by | FK | Usuário que alterou |

### 6.2 Campos de EXAMES / AVALIAÇÃO CLÍNICA (IndexMed / Integrações)
> Estes campos **não são cadastrados manualmente** no formulário do colaborador.
> Eles são **importados / atualizados por integração** (IndexMed agora, outros sistemas no futuro).

| Campo | Tipo | Observação |
|------|------|------------|
| cd_avaliacao_clinica | PK | Identificador da avaliação |
| cd_colaborador | FK | Colaborador |
| tipo_exame | string | Tipo de exame (ASO admissional, periódico, retorno, etc.) |
| status_exame | string | Status da avaliação clínica |
| dt_realizacao_exame | date | Data da realização |
| dt_validade_exame | date | Data de validade |
| dt_proximo_exame | date | Próximo exame previsto |
| resultado_exame | string | Apto / Inapto / Restrições |
| observacoes_exame | text | Observações clínicas |
| origem_dado | string | Sistema de origem (IndexMed, manual, etc.) |

### 6.3 Visibilidade no Formulário (V1.1)
**Visíveis:**
- nm_colaborador
- matricula
- cpf
- genero
- user_email
- cd_funcao
- dt_nascimento
- dt_admissao
- fl_status

**Invisíveis / automáticos:**
- cd_grupo, cd_empresa, cd_unidade, cd_setor (derivados da função)
- user_ativo (derivado de política de provisionamento)
- email_validado_em (preenchido pelo fluxo de validação de primeiro acesso)
- dados de exames / avaliação clínica
- campos de auditoria


---

## 7. RELATÓRIOS (VISÃO CONSOLIDADA)

Relatórios devem exibir:
- Todos os campos acima
- Mesmo que estejam vazios
- Sempre via relacionamento (não duplicar dados)

---

## 8. REGRA FINAL DO CORE

- Cadastro: simples
- Modelo: completo
- Relatório: total
- Árvore: relacionamento, não formulário

Este documento é o **contrato oficial da V1.1 do Core**.
Qualquer alteração deve ser registrada aqui antes de ir para o código.
