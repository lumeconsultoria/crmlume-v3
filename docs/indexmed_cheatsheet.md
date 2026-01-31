# Integração IndexMed — Lembrete Rápido (V1)

## Acesso
- Base URL: `https://www.indexmed.com.br/conecta`
- Auth: Basic HTTP (user/pass em `.env`, não versionar)
- Versão swagger: 2.0 (title: IndexMed WebApi, version: V1)

## Endpoints principais (que mapeiam ao nosso Core)
- **Grupo/Empresa/Unidade/Setor/Função**  
  - GET list / GET by id|CNPJ, POST (cadastrar), PUT (atualizar)  
  - Filtros: `cd_grupo`, `cd_empresa`, `nr_cnpj`, `cd_interno_unidade/setor/funcao`, `fl_status (A/I)`
- **Colaborador**  
  - GET list (filtros acima + status A/I/F/T)  
  - GET por `nr_cpf` + `nr_matricula`  
  - POST/PUT via `RequestCadastro/AtualizacaoFuncionario`
- **Esocial / Exames (S-2220)**  
  - GET `Esocial/GetListEventoAso` por `cd_grupo` (+ opcional `cd_empresa`, datas)
- **Arquivo (documentos/recibos)**  
  - GET list, GET por `cd_documento`, POST/PUT para cadastro/atualização
- **Tabelas Complementares** (referências)  
  - CNAE (retorna `nr_grau_risco`), CBO, riscos, tipos de edificação/piso/iluminação/ventilação, estados/cidades, tipos de inscrição.

## Campos essenciais para chaveamento bidirecional
- `cd_interno_unidade`, `cd_interno_setor`, `cd_interno_funcao` (nossos códigos externos)  
- IDs IndexMed: `cd_grupo`, `cd_empresa`, `cd_unidade`, `cd_setor`, `cd_funcao`, `cd_colaborador`, `cd_documento`, `protocolo_origem` (eventos/exames)
- Documentos: `nr_cnpj` / `nr_cpf` sempre como string (até 20 chars)
- Status retornado em letras: `A` (ativo), `I` (inativo), `F` (férias), `T` (afastado) — mapear para `fl_status` + `ds_status` no Core.

## Formatos e padrões
- Datas: ISO-like (`yyyy-MM-ddTHH:mm:ss`) — confirmar ao consumir.
- `fl_status` nas entidades: string de 1 char (A/I).  
- CNAE: 5 dígitos; usar para calcular/validar `nr_grau_risco`.  
- E-mail não faz parte da API; é só do nosso provisionamento interno.

## Regras para ingestão (quando habilitarmos V2)
1) Nunca sobrescrever exames; gravar em `avaliacoes_clinicas` com histórico.  
2) Manter tabela de mapeamento de IDs externos (IndexMed ↔ nossos IDs) ou colunas dedicadas.  
3) Normalizar CNPJ/CPF (somente números) antes de chamadas.  
4) Paginação: verificar limites; se existir, iterar até esgotar.  
5) Logar `request_id`/`protocolo_origem` para auditoria.

## Pendências antes de integrar
- Definir onde persistir `cd_interno_*` e `id_indexmed_*` (modelos ou tabela de mapeamento).  
- Mapear status A/I/F/T para nosso esquema boolean + descrição.  
- Decidir armazenamento de anexos (`cd_documento`/`arquivo_url`) e política de download.  
- Configurar `.env` com credenciais Basic (sem commit).  
- Reaproveitar o pipeline de importação offline para validar dados antes de sincronizar.

