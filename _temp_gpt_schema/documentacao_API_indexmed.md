{
"swagger": "2.0",
"info": {
"version": "V1",
"title": "IndexMed WebApi",
"x-swagger-net-version": "8.3.52.501"
},
"host": "www.indexmed.com.br",
"basePath": "/conecta",
"schemes": [
"https"
],
"paths": {
"/api/Arquivo/GetList": {
"get": {
"tags": [
"Arquivo"
],
"summary": "Lista de Arquivos cadastrados por Usuárioá",
"operationId": "Arquivo_GetList",
"consumes": [],
"produces": [
"application/json",
"text/json",
"text/xml",
"multipart/form-data"
],
"parameters": [
{
"name": "cd_grupo",
"in": "query",
"description": "Código do Grupo no Aplicativo (Opcional: para filtrar por Grupo).",
"required": false,
"type": "integer",
"format": "int32"
},
{
"name": "cd_empresa",
"in": "query",
"description": "Código da Empresa no Aplicativo (Opcional: para filtrar por Empresa).",
"required": false,
"type": "integer",
"format": "int32"
},
{
"name": "cd_unidade",
"in": "query",
"description": "Código da Unidade no Aplicativo (Opcional: para filtrar por Unidade).",
"required": false,
"type": "integer",
"format": "int32"
}
],
"responses": {
"200": {
"description": "Sucesso com retorno do(s) Colaborador(es)",
"schema": {
"items": {
"$ref": "#/definitions/ResponseConsultaArquivo"
              },
              "xml": {
                "name": "ResponseConsultaArquivo",
                "wrapped": true
              },
              "type": "array"
            }
          },
          "204": {
            "description": "Nenhuma informação foi encontrada com os dados enviados",
            "schema": {
              "items": {
                "$ref": "#/definitions/ResponseConsultaArquivo"
},
"xml": {
"name": "ResponseConsultaArquivo",
"wrapped": true
},
"type": "array"
}
},
"400": {
"description": "Solicitação mal formatada"
},
"401": {
"description": "Acesso não autorizado (Solicitação sem autenticação)"
},
"403": {
"description": "Você não tem acesso à esta funcionalidade"
},
"500": {
"description": "Erro interno do servidor"
}
}
}
},
"/api/Arquivo/GetTipos": {
"get": {
"tags": [
"Arquivo"
],
"summary": "Lista Tipos de Documentos",
"operationId": "Arquivo_GetTipos",
"consumes": [],
"produces": [
"application/json",
"text/json",
"text/xml",
"multipart/form-data"
],
"responses": {
"200": {
"description": "Sucesso com retorno do(s) Colaborador(es)",
"schema": {
"items": {
"$ref": "#/definitions/ResponseConsultaArquivoTipo"
              },
              "xml": {
                "name": "ResponseConsultaArquivoTipo",
                "wrapped": true
              },
              "type": "array"
            }
          },
          "204": {
            "description": "Nenhuma informação foi encontrada com os dados enviados",
            "schema": {
              "items": {
                "$ref": "#/definitions/ResponseConsultaArquivoTipo"
},
"xml": {
"name": "ResponseConsultaArquivoTipo",
"wrapped": true
},
"type": "array"
}
},
"400": {
"description": "Solicitação mal formatada"
},
"401": {
"description": "Acesso não autorizado (Solicitação sem autenticação)"
},
"403": {
"description": "Você não tem acesso à esta funcionalidade"
},
"500": {
"description": "Erro interno do servidor"
}
}
}
},
"/api/Arquivo/Get": {
"get": {
"tags": [
"Arquivo"
],
"summary": "Retorna Documento específico cadastrado na Unidade",
"operationId": "Arquivo_Get",
"consumes": [],
"produces": [
"application/json",
"text/json",
"text/xml",
"multipart/form-data"
],
"parameters": [
{
"name": "cd_documento",
"in": "query",
"description": "Código do Documento cadastrado na Unidade (Obrigatório).",
"required": true,
"type": "integer",
"format": "int32"
}
],
"responses": {
"200": {
"description": "Sucesso com retorno do Colaborador",
"schema": {
"$ref": "#/definitions/ResponseConsultaFuncionario"
            }
          },
          "204": {
            "description": "Nenhuma informação foi encontrada com os dados enviados",
            "schema": {
              "$ref": "#/definitions/ResponseConsultaFuncionario"
}
},
"400": {
"description": "Solicitação mal formatada"
},
"401": {
"description": "Acesso não autorizado (Solicitação sem autenticação)"
},
"403": {
"description": "Você não tem acesso à esta funcionalidade"
},
"500": {
"description": "Erro interno do servidor"
}
}
}
},
"/api/Arquivo": {
"put": {
"tags": [
"Arquivo"
],
"summary": "Atualiza um Arquivo na Unidade",
"operationId": "Arquivo_Put",
"consumes": [
"application/json",
"text/json",
"text/xml",
"application/x-www-form-urlencoded",
"multipart/form-data"
],
"produces": [
"application/json",
"text/json",
"text/xml",
"multipart/form-data"
],
"parameters": [
{
"name": "request",
"in": "body",
"required": true,
"schema": {
"$ref": "#/definitions/RequestAtualizacaoArquivo"
}
}
],
"responses": {
"200": {
"description": "Sucesso com retorno do response",
"schema": {
"$ref": "#/definitions/ResponseCadastroArquivo"
            }
          },
          "204": {
            "description": "Nenhuma informação foi encontrada com os dados enviados",
            "schema": {
              "$ref": "#/definitions/ResponseCadastroArquivo"
}
},
"400": {
"description": "Solicitação mal formatada"
},
"401": {
"description": "Acesso não autorizado (Solicitação sem autenticação)"
},
"403": {
"description": "Você não tem acesso à esta funcionalidade"
},
"500": {
"description": "Erro interno do servidor"
}
}
},
"post": {
"tags": [
"Arquivo"
],
"summary": "Cadastra um Arquivo na Unidade",
"operationId": "Arquivo_Post",
"consumes": [
"application/json",
"text/json",
"text/xml",
"application/x-www-form-urlencoded",
"multipart/form-data"
],
"produces": [
"application/json",
"text/json",
"text/xml",
"multipart/form-data"
],
"parameters": [
{
"name": "request",
"in": "body",
"required": true,
"schema": {
"$ref": "#/definitions/RequestCadastroArquivo"
}
}
],
"responses": {
"200": {
"description": "Sucesso com retorno do response",
"schema": {
"$ref": "#/definitions/ResponseCadastroArquivo"
            }
          },
          "204": {
            "description": "Nenhuma informação foi encontrada com os dados enviados",
            "schema": {
              "$ref": "#/definitions/ResponseCadastroArquivo"
}
},
"400": {
"description": "Solicitação mal formatada"
},
"401": {
"description": "Acesso não autorizado (Solicitação sem autenticação)"
},
"403": {
"description": "Você não tem acesso à esta funcionalidade"
},
"500": {
"description": "Erro interno do servidor"
}
}
}
},
"/api/Colaborador/GetList": {
"get": {
"tags": [
"Colaborador"
],
"summary": "Lista de Colaboradores (Funcionários) cadastrados na Assinatura",
"operationId": "Colaborador_GetList",
"consumes": [],
"produces": [
"application/json",
"text/json",
"text/xml",
"multipart/form-data"
],
"parameters": [
{
"name": "cd_grupo",
"in": "query",
"description": "Código do Grupo no Aplicativo Indexmed (Opcional caso envie o CNPJ da empresa, ou seja, ou o código do Grupo ou o CNPJ da empresa precisam ser enviados).",
"required": false,
"type": "integer",
"format": "int32"
},
{
"name": "nrCNPJ",
"in": "query",
"description": "CNPJ da Empresa no Aplicativo Indexmed (Opcional caso envie o código do Grupo: para filtrar por Empresa).",
"required": false,
"type": "string"
},
{
"name": "cd_interno_unidade",
"in": "query",
"description": "Código Interno da Unidade na sua Empresa (Opcional: para filtrar por Unidade) para filtrar com esse campo, é necessário informar também o CNPJ da empresa.",
"required": false,
"type": "string"
},
{
"name": "cd_interno_setor",
"in": "query",
"description": "Código Interno do Setor na sua Empresa (Opcional: para filtrar por Setor) para filtrar com esse campo, é necessário informar também o CNPJ da empresa.",
"required": false,
"type": "string"
},
{
"name": "cd_interno_funcao",
"in": "query",
"description": "Código Interno da Função na sua Empresa (Opcional: para filtrar por Função) para filtrar com esse campo, é necessário informar também o CNPJ da empresa.",
"required": false,
"type": "string"
},
{
"name": "fl_status",
"in": "query",
"description": "Status do funcionário (Opcional: para filtrar por status). Opções válidas são: A,I,F,T (A = Ativo, I = Inativo/Demitido, F = Férias, T = Afastado): .",
"required": false,
"type": "string"
}
],
"responses": {
"200": {
"description": "Sucesso com retorno do(s) Colaborador(es)",
"schema": {
"items": {
"$ref": "#/definitions/ResponseConsultaFuncionario"
              },
              "xml": {
                "name": "ResponseConsultaFuncionario",
                "wrapped": true
              },
              "type": "array"
            }
          },
          "204": {
            "description": "Nenhuma informação foi encontrada com os dados enviados",
            "schema": {
              "items": {
                "$ref": "#/definitions/ResponseConsultaFuncionario"
},
"xml": {
"name": "ResponseConsultaFuncionario",
"wrapped": true
},
"type": "array"
}
},
"400": {
"description": "Solicitação mal formatada"
},
"401": {
"description": "Acesso não autorizado (Solicitação sem autenticação)"
},
"403": {
"description": "Você não tem acesso à esta funcionalidade"
},
"500": {
"description": "Erro interno do servidor"
}
}
}
},
"/api/Colaborador/Get": {
"get": {
"tags": [
"Colaborador"
],
"summary": "Retorna os dados de um Colaborador (Funcionário) específico cadastrado na Assinatura",
"operationId": "Colaborador_Get",
"consumes": [],
"produces": [
"application/json",
"text/json",
"text/xml",
"multipart/form-data"
],
"parameters": [
{
"name": "nr_cpf",
"in": "query",
"description": "CPF do Funcionário na sua Empresa (Obrigatório).",
"required": true,
"type": "string"
},
{
"name": "nr_matricula",
"in": "query",
"description": "Número da Matrícula do Funcionário na sua Empresa (Obrigatório).",
"required": true,
"type": "string"
}
],
"responses": {
"200": {
"description": "Sucesso com retorno do Colaborador",
"schema": {
"$ref": "#/definitions/ResponseConsultaFuncionario"
            }
          },
          "204": {
            "description": "Nenhuma informação foi encontrada com os dados enviados",
            "schema": {
              "$ref": "#/definitions/ResponseConsultaFuncionario"
}
},
"400": {
"description": "Solicitação mal formatada"
},
"401": {
"description": "Acesso não autorizado (Solicitação sem autenticação)"
},
"403": {
"description": "Você não tem acesso à esta funcionalidade"
},
"500": {
"description": "Erro interno do servidor"
}
}
}
},
"/api/Colaborador": {
"put": {
"tags": [
"Colaborador"
],
"summary": "Atualiza um Colaborador na empresa",
"operationId": "Colaborador_Put",
"consumes": [
"application/json",
"text/json",
"text/xml",
"application/x-www-form-urlencoded",
"multipart/form-data"
],
"produces": [
"application/json",
"text/json",
"text/xml",
"multipart/form-data"
],
"parameters": [
{
"name": "request",
"in": "body",
"required": true,
"schema": {
"$ref": "#/definitions/RequestAtualizacaoFuncionario"
}
}
],
"responses": {
"200": {
"description": "Sucesso com retorno do response",
"schema": {
"$ref": "#/definitions/ResponseCadastroFuncionario"
            }
          },
          "204": {
            "description": "Nenhuma informação foi encontrada com os dados enviados",
            "schema": {
              "$ref": "#/definitions/ResponseCadastroFuncionario"
}
},
"400": {
"description": "Solicitação mal formatada"
},
"401": {
"description": "Acesso não autorizado (Solicitação sem autenticação)"
},
"403": {
"description": "Você não tem acesso à esta funcionalidade"
},
"500": {
"description": "Erro interno do servidor"
}
}
},
"post": {
"tags": [
"Colaborador"
],
"summary": "Cadastra um Colaborador na empresa",
"operationId": "Colaborador_Post",
"consumes": [
"application/json",
"text/json",
"text/xml",
"application/x-www-form-urlencoded",
"multipart/form-data"
],
"produces": [
"application/json",
"text/json",
"text/xml",
"multipart/form-data"
],
"parameters": [
{
"name": "request",
"in": "body",
"required": true,
"schema": {
"$ref": "#/definitions/RequestCadastroFuncionario"
}
}
],
"responses": {
"200": {
"description": "Sucesso com retorno do response",
"schema": {
"$ref": "#/definitions/ResponseCadastroFuncionario"
            }
          },
          "204": {
            "description": "Nenhuma informação foi encontrada com os dados enviados",
            "schema": {
              "$ref": "#/definitions/ResponseCadastroFuncionario"
}
},
"400": {
"description": "Solicitação mal formatada"
},
"401": {
"description": "Acesso não autorizado (Solicitação sem autenticação)"
},
"403": {
"description": "Você não tem acesso à esta funcionalidade"
},
"500": {
"description": "Erro interno do servidor"
}
}
}
},
"/api/Empresa/GetList": {
"get": {
"tags": [
"Empresa"
],
"summary": "Lista de Empresas cadastradas no Grupo",
"operationId": "Empresa_GetList",
"consumes": [],
"produces": [
"application/json",
"text/json",
"text/xml",
"multipart/form-data"
],
"parameters": [
{
"name": "idGrupo",
"in": "query",
"description": "Código do Grupo no Aplicativo (Opcional: para filtrar por Grupo de Empresas). Formato: (integer).",
"required": false,
"type": "integer",
"format": "int32"
},
{
"name": "nmGrupo",
"in": "query",
"description": "Nome do Grupo no Aplicativo (Opcional: para filtrar por Grupo de Empresas). Formato: (string). Máx. de 100 caracteres.",
"required": false,
"type": "string"
}
],
"responses": {
"200": {
"description": "Sucesso com retorno da(s) Empresa(s)",
"schema": {
"items": {
"$ref": "#/definitions/ResponseConsultaEmpresa"
              },
              "xml": {
                "name": "ResponseConsultaEmpresa",
                "wrapped": true
              },
              "type": "array"
            }
          },
          "204": {
            "description": "Nenhuma informação foi encontrada com os dados enviados",
            "schema": {
              "items": {
                "$ref": "#/definitions/ResponseConsultaEmpresa"
},
"xml": {
"name": "ResponseConsultaEmpresa",
"wrapped": true
},
"type": "array"
}
},
"400": {
"description": "Solicitação mal formatada"
},
"401": {
"description": "Acesso não autorizado (Solicitação sem autenticação)"
},
"403": {
"description": "Você não tem acesso à esta funcionalidade"
},
"500": {
"description": "Erro interno do servidor"
}
}
}
},
"/api/Empresa/Get": {
"get": {
"tags": [
"Empresa"
],
"summary": "Retorna os dados de uma Empresa específica cadastrada no Grupo",
"operationId": "Empresa_Get",
"consumes": [],
"produces": [
"application/json",
"text/json",
"text/xml",
"multipart/form-data"
],
"parameters": [
{
"name": "cd_empresa",
"in": "query",
"description": "Código da Empresa no Aplicativo (Obrigatório se não enviar o CNPJ). Formato: (integer).",
"required": false,
"type": "integer",
"format": "int32"
},
{
"name": "nr_cnpj",
"in": "query",
"description": "CNPJ da Empresa no Aplicativo (Obrigatório se não enviar o Código da Empresa). Formato: (string).",
"required": false,
"type": "string",
"default": ""
}
],
"responses": {
"200": {
"description": "Sucesso com retorno da Empresa",
"schema": {
"$ref": "#/definitions/ResponseConsultaEmpresa"
            }
          },
          "204": {
            "description": "Nenhuma informação foi encontrada com os dados enviados",
            "schema": {
              "$ref": "#/definitions/ResponseConsultaEmpresa"
}
},
"400": {
"description": "Solicitação mal formatada"
},
"401": {
"description": "Acesso não autorizado (Solicitação sem autenticação)"
},
"403": {
"description": "Você não tem acesso à esta funcionalidade"
},
"500": {
"description": "Erro interno do servidor"
}
}
}
},
"/api/Empresa": {
"put": {
"tags": [
"Empresa"
],
"summary": "Atualiza os dados de uma Empresa específica cadastrada no Grupo",
"operationId": "Empresa_Put",
"consumes": [
"application/json",
"text/json",
"text/xml",
"application/x-www-form-urlencoded",
"multipart/form-data"
],
"produces": [
"application/json",
"text/json",
"text/xml",
"multipart/form-data"
],
"parameters": [
{
"name": "request",
"in": "body",
"required": true,
"schema": {
"$ref": "#/definitions/RequestAtualizacaoEmpresa"
}
}
],
"responses": {
"200": {
"description": "Sucesso com retorno do response",
"schema": {
"$ref": "#/definitions/ResponseCadastroEmpresa"
            }
          },
          "204": {
            "description": "Nenhuma informação foi encontrada com os dados enviados",
            "schema": {
              "$ref": "#/definitions/ResponseCadastroEmpresa"
}
},
"400": {
"description": "Solicitação mal formatada"
},
"401": {
"description": "Acesso não autorizado (Solicitação sem autenticação)"
},
"403": {
"description": "Você não tem acesso à esta funcionalidade"
},
"500": {
"description": "Erro interno do servidor"
}
}
},
"post": {
"tags": [
"Empresa"
],
"summary": "Cadastra uma Empresa no Grupo",
"operationId": "Empresa_Post",
"consumes": [
"application/json",
"text/json",
"text/xml",
"application/x-www-form-urlencoded",
"multipart/form-data"
],
"produces": [
"application/json",
"text/json",
"text/xml",
"multipart/form-data"
],
"parameters": [
{
"name": "request",
"in": "body",
"required": true,
"schema": {
"$ref": "#/definitions/RequestCadastroEmpresa"
}
}
],
"responses": {
"200": {
"description": "Sucesso com retorno do response",
"schema": {
"$ref": "#/definitions/ResponseCadastroEmpresa"
            }
          },
          "204": {
            "description": "Nenhuma informação foi encontrada com os dados enviados",
            "schema": {
              "$ref": "#/definitions/ResponseCadastroEmpresa"
}
},
"400": {
"description": "Solicitação mal formatada"
},
"401": {
"description": "Acesso não autorizado (Solicitação sem autenticação)"
},
"403": {
"description": "Você não tem acesso à esta funcionalidade"
},
"500": {
"description": "Erro interno do servidor"
}
}
}
},
"/api/Esocial/GetListEventoAso": {
"get": {
"tags": [
"Esocial"
],
"summary": "Lista de Eventos do ASO (S-2220: Monitoramento da Saúde do Trabalhador) dos Funcionários cadastrados na Assinatura",
"operationId": "Esocial_GetListEventoAso",
"consumes": [],
"produces": [
"application/json",
"text/json",
"text/xml",
"multipart/form-data"
],
"parameters": [
{
"name": "cd_grupo",
"in": "query",
"description": "Código do Grupo/Cliente no Aplicativo (Obrigatório)",
"required": true,
"type": "integer",
"format": "int32"
},
{
"name": "cd_empresa",
"in": "query",
"description": "Código da Empresa no Aplicativo (Opcional: para filtrar por Empresa).",
"required": false,
"type": "integer",
"format": "int32"
},
{
"name": "dt_inicio",
"in": "query",
"description": "Data de corte inicial para a busca (Opcional)",
"required": false,
"type": "string",
"format": "date-time"
},
{
"name": "dt_final",
"in": "query",
"description": "Data de corte final para a busca (Opcional)",
"required": false,
"type": "string",
"format": "date-time"
}
],
"responses": {
"200": {
"description": "Sucesso com retorno da lista de eventos",
"schema": {
"items": {
"$ref": "#/definitions/EventoAsoEsocial"
              },
              "xml": {
                "name": "EventoAsoEsocial",
                "wrapped": true
              },
              "type": "array"
            }
          },
          "204": {
            "description": "Nenhuma informação foi encontrada com os dados enviados",
            "schema": {
              "items": {
                "$ref": "#/definitions/EventoAsoEsocial"
},
"xml": {
"name": "EventoAsoEsocial",
"wrapped": true
},
"type": "array"
}
},
"400": {
"description": "Solicitação mal formatada"
},
"401": {
"description": "Acesso não autorizado (Solicitação sem autenticação)"
},
"403": {
"description": "Você não tem acesso à esta funcionalidade"
},
"500": {
"description": "Erro interno do servidor"
}
}
}
},
"/api/Esocial/GetEventoAsoPorFuncionario": {
"get": {
"tags": [
"Esocial"
],
"summary": "Lista de Eventos do ASO (S-2220: Monitoramento da Saúde do Trabalhador) de um Funcionário específico cadastrado na Assinatura",
"operationId": "Esocial_GetEventoAsoPorFuncionario",
"consumes": [],
"produces": [
"application/json",
"text/json",
"text/xml",
"multipart/form-data"
],
"parameters": [
{
"name": "nr_cpf",
"in": "query",
"description": "CPF do Funcionário na sua Empresa (Obrigatório).",
"required": true,
"type": "string"
},
{
"name": "dt_inicio",
"in": "query",
"description": "Data de corte inicial para a busca (Opcional)",
"required": false,
"type": "string",
"format": "date-time"
},
{
"name": "dt_final",
"in": "query",
"description": "Data de corte final para a busca (Opcional)",
"required": false,
"type": "string",
"format": "date-time"
}
],
"responses": {
"200": {
"description": "Sucesso com retorno da lista de eventos",
"schema": {
"items": {
"$ref": "#/definitions/EventoAsoEsocial"
              },
              "xml": {
                "name": "EventoAsoEsocial",
                "wrapped": true
              },
              "type": "array"
            }
          },
          "204": {
            "description": "Nenhuma informação foi encontrada com os dados enviados",
            "schema": {
              "items": {
                "$ref": "#/definitions/EventoAsoEsocial"
},
"xml": {
"name": "EventoAsoEsocial",
"wrapped": true
},
"type": "array"
}
},
"400": {
"description": "Solicitação mal formatada"
},
"401": {
"description": "Acesso não autorizado (Solicitação sem autenticação)"
},
"403": {
"description": "Você não tem acesso à esta funcionalidade"
},
"500": {
"description": "Erro interno do servidor"
}
}
}
},
"/api/Esocial/GetListEventoFatoresRisco": {
"get": {
"tags": [
"Esocial"
],
"summary": "Lista de Eventos de Fatores de Risco (S-2240: Condições Ambientais de Trabalho) dos Funcionários cadastrados na Assinatura",
"operationId": "Esocial_GetListEventoFatoresRisco",
"consumes": [],
"produces": [
"application/json",
"text/json",
"text/xml",
"multipart/form-data"
],
"parameters": [
{
"name": "cd_grupo",
"in": "query",
"description": "Código do Grupo/Cliente no Aplicativo (Obrigatório)",
"required": true,
"type": "integer",
"format": "int32"
},
{
"name": "nrCNPJ",
"in": "query",
"description": "CNPJ da Empresa no Aplicativo (Opcional: para filtrar por Empresa).",
"required": false,
"type": "string"
},
{
"name": "dt_inicio",
"in": "query",
"description": "Data de corte inicial para a busca (Opcional)",
"required": false,
"type": "string",
"format": "date-time"
},
{
"name": "dt_final",
"in": "query",
"description": "Data de corte final para a busca (Opcional)",
"required": false,
"type": "string",
"format": "date-time"
}
],
"responses": {
"200": {
"description": "Sucesso com retorno da lista de eventos",
"schema": {
"items": {
"$ref": "#/definitions/EventoFatoresRiscoEsocial"
              },
              "xml": {
                "name": "EventoFatoresRiscoEsocial",
                "wrapped": true
              },
              "type": "array"
            }
          },
          "204": {
            "description": "Nenhuma informação foi encontrada com os dados enviados",
            "schema": {
              "items": {
                "$ref": "#/definitions/EventoFatoresRiscoEsocial"
},
"xml": {
"name": "EventoFatoresRiscoEsocial",
"wrapped": true
},
"type": "array"
}
},
"400": {
"description": "Solicitação mal formatada"
},
"401": {
"description": "Acesso não autorizado (Solicitação sem autenticação)"
},
"403": {
"description": "Você não tem acesso à esta funcionalidade"
},
"500": {
"description": "Erro interno do servidor"
}
}
}
},
"/api/Esocial/GetListEventoFatoresRiscoPorFuncionario": {
"get": {
"tags": [
"Esocial"
],
"summary": "Lista de Eventos de Fatores de Risco (S-2240: Condições Ambientais de Trabalho) de um Funcionário específico cadastrado na Assinatura",
"operationId": "Esocial_GetListEventoFatoresRiscoPorFuncionario",
"consumes": [],
"produces": [
"application/json",
"text/json",
"text/xml",
"multipart/form-data"
],
"parameters": [
{
"name": "nr_cpf",
"in": "query",
"description": "CPF do Funcionário na sua Empresa (Obrigatório).",
"required": true,
"type": "string"
},
{
"name": "dt_inicio",
"in": "query",
"description": "Data de corte inicial para a busca (Opcional)",
"required": false,
"type": "string",
"format": "date-time"
},
{
"name": "dt_final",
"in": "query",
"description": "Data de corte final para a busca (Opcional)",
"required": false,
"type": "string",
"format": "date-time"
}
],
"responses": {
"200": {
"description": "Sucesso com retorno da lista de eventos",
"schema": {
"items": {
"$ref": "#/definitions/EventoFatoresRiscoEsocial"
              },
              "xml": {
                "name": "EventoFatoresRiscoEsocial",
                "wrapped": true
              },
              "type": "array"
            }
          },
          "204": {
            "description": "Nenhuma informação foi encontrada com os dados enviados",
            "schema": {
              "items": {
                "$ref": "#/definitions/EventoFatoresRiscoEsocial"
},
"xml": {
"name": "EventoFatoresRiscoEsocial",
"wrapped": true
},
"type": "array"
}
},
"400": {
"description": "Solicitação mal formatada"
},
"401": {
"description": "Acesso não autorizado (Solicitação sem autenticação)"
},
"403": {
"description": "Você não tem acesso à esta funcionalidade"
},
"500": {
"description": "Erro interno do servidor"
}
}
}
},
"/api/Esocial/GetListEventoCat": {
"get": {
"tags": [
"Esocial"
],
"summary": "Lista de Eventos de CAT (S-2210: Comunicação de Acidente de Trabalho) dos Funcionários cadastrados na Assinatura",
"operationId": "Esocial_GetListEventoCat",
"consumes": [],
"produces": [
"application/json",
"text/json",
"text/xml",
"multipart/form-data"
],
"parameters": [
{
"name": "cd_grupo",
"in": "query",
"description": "Código do Grupo/Cliente no Aplicativo (Obrigatório)",
"required": true,
"type": "integer",
"format": "int32"
},
{
"name": "nrCNPJ",
"in": "query",
"description": "CNPJ da Empresa no Aplicativo (Opcional: para filtrar por Empresa).",
"required": false,
"type": "string"
},
{
"name": "dt_inicio",
"in": "query",
"description": "Data de corte inicial para a busca (Opcional)",
"required": false,
"type": "string",
"format": "date-time"
},
{
"name": "dt_final",
"in": "query",
"description": "Data de corte final para a busca (Opcional)",
"required": false,
"type": "string",
"format": "date-time"
}
],
"responses": {
"200": {
"description": "Sucesso com retorno da lista de eventos",
"schema": {
"items": {
"$ref": "#/definitions/EventoCatEsocial"
              },
              "xml": {
                "name": "EventoCatEsocial",
                "wrapped": true
              },
              "type": "array"
            }
          },
          "204": {
            "description": "Nenhuma informação foi encontrada com os dados enviados",
            "schema": {
              "items": {
                "$ref": "#/definitions/EventoCatEsocial"
},
"xml": {
"name": "EventoCatEsocial",
"wrapped": true
},
"type": "array"
}
},
"400": {
"description": "Solicitação mal formatada"
},
"401": {
"description": "Acesso não autorizado (Solicitação sem autenticação)"
},
"403": {
"description": "Você não tem acesso à esta funcionalidade"
},
"500": {
"description": "Erro interno do servidor"
}
}
}
},
"/api/Esocial/GetListEventoCatPorFuncionario": {
"get": {
"tags": [
"Esocial"
],
"summary": "Lista de Eventos de CAT (S-2210: Comunicação de Acidente de Trabalho) de um Funcionário específico cadastrado na Assinatura",
"operationId": "Esocial_GetListEventoCatPorFuncionario",
"consumes": [],
"produces": [
"application/json",
"text/json",
"text/xml",
"multipart/form-data"
],
"parameters": [
{
"name": "nr_cpf",
"in": "query",
"description": "CPF do Funcionário na sua Empresa (Obrigatório).",
"required": true,
"type": "string"
},
{
"name": "dt_inicio",
"in": "query",
"description": "Data de corte inicial para a busca (Opcional)",
"required": false,
"type": "string",
"format": "date-time"
},
{
"name": "dt_final",
"in": "query",
"description": "Data de corte final para a busca (Opcional)",
"required": false,
"type": "string",
"format": "date-time"
}
],
"responses": {
"200": {
"description": "Sucesso com retorno da lista de eventos",
"schema": {
"items": {
"$ref": "#/definitions/EventoCatEsocial"
              },
              "xml": {
                "name": "EventoCatEsocial",
                "wrapped": true
              },
              "type": "array"
            }
          },
          "204": {
            "description": "Nenhuma informação foi encontrada com os dados enviados",
            "schema": {
              "items": {
                "$ref": "#/definitions/EventoCatEsocial"
},
"xml": {
"name": "EventoCatEsocial",
"wrapped": true
},
"type": "array"
}
},
"400": {
"description": "Solicitação mal formatada"
},
"401": {
"description": "Acesso não autorizado (Solicitação sem autenticação)"
},
"403": {
"description": "Você não tem acesso à esta funcionalidade"
},
"500": {
"description": "Erro interno do servidor"
}
}
}
},
"/api/Funcao/GetList": {
"get": {
"tags": [
"Funcao"
],
"summary": "Lista de Funcões (Cargos) cadastradas na Assinatura",
"operationId": "Funcao_GetList",
"consumes": [],
"produces": [
"application/json",
"text/json",
"text/xml",
"multipart/form-data"
],
"parameters": [
{
"name": "cd_empresa",
"in": "query",
"description": "Código da Empresa no Aplicativo (Opcional: para filtrar por Empresa).",
"required": false,
"type": "integer",
"format": "int32"
},
{
"name": "nr_cnpj_empresa",
"in": "query",
"description": "CNPJ da Empresa no Aplicativo (Opcional: para filtrar por Empresa).",
"required": false,
"type": "string"
}
],
"responses": {
"200": {
"description": "Sucesso com retorno da(s) Função(ões)",
"schema": {
"items": {
"$ref": "#/definitions/ResponseConsultaFuncao"
              },
              "xml": {
                "name": "ResponseConsultaFuncao",
                "wrapped": true
              },
              "type": "array"
            }
          },
          "204": {
            "description": "Nenhuma informação foi encontrada com os dados enviados",
            "schema": {
              "items": {
                "$ref": "#/definitions/ResponseConsultaFuncao"
},
"xml": {
"name": "ResponseConsultaFuncao",
"wrapped": true
},
"type": "array"
}
},
"400": {
"description": "Solicitação mal formatada"
},
"401": {
"description": "Acesso não autorizado (Solicitação sem autenticação)"
},
"403": {
"description": "Você não tem acesso à esta funcionalidade"
},
"500": {
"description": "Erro interno do servidor"
}
}
}
},
"/api/Funcao/Get": {
"get": {
"tags": [
"Funcao"
],
"summary": "Retorna os dados de uma Função (Cargo) específica cadastrada na Assinatura",
"operationId": "Funcao_Get",
"consumes": [],
"produces": [
"application/json",
"text/json",
"text/xml",
"multipart/form-data"
],
"parameters": [
{
"name": "cd_funcao",
"in": "query",
"description": "Código da Função na Empresa. Formato: (integer). Obrigatório se não for enviado o cd_interno_setor e cd_empresa.",
"required": false,
"type": "integer",
"format": "int32"
},
{
"name": "cd_interno_funcao",
"in": "query",
"description": "Código Interno da Função na sua Empresa.Formato: (string). Máx. de 20 caracteres. Obrigatório se não for enviado o cd_setor e se for enviado cd_empresa ou nr_cnpj.",
"required": false,
"type": "string",
"default": ""
},
{
"name": "cd_empresa",
"in": "query",
"description": "Código da Empresa no Aplicativo. Formato: (integer). Obrigatório se for enviado o cd_interno_setor e não for enviado o nr_cnpj.",
"required": false,
"type": "integer",
"format": "int32"
},
{
"name": "nr_cnpj",
"in": "query",
"description": "CNPJ da Empresa. Formato: (string). Sem máscaras. Máx. de 20 caracteres. Obrigatório se for enviado o cd_interno_setor e não for enviado o cd_empresa.",
"required": false,
"type": "string",
"default": ""
}
],
"responses": {
"200": {
"description": "Sucesso com retorno da Função",
"schema": {
"$ref": "#/definitions/ResponseConsultaFuncao"
            }
          },
          "204": {
            "description": "Nenhuma informação foi encontrada com os dados enviados",
            "schema": {
              "$ref": "#/definitions/ResponseConsultaFuncao"
}
},
"400": {
"description": "Solicitação mal formatada"
},
"401": {
"description": "Acesso não autorizado (Solicitação sem autenticação)"
},
"403": {
"description": "Você não tem acesso à esta funcionalidade"
},
"500": {
"description": "Erro interno do servidor"
}
}
}
},
"/api/Funcao": {
"put": {
"tags": [
"Funcao"
],
"summary": "Atualiza os dados de uma Função (Cargo) específico na Assinatura",
"operationId": "Funcao_Put",
"consumes": [
"application/json",
"text/json",
"text/xml",
"application/x-www-form-urlencoded",
"multipart/form-data"
],
"produces": [
"application/json",
"text/json",
"text/xml",
"multipart/form-data"
],
"parameters": [
{
"name": "request",
"in": "body",
"required": true,
"schema": {
"$ref": "#/definitions/RequestAtualizacaoFuncao"
}
}
],
"responses": {
"200": {
"description": "Sucesso com retorno do response",
"schema": {
"$ref": "#/definitions/ResponseCadastroFuncao"
            }
          },
          "204": {
            "description": "Nenhuma informação foi encontrada com os dados enviados",
            "schema": {
              "$ref": "#/definitions/ResponseCadastroFuncao"
}
},
"400": {
"description": "Solicitação mal formatada"
},
"401": {
"description": "Acesso não autorizado (Solicitação sem autenticação)"
},
"403": {
"description": "Você não tem acesso à esta funcionalidade"
},
"500": {
"description": "Erro interno do servidor"
}
}
},
"post": {
"tags": [
"Funcao"
],
"summary": "Cadastra um Função (Cargo) específico na Assinatura",
"operationId": "Funcao_Post",
"consumes": [
"application/json",
"text/json",
"text/xml",
"application/x-www-form-urlencoded",
"multipart/form-data"
],
"produces": [
"application/json",
"text/json",
"text/xml",
"multipart/form-data"
],
"parameters": [
{
"name": "request",
"in": "body",
"required": true,
"schema": {
"$ref": "#/definitions/RequestCadastroFuncao"
}
}
],
"responses": {
"200": {
"description": "Sucesso com retorno do response",
"schema": {
"$ref": "#/definitions/ResponseCadastroFuncao"
            }
          },
          "204": {
            "description": "Nenhuma informação foi encontrada com os dados enviados",
            "schema": {
              "$ref": "#/definitions/ResponseCadastroFuncao"
}
},
"400": {
"description": "Solicitação mal formatada"
},
"401": {
"description": "Acesso não autorizado (Solicitação sem autenticação)"
},
"403": {
"description": "Você não tem acesso à esta funcionalidade"
},
"500": {
"description": "Erro interno do servidor"
}
}
}
},
"/api/Grupo/GetList": {
"get": {
"tags": [
"Grupo"
],
"summary": "Lista de Grupos cadastrados na Assinatura",
"operationId": "Grupo_GetList",
"consumes": [],
"produces": [
"application/json",
"text/json",
"text/xml",
"multipart/form-data"
],
"responses": {
"200": {
"description": "Sucesso com retorno do(s) Grupo(s)",
"schema": {
"items": {
"$ref": "#/definitions/ResponseConsultaGrupo"
              },
              "xml": {
                "name": "ResponseConsultaGrupo",
                "wrapped": true
              },
              "type": "array"
            }
          },
          "204": {
            "description": "Nenhuma informação foi encontrada com os dados enviados",
            "schema": {
              "items": {
                "$ref": "#/definitions/ResponseConsultaGrupo"
},
"xml": {
"name": "ResponseConsultaGrupo",
"wrapped": true
},
"type": "array"
}
},
"400": {
"description": "Solicitação mal formatada"
},
"401": {
"description": "Acesso não autorizado (Solicitação sem autenticação)"
},
"403": {
"description": "Você não tem acesso à esta funcionalidade"
},
"500": {
"description": "Erro interno do servidor"
}
}
}
},
"/api/Grupo/Get": {
"get": {
"tags": [
"Grupo"
],
"summary": "Retorna os dados de um Grupo específico cadastrado na Assinatura",
"operationId": "Grupo_Get",
"consumes": [],
"produces": [
"application/json",
"text/json",
"text/xml",
"multipart/form-data"
],
"parameters": [
{
"name": "nm_grupo",
"in": "query",
"description": "Nome do Grupo/Cliente no Aplicativo Indexmed (Obrigatório). Formato: (string). Máx. de 100 caracteres.",
"required": true,
"type": "string"
}
],
"responses": {
"200": {
"description": "Sucesso com retorno do Grupo",
"schema": {
"$ref": "#/definitions/ResponseConsultaGrupo"
            }
          },
          "204": {
            "description": "Nenhuma informação foi encontrada com os dados enviados",
            "schema": {
              "$ref": "#/definitions/ResponseConsultaGrupo"
}
},
"400": {
"description": "Solicitação mal formatada"
},
"401": {
"description": "Acesso não autorizado (Solicitação sem autenticação)"
},
"403": {
"description": "Você não tem acesso à esta funcionalidade"
},
"500": {
"description": "Erro interno do servidor"
}
}
}
},
"/api/Grupo": {
"put": {
"tags": [
"Grupo"
],
"summary": "Atualiza os dados de um Grupo específico cadastrado na assinatura",
"operationId": "Grupo_Put",
"consumes": [
"application/json",
"text/json",
"text/xml",
"application/x-www-form-urlencoded",
"multipart/form-data"
],
"produces": [
"application/json",
"text/json",
"text/xml",
"multipart/form-data"
],
"parameters": [
{
"name": "request",
"in": "body",
"required": true,
"schema": {
"$ref": "#/definitions/RequestAtualizacaoGrupo"
}
}
],
"responses": {
"200": {
"description": "Sucesso com retorno do response",
"schema": {
"$ref": "#/definitions/ResponseCadastroGrupo"
            }
          },
          "204": {
            "description": "Nenhuma informação foi encontrada com os dados enviados",
            "schema": {
              "$ref": "#/definitions/ResponseCadastroGrupo"
}
},
"400": {
"description": "Solicitação mal formatada"
},
"401": {
"description": "Acesso não autorizado (Solicitação sem autenticação)"
},
"403": {
"description": "Você não tem acesso à esta funcionalidade"
},
"500": {
"description": "Erro interno do servidor"
}
}
},
"post": {
"tags": [
"Grupo"
],
"summary": "Cadastra um Grupo na assinatura",
"operationId": "Grupo_Post",
"consumes": [
"application/json",
"text/json",
"text/xml",
"application/x-www-form-urlencoded",
"multipart/form-data"
],
"produces": [
"application/json",
"text/json",
"text/xml",
"multipart/form-data"
],
"parameters": [
{
"name": "request",
"in": "body",
"required": true,
"schema": {
"$ref": "#/definitions/RequestCadastroGrupo"
}
}
],
"responses": {
"200": {
"description": "Sucesso com retorno do response",
"schema": {
"$ref": "#/definitions/ResponseCadastroGrupo"
            }
          },
          "204": {
            "description": "Nenhuma informação foi encontrada com os dados enviados",
            "schema": {
              "$ref": "#/definitions/ResponseCadastroGrupo"
}
},
"400": {
"description": "Solicitação mal formatada"
},
"401": {
"description": "Acesso não autorizado (Solicitação sem autenticação)"
},
"403": {
"description": "Você não tem acesso à esta funcionalidade"
},
"500": {
"description": "Erro interno do servidor"
}
}
}
},
"/api/ImportarFichaClinica": {
"post": {
"tags": [
"ImportarFichaClinica"
],
"summary": "Informar Exame realizado",
"operationId": "ImportarFichaClinica_Post",
"consumes": [
"application/json",
"text/json",
"text/xml",
"application/x-www-form-urlencoded",
"multipart/form-data"
],
"produces": [
"application/json",
"text/json",
"text/xml",
"multipart/form-data"
],
"parameters": [
{
"name": "request",
"in": "body",
"required": true,
"schema": {
"$ref": "#/definitions/RequestImportarFichaClinica"
}
}
],
"responses": {
"200": {
"description": "Sucesso com retorno do response",
"schema": {
"$ref": "#/definitions/ResponseImportarFichaClinica"
            }
          },
          "204": {
            "description": "Nenhuma informação foi encontrada com os dados enviados",
            "schema": {
              "$ref": "#/definitions/ResponseImportarFichaClinica"
}
},
"400": {
"description": "Solicitação mal formatada"
},
"401": {
"description": "Acesso não autorizado (Solicitação sem autenticação)"
},
"403": {
"description": "Você não tem acesso à esta funcionalidade"
},
"500": {
"description": "Erro interno do servidor"
}
}
}
},
"/api/ImportarLoteEventos": {
"post": {
"tags": [
"ImportarLoteEventos"
],
"summary": "Informar Exame realizado",
"operationId": "ImportarLoteEventos_Post",
"consumes": [
"application/json",
"text/json",
"text/xml",
"application/x-www-form-urlencoded",
"multipart/form-data"
],
"produces": [
"application/json",
"text/json",
"text/xml",
"multipart/form-data"
],
"parameters": [
{
"name": "request",
"in": "body",
"required": true,
"schema": {
"$ref": "#/definitions/RequestImportarLoteEventos"
}
}
],
"responses": {
"200": {
"description": "Sucesso com retorno do response",
"schema": {
"$ref": "#/definitions/ResponseImportarLoteEventos"
            }
          },
          "204": {
            "description": "Nenhuma informação foi encontrada com os dados enviados",
            "schema": {
              "$ref": "#/definitions/ResponseImportarLoteEventos"
}
},
"400": {
"description": "Solicitação mal formatada"
},
"401": {
"description": "Acesso não autorizado (Solicitação sem autenticação)"
},
"403": {
"description": "Você não tem acesso à esta funcionalidade"
},
"500": {
"description": "Erro interno do servidor"
}
}
}
},
"/api/InformarExames": {
"post": {
"tags": [
"InformarExames"
],
"summary": "Informar Exame realizado",
"operationId": "InformarExames_Post",
"consumes": [
"application/json",
"text/json",
"text/xml",
"application/x-www-form-urlencoded",
"multipart/form-data"
],
"produces": [
"application/json",
"text/json",
"text/xml",
"multipart/form-data"
],
"parameters": [
{
"name": "request",
"in": "body",
"required": true,
"schema": {
"$ref": "#/definitions/RequestInformarExames"
}
}
],
"responses": {
"200": {
"description": "Sucesso com retorno do response",
"schema": {
"$ref": "#/definitions/ResponseInformarExames"
            }
          },
          "204": {
            "description": "Nenhuma informação foi encontrada com os dados enviados",
            "schema": {
              "$ref": "#/definitions/ResponseInformarExames"
}
},
"400": {
"description": "Solicitação mal formatada"
},
"401": {
"description": "Acesso não autorizado (Solicitação sem autenticação)"
},
"403": {
"description": "Você não tem acesso à esta funcionalidade"
},
"500": {
"description": "Erro interno do servidor"
}
}
}
},
"/api/MedicoCoordenador/GetList": {
"get": {
"tags": [
"MedicoCoordenador"
],
"summary": "Lista de Colaboradores (Funcionários) cadastrados na Assinatura",
"operationId": "MedicoCoordenador_GetList",
"consumes": [],
"produces": [
"application/json",
"text/json",
"text/xml",
"multipart/form-data"
],
"parameters": [
{
"name": "cd_grupo",
"in": "query",
"description": "Código do Grupo (Opcional: para filtrar por Função).",
"required": false,
"type": "integer",
"format": "int32"
},
{
"name": "nmGrupo",
"in": "query",
"description": "Nome do Grupo no Aplicativo (Opcional: para filtrar por Grupo de Empresas).",
"required": false,
"type": "string"
}
],
"responses": {
"200": {
"description": "Sucesso com retorno do(s) Medico(s) Coordenador(es)",
"schema": {
"items": {
"$ref": "#/definitions/ResponseConsultaMedicoCoordenador"
              },
              "xml": {
                "name": "ResponseConsultaMedicoCoordenador",
                "wrapped": true
              },
              "type": "array"
            }
          },
          "204": {
            "description": "Nenhuma informação foi encontrada com os dados enviados",
            "schema": {
              "items": {
                "$ref": "#/definitions/ResponseConsultaMedicoCoordenador"
},
"xml": {
"name": "ResponseConsultaMedicoCoordenador",
"wrapped": true
},
"type": "array"
}
},
"400": {
"description": "Solicitação mal formatada"
},
"401": {
"description": "Acesso não autorizado (Solicitação sem autenticação)"
},
"403": {
"description": "Você não tem acesso à esta funcionalidade"
},
"500": {
"description": "Erro interno do servidor"
}
}
}
},
"/api/MedicoCoordenador/Get": {
"get": {
"tags": [
"MedicoCoordenador"
],
"summary": "Retorna os dados de um Médico Responsável pelo PCMSO específico cadastrado no Grupo.",
"operationId": "MedicoCoordenador_Get",
"consumes": [],
"produces": [
"application/json",
"text/json",
"text/xml",
"multipart/form-data"
],
"parameters": [
{
"name": "cd_medico_coordenador",
"in": "query",
"description": "Código do Médico no qual o Médico foi Cadastrado. (Obrigatório).",
"required": true,
"type": "integer",
"format": "int32"
},
{
"name": "cd_grupo",
"in": "query",
"description": "Código do Grupo de Empresas no qual o Médico foi Cadastrado. (Opcional).",
"required": false,
"type": "integer",
"format": "int32"
},
{
"name": "nr_crm",
"in": "query",
"description": "Número do CRM do Médico (Opcional).",
"required": false,
"type": "string"
},
{
"name": "uf_crm",
"in": "query",
"description": "Estado do CRM do Médico (Opcional).",
"required": false,
"type": "string"
}
],
"responses": {
"200": {
"description": "Sucesso com retorno do Colaborador",
"schema": {
"$ref": "#/definitions/ResponseConsultaMedicoCoordenador"
            }
          },
          "204": {
            "description": "Nenhuma informação foi encontrada com os dados enviados",
            "schema": {
              "$ref": "#/definitions/ResponseConsultaMedicoCoordenador"
}
},
"400": {
"description": "Solicitação mal formatada"
},
"401": {
"description": "Acesso não autorizado (Solicitação sem autenticação)"
},
"403": {
"description": "Você não tem acesso à esta funcionalidade"
},
"500": {
"description": "Erro interno do servidor"
}
}
}
},
"/api/MedicoCoordenador": {
"put": {
"tags": [
"MedicoCoordenador"
],
"summary": "Atualiza um Medico Coordenador no Grupo",
"operationId": "MedicoCoordenador_Put",
"consumes": [
"application/json",
"text/json",
"text/xml",
"application/x-www-form-urlencoded",
"multipart/form-data"
],
"produces": [
"application/json",
"text/json",
"text/xml",
"multipart/form-data"
],
"parameters": [
{
"name": "request",
"in": "body",
"required": true,
"schema": {
"$ref": "#/definitions/RequestAtualizacaoMedicoCoordenador"
}
}
],
"responses": {
"200": {
"description": "Sucesso com retorno do response",
"schema": {
"$ref": "#/definitions/ResponseCadastroMedicoCoordenador"
            }
          },
          "204": {
            "description": "Nenhuma informação foi encontrada com os dados enviados",
            "schema": {
              "$ref": "#/definitions/ResponseCadastroMedicoCoordenador"
}
},
"400": {
"description": "Solicitação mal formatada"
},
"401": {
"description": "Acesso não autorizado (Solicitação sem autenticação)"
},
"403": {
"description": "Você não tem acesso à esta funcionalidade"
},
"500": {
"description": "Erro interno do servidor"
}
}
},
"post": {
"tags": [
"MedicoCoordenador"
],
"summary": "Cadastra um Médico Responsável pelo PCMSO no Grupo.",
"operationId": "MedicoCoordenador_Post",
"consumes": [
"application/json",
"text/json",
"text/xml",
"application/x-www-form-urlencoded",
"multipart/form-data"
],
"produces": [
"application/json",
"text/json",
"text/xml",
"multipart/form-data"
],
"parameters": [
{
"name": "request",
"in": "body",
"required": true,
"schema": {
"$ref": "#/definitions/RequestCadastroMedicoCoordenador"
}
}
],
"responses": {
"200": {
"description": "Sucesso com retorno do response",
"schema": {
"$ref": "#/definitions/ResponseCadastroMedicoCoordenador"
            }
          },
          "204": {
            "description": "Nenhuma informação foi encontrada com os dados enviados",
            "schema": {
              "$ref": "#/definitions/ResponseCadastroMedicoCoordenador"
}
},
"400": {
"description": "Solicitação mal formatada"
},
"401": {
"description": "Acesso não autorizado (Solicitação sem autenticação)"
},
"403": {
"description": "Você não tem acesso à esta funcionalidade"
},
"500": {
"description": "Erro interno do servidor"
}
}
}
},
"/api/Setor/GetList": {
"get": {
"tags": [
"Setor"
],
"summary": "Lista de Setores (Departamentos) cadastrados na Assinatura",
"operationId": "Setor_GetList",
"consumes": [],
"produces": [
"application/json",
"text/json",
"text/xml",
"multipart/form-data"
],
"parameters": [
{
"name": "cd_empresa",
"in": "query",
"description": "Código da Empresa no Aplicativo (Opcional: para filtrar por Empresa).",
"required": false,
"type": "integer",
"format": "int32"
},
{
"name": "nr_cnpj_empresa",
"in": "query",
"description": "CNPJ da Empresa no Aplicativo (Opcional: para filtrar por Empresa).",
"required": false,
"type": "string"
}
],
"responses": {
"200": {
"description": "Sucesso com retorno da(s) Unidade(s)",
"schema": {
"items": {
"$ref": "#/definitions/ResponseConsultaSetor"
              },
              "xml": {
                "name": "ResponseConsultaSetor",
                "wrapped": true
              },
              "type": "array"
            }
          },
          "204": {
            "description": "Nenhuma informação foi encontrada com os dados enviados",
            "schema": {
              "items": {
                "$ref": "#/definitions/ResponseConsultaSetor"
},
"xml": {
"name": "ResponseConsultaSetor",
"wrapped": true
},
"type": "array"
}
},
"400": {
"description": "Solicitação mal formatada"
},
"401": {
"description": "Acesso não autorizado (Solicitação sem autenticação)"
},
"403": {
"description": "Você não tem acesso à esta funcionalidade"
},
"500": {
"description": "Erro interno do servidor"
}
}
}
},
"/api/Setor/Get": {
"get": {
"tags": [
"Setor"
],
"summary": "Retorna os dados de um Setor (Departamento) específico cadastrado na Assinatura",
"operationId": "Setor_Get",
"consumes": [],
"produces": [
"application/json",
"text/json",
"text/xml",
"multipart/form-data"
],
"parameters": [
{
"name": "cd_setor",
"in": "query",
"description": "Código do Setor na Empresa. Formato: (integer). Obrigatório se não for enviado o cd_interno_setor e cd_empresa.",
"required": false,
"type": "integer",
"format": "int32"
},
{
"name": "cd_interno_setor",
"in": "query",
"description": "Código Interno do Setor na sua Empresa.Formato: (string). Máx. de 20 caracteres. Obrigatório se não for enviado o cd_setor e se for enviado cd_empresa ou nr_cnpj.",
"required": false,
"type": "string",
"default": ""
},
{
"name": "cd_empresa",
"in": "query",
"description": "Código da Empresa no Aplicativo. Formato: (integer). Obrigatório se for enviado o cd_interno_setor e não for enviado o nr_cnpj.",
"required": false,
"type": "integer",
"format": "int32"
},
{
"name": "nr_cnpj",
"in": "query",
"description": "CNPJ da Empresa. Formato: (string). Sem máscaras. Máx. de 20 caracteres. Obrigatório se for enviado o cd_interno_setor e não for enviado o cd_empresa.",
"required": false,
"type": "string",
"default": ""
}
],
"responses": {
"200": {
"description": "Sucesso com retorno da Unidade",
"schema": {
"$ref": "#/definitions/ResponseConsultaSetor"
            }
          },
          "204": {
            "description": "Nenhuma informação foi encontrada com os dados enviados",
            "schema": {
              "$ref": "#/definitions/ResponseConsultaSetor"
}
},
"400": {
"description": "Solicitação mal formatada"
},
"401": {
"description": "Acesso não autorizado (Solicitação sem autenticação)"
},
"403": {
"description": "Você não tem acesso à esta funcionalidade"
},
"500": {
"description": "Erro interno do servidor"
}
}
}
},
"/api/Setor": {
"put": {
"tags": [
"Setor"
],
"summary": "Atualiza os dados de um Setor (Departamento) específico na Assinatura",
"operationId": "Setor_Put",
"consumes": [
"application/json",
"text/json",
"text/xml",
"application/x-www-form-urlencoded",
"multipart/form-data"
],
"produces": [
"application/json",
"text/json",
"text/xml",
"multipart/form-data"
],
"parameters": [
{
"name": "request",
"in": "body",
"required": true,
"schema": {
"$ref": "#/definitions/RequestAtualizacaoSetor"
}
}
],
"responses": {
"200": {
"description": "Sucesso com retorno do response",
"schema": {
"$ref": "#/definitions/ResponseCadastroSetor"
            }
          },
          "204": {
            "description": "Nenhuma informação foi encontrada com os dados enviados",
            "schema": {
              "$ref": "#/definitions/ResponseCadastroSetor"
}
},
"400": {
"description": "Solicitação mal formatada"
},
"401": {
"description": "Acesso não autorizado (Solicitação sem autenticação)"
},
"403": {
"description": "Você não tem acesso à esta funcionalidade"
},
"500": {
"description": "Erro interno do servidor"
}
}
},
"post": {
"tags": [
"Setor"
],
"summary": "Cadastra um Setor (Departamento) específico na Assinatura",
"operationId": "Setor_Post",
"consumes": [
"application/json",
"text/json",
"text/xml",
"application/x-www-form-urlencoded",
"multipart/form-data"
],
"produces": [
"application/json",
"text/json",
"text/xml",
"multipart/form-data"
],
"parameters": [
{
"name": "request",
"in": "body",
"required": true,
"schema": {
"$ref": "#/definitions/RequestCadastroSetor"
}
}
],
"responses": {
"200": {
"description": "Sucesso com retorno do response",
"schema": {
"$ref": "#/definitions/ResponseCadastroSetor"
            }
          },
          "204": {
            "description": "Nenhuma informação foi encontrada com os dados enviados",
            "schema": {
              "$ref": "#/definitions/ResponseCadastroSetor"
}
},
"400": {
"description": "Solicitação mal formatada"
},
"401": {
"description": "Acesso não autorizado (Solicitação sem autenticação)"
},
"403": {
"description": "Você não tem acesso à esta funcionalidade"
},
"500": {
"description": "Erro interno do servidor"
}
}
}
},
"/api/TabelasComplementares/GetListExames": {
"get": {
"tags": [
"TabelasComplementares"
],
"summary": "Lista de Exames cadastrados",
"operationId": "TabelasComplementares_GetListExames",
"consumes": [],
"produces": [
"application/json",
"text/json",
"text/xml",
"multipart/form-data"
],
"responses": {
"200": {
"description": "Sucesso com retorno do(s) Exame(s)",
"schema": {
"items": {
"$ref": "#/definitions/ResponseConsultaExame"
              },
              "xml": {
                "name": "ResponseConsultaExame",
                "wrapped": true
              },
              "type": "array"
            }
          },
          "204": {
            "description": "Nenhuma informação foi encontrada com os dados enviados",
            "schema": {
              "items": {
                "$ref": "#/definitions/ResponseConsultaExame"
},
"xml": {
"name": "ResponseConsultaExame",
"wrapped": true
},
"type": "array"
}
},
"400": {
"description": "Solicitação mal formatada"
},
"401": {
"description": "Acesso não autorizado (Solicitação sem autenticação)"
},
"403": {
"description": "Você não tem acesso à esta funcionalidade"
},
"500": {
"description": "Erro interno do servidor"
}
}
}
},
"/api/TabelasComplementares/GetListRiscos": {
"get": {
"tags": [
"TabelasComplementares"
],
"summary": "Lista de Riscos cadastrados",
"operationId": "TabelasComplementares_GetListRiscos",
"consumes": [],
"produces": [
"application/json",
"text/json",
"text/xml",
"multipart/form-data"
],
"responses": {
"200": {
"description": "Sucesso com retorno do(s) Risco(s)",
"schema": {
"items": {
"$ref": "#/definitions/ResponseConsultaRisco"
              },
              "xml": {
                "name": "ResponseConsultaRisco",
                "wrapped": true
              },
              "type": "array"
            }
          },
          "204": {
            "description": "Nenhuma informação foi encontrada com os dados enviados",
            "schema": {
              "items": {
                "$ref": "#/definitions/ResponseConsultaRisco"
},
"xml": {
"name": "ResponseConsultaRisco",
"wrapped": true
},
"type": "array"
}
},
"400": {
"description": "Solicitação mal formatada"
},
"401": {
"description": "Acesso não autorizado (Solicitação sem autenticação)"
},
"403": {
"description": "Você não tem acesso à esta funcionalidade"
},
"500": {
"description": "Erro interno do servidor"
}
}
}
},
"/api/TabelasComplementares/GetListEstados": {
"get": {
"tags": [
"TabelasComplementares"
],
"summary": "Lista de Estados cadastrados",
"operationId": "TabelasComplementares_GetListEstados",
"consumes": [],
"produces": [
"application/json",
"text/json",
"text/xml",
"multipart/form-data"
],
"responses": {
"200": {
"description": "Sucesso com retorno do(s) Estado(s)",
"schema": {
"items": {
"$ref": "#/definitions/ResponseConsultaEstado"
              },
              "xml": {
                "name": "ResponseConsultaEstado",
                "wrapped": true
              },
              "type": "array"
            }
          },
          "204": {
            "description": "Nenhuma informação foi encontrada com os dados enviados",
            "schema": {
              "items": {
                "$ref": "#/definitions/ResponseConsultaEstado"
},
"xml": {
"name": "ResponseConsultaEstado",
"wrapped": true
},
"type": "array"
}
},
"400": {
"description": "Solicitação mal formatada"
},
"401": {
"description": "Acesso não autorizado (Solicitação sem autenticação)"
},
"403": {
"description": "Você não tem acesso à esta funcionalidade"
},
"500": {
"description": "Erro interno do servidor"
}
}
}
},
"/api/TabelasComplementares/GetListCidades": {
"get": {
"tags": [
"TabelasComplementares"
],
"summary": "Lista de Cidades cadastrados",
"operationId": "TabelasComplementares_GetListCidades",
"consumes": [],
"produces": [
"application/json",
"text/json",
"text/xml",
"multipart/form-data"
],
"responses": {
"200": {
"description": "Sucesso com retorno da(s) Cidade(s)",
"schema": {
"items": {
"$ref": "#/definitions/ResponseConsultaCidade"
              },
              "xml": {
                "name": "ResponseConsultaCidade",
                "wrapped": true
              },
              "type": "array"
            }
          },
          "204": {
            "description": "Nenhuma informação foi encontrada com os dados enviados",
            "schema": {
              "items": {
                "$ref": "#/definitions/ResponseConsultaCidade"
},
"xml": {
"name": "ResponseConsultaCidade",
"wrapped": true
},
"type": "array"
}
},
"400": {
"description": "Solicitação mal formatada"
},
"401": {
"description": "Acesso não autorizado (Solicitação sem autenticação)"
},
"403": {
"description": "Você não tem acesso à esta funcionalidade"
},
"500": {
"description": "Erro interno do servidor"
}
}
}
},
"/api/TabelasComplementares/GetListCnae": {
"get": {
"tags": [
"TabelasComplementares"
],
"summary": "Lista de Cnaes cadastrados",
"operationId": "TabelasComplementares_GetListCnae",
"consumes": [],
"produces": [
"application/json",
"text/json",
"text/xml",
"multipart/form-data"
],
"responses": {
"200": {
"description": "Sucesso com retorno da(s) Ocupação(ões)",
"schema": {
"items": {
"$ref": "#/definitions/ResponseConsultaCnae"
              },
              "xml": {
                "name": "ResponseConsultaCnae",
                "wrapped": true
              },
              "type": "array"
            }
          },
          "204": {
            "description": "Nenhuma informação foi encontrada com os dados enviados",
            "schema": {
              "items": {
                "$ref": "#/definitions/ResponseConsultaCnae"
},
"xml": {
"name": "ResponseConsultaCnae",
"wrapped": true
},
"type": "array"
}
},
"400": {
"description": "Solicitação mal formatada"
},
"401": {
"description": "Acesso não autorizado (Solicitação sem autenticação)"
},
"403": {
"description": "Você não tem acesso à esta funcionalidade"
},
"500": {
"description": "Erro interno do servidor"
}
}
}
},
"/api/TabelasComplementares/GetListCbo": {
"get": {
"tags": [
"TabelasComplementares"
],
"summary": "Lista de Ocupações cadastrados",
"operationId": "TabelasComplementares_GetListCbo",
"consumes": [],
"produces": [
"application/json",
"text/json",
"text/xml",
"multipart/form-data"
],
"responses": {
"200": {
"description": "Sucesso com retorno da(s) Ocupação(ões)",
"schema": {
"items": {
"$ref": "#/definitions/ResponseConsultaCbo"
              },
              "xml": {
                "name": "ResponseConsultaCbo",
                "wrapped": true
              },
              "type": "array"
            }
          },
          "204": {
            "description": "Nenhuma informação foi encontrada com os dados enviados",
            "schema": {
              "items": {
                "$ref": "#/definitions/ResponseConsultaCbo"
},
"xml": {
"name": "ResponseConsultaCbo",
"wrapped": true
},
"type": "array"
}
},
"400": {
"description": "Solicitação mal formatada"
},
"401": {
"description": "Acesso não autorizado (Solicitação sem autenticação)"
},
"403": {
"description": "Você não tem acesso à esta funcionalidade"
},
"500": {
"description": "Erro interno do servidor"
}
}
}
},
"/api/TabelasComplementares/GetListTipoInscricao": {
"get": {
"tags": [
"TabelasComplementares"
],
"summary": "Lista de Tipos de Inscrições cadastrados",
"operationId": "TabelasComplementares_GetListTipoInscricao",
"consumes": [],
"produces": [
"application/json",
"text/json",
"text/xml",
"multipart/form-data"
],
"responses": {
"200": {
"description": "Sucesso com retorno da(s) Ocupação(ões)",
"schema": {
"items": {
"$ref": "#/definitions/ResponseConsultaTipoInscricao"
              },
              "xml": {
                "name": "ResponseConsultaTipoInscricao",
                "wrapped": true
              },
              "type": "array"
            }
          },
          "204": {
            "description": "Nenhuma informação foi encontrada com os dados enviados",
            "schema": {
              "items": {
                "$ref": "#/definitions/ResponseConsultaTipoInscricao"
},
"xml": {
"name": "ResponseConsultaTipoInscricao",
"wrapped": true
},
"type": "array"
}
},
"400": {
"description": "Solicitação mal formatada"
},
"401": {
"description": "Acesso não autorizado (Solicitação sem autenticação)"
},
"403": {
"description": "Você não tem acesso à esta funcionalidade"
},
"500": {
"description": "Erro interno do servidor"
}
}
}
},
"/api/TabelasComplementares/GetListTipoLocalTrabalho": {
"get": {
"tags": [
"TabelasComplementares"
],
"summary": "Lista de Tipos de Local de Trabalho cadastrados",
"operationId": "TabelasComplementares_GetListTipoLocalTrabalho",
"consumes": [],
"produces": [
"application/json",
"text/json",
"text/xml",
"multipart/form-data"
],
"responses": {
"200": {
"description": "Sucesso com retorno da(s) Ocupação(ões)",
"schema": {
"items": {
"$ref": "#/definitions/ResponseConsultaTipoLocalTrabalho"
              },
              "xml": {
                "name": "ResponseConsultaTipoLocalTrabalho",
                "wrapped": true
              },
              "type": "array"
            }
          },
          "204": {
            "description": "Nenhuma informação foi encontrada com os dados enviados",
            "schema": {
              "items": {
                "$ref": "#/definitions/ResponseConsultaTipoLocalTrabalho"
},
"xml": {
"name": "ResponseConsultaTipoLocalTrabalho",
"wrapped": true
},
"type": "array"
}
},
"400": {
"description": "Solicitação mal formatada"
},
"401": {
"description": "Acesso não autorizado (Solicitação sem autenticação)"
},
"403": {
"description": "Você não tem acesso à esta funcionalidade"
},
"500": {
"description": "Erro interno do servidor"
}
}
}
},
"/api/TabelasComplementares/GetListTipoEdificacao": {
"get": {
"tags": [
"TabelasComplementares"
],
"summary": "Lista de Tipos de Edificações cadastrados",
"operationId": "TabelasComplementares_GetListTipoEdificacao",
"consumes": [],
"produces": [
"application/json",
"text/json",
"text/xml",
"multipart/form-data"
],
"responses": {
"200": {
"description": "Sucesso com retorno da(s) Ocupação(ões)",
"schema": {
"items": {
"$ref": "#/definitions/ResponseConsultaTipoEdificacao"
              },
              "xml": {
                "name": "ResponseConsultaTipoEdificacao",
                "wrapped": true
              },
              "type": "array"
            }
          },
          "204": {
            "description": "Nenhuma informação foi encontrada com os dados enviados",
            "schema": {
              "items": {
                "$ref": "#/definitions/ResponseConsultaTipoEdificacao"
},
"xml": {
"name": "ResponseConsultaTipoEdificacao",
"wrapped": true
},
"type": "array"
}
},
"400": {
"description": "Solicitação mal formatada"
},
"401": {
"description": "Acesso não autorizado (Solicitação sem autenticação)"
},
"403": {
"description": "Você não tem acesso à esta funcionalidade"
},
"500": {
"description": "Erro interno do servidor"
}
}
}
},
"/api/TabelasComplementares/GetListTipoCobertura": {
"get": {
"tags": [
"TabelasComplementares"
],
"summary": "Lista de Tipos de Cobertura cadastrados",
"operationId": "TabelasComplementares_GetListTipoCobertura",
"consumes": [],
"produces": [
"application/json",
"text/json",
"text/xml",
"multipart/form-data"
],
"responses": {
"200": {
"description": "Sucesso com retorno da(s) Ocupação(ões)",
"schema": {
"items": {
"$ref": "#/definitions/ResponseConsultaTipoCobertura"
              },
              "xml": {
                "name": "ResponseConsultaTipoCobertura",
                "wrapped": true
              },
              "type": "array"
            }
          },
          "204": {
            "description": "Nenhuma informação foi encontrada com os dados enviados",
            "schema": {
              "items": {
                "$ref": "#/definitions/ResponseConsultaTipoCobertura"
},
"xml": {
"name": "ResponseConsultaTipoCobertura",
"wrapped": true
},
"type": "array"
}
},
"400": {
"description": "Solicitação mal formatada"
},
"401": {
"description": "Acesso não autorizado (Solicitação sem autenticação)"
},
"403": {
"description": "Você não tem acesso à esta funcionalidade"
},
"500": {
"description": "Erro interno do servidor"
}
}
}
},
"/api/TabelasComplementares/GetListTipoPiso": {
"get": {
"tags": [
"TabelasComplementares"
],
"summary": "Lista de Tipos de Pisos cadastrados",
"operationId": "TabelasComplementares_GetListTipoPiso",
"consumes": [],
"produces": [
"application/json",
"text/json",
"text/xml",
"multipart/form-data"
],
"responses": {
"200": {
"description": "Sucesso com retorno da(s) Ocupação(ões)",
"schema": {
"items": {
"$ref": "#/definitions/ResponseConsultaTipoPiso"
              },
              "xml": {
                "name": "ResponseConsultaTipoPiso",
                "wrapped": true
              },
              "type": "array"
            }
          },
          "204": {
            "description": "Nenhuma informação foi encontrada com os dados enviados",
            "schema": {
              "items": {
                "$ref": "#/definitions/ResponseConsultaTipoPiso"
},
"xml": {
"name": "ResponseConsultaTipoPiso",
"wrapped": true
},
"type": "array"
}
},
"400": {
"description": "Solicitação mal formatada"
},
"401": {
"description": "Acesso não autorizado (Solicitação sem autenticação)"
},
"403": {
"description": "Você não tem acesso à esta funcionalidade"
},
"500": {
"description": "Erro interno do servidor"
}
}
}
},
"/api/TabelasComplementares/GetListTipoIluminacao": {
"get": {
"tags": [
"TabelasComplementares"
],
"summary": "Lista de Tipos de Iluminação cadastrados",
"operationId": "TabelasComplementares_GetListTipoIluminacao",
"consumes": [],
"produces": [
"application/json",
"text/json",
"text/xml",
"multipart/form-data"
],
"responses": {
"200": {
"description": "Sucesso com retorno da(s) Ocupação(ões)",
"schema": {
"items": {
"$ref": "#/definitions/ResponseConsultaTipoIluminacao"
              },
              "xml": {
                "name": "ResponseConsultaTipoIluminacao",
                "wrapped": true
              },
              "type": "array"
            }
          },
          "204": {
            "description": "Nenhuma informação foi encontrada com os dados enviados",
            "schema": {
              "items": {
                "$ref": "#/definitions/ResponseConsultaTipoIluminacao"
},
"xml": {
"name": "ResponseConsultaTipoIluminacao",
"wrapped": true
},
"type": "array"
}
},
"400": {
"description": "Solicitação mal formatada"
},
"401": {
"description": "Acesso não autorizado (Solicitação sem autenticação)"
},
"403": {
"description": "Você não tem acesso à esta funcionalidade"
},
"500": {
"description": "Erro interno do servidor"
}
}
}
},
"/api/TabelasComplementares/GetListTipoFechamento": {
"get": {
"tags": [
"TabelasComplementares"
],
"summary": "Lista de Tipos de Fechamento cadastrados",
"operationId": "TabelasComplementares_GetListTipoFechamento",
"consumes": [],
"produces": [
"application/json",
"text/json",
"text/xml",
"multipart/form-data"
],
"responses": {
"200": {
"description": "Sucesso com retorno da(s) Ocupação(ões)",
"schema": {
"items": {
"$ref": "#/definitions/ResponseConsultaTipoFechamento"
              },
              "xml": {
                "name": "ResponseConsultaTipoFechamento",
                "wrapped": true
              },
              "type": "array"
            }
          },
          "204": {
            "description": "Nenhuma informação foi encontrada com os dados enviados",
            "schema": {
              "items": {
                "$ref": "#/definitions/ResponseConsultaTipoFechamento"
},
"xml": {
"name": "ResponseConsultaTipoFechamento",
"wrapped": true
},
"type": "array"
}
},
"400": {
"description": "Solicitação mal formatada"
},
"401": {
"description": "Acesso não autorizado (Solicitação sem autenticação)"
},
"403": {
"description": "Você não tem acesso à esta funcionalidade"
},
"500": {
"description": "Erro interno do servidor"
}
}
}
},
"/api/TabelasComplementares/GetListTipoVentilacao": {
"get": {
"tags": [
"TabelasComplementares"
],
"summary": "Lista de Tipos de Ventilação cadastrados",
"operationId": "TabelasComplementares_GetListTipoVentilacao",
"consumes": [],
"produces": [
"application/json",
"text/json",
"text/xml",
"multipart/form-data"
],
"responses": {
"200": {
"description": "Sucesso com retorno da(s) Ocupação(ões)",
"schema": {
"items": {
"$ref": "#/definitions/ResponseConsultaTipoVentilacao"
              },
              "xml": {
                "name": "ResponseConsultaTipoVentilacao",
                "wrapped": true
              },
              "type": "array"
            }
          },
          "204": {
            "description": "Nenhuma informação foi encontrada com os dados enviados",
            "schema": {
              "items": {
                "$ref": "#/definitions/ResponseConsultaTipoVentilacao"
},
"xml": {
"name": "ResponseConsultaTipoVentilacao",
"wrapped": true
},
"type": "array"
}
},
"400": {
"description": "Solicitação mal formatada"
},
"401": {
"description": "Acesso não autorizado (Solicitação sem autenticação)"
},
"403": {
"description": "Você não tem acesso à esta funcionalidade"
},
"500": {
"description": "Erro interno do servidor"
}
}
}
},
"/api/TabelasComplementares/GetListCategoriaTrabalhador": {
"get": {
"tags": [
"TabelasComplementares"
],
"summary": "Lista de Categoria do Trabalhador cadastrados",
"operationId": "TabelasComplementares_GetListCategoriaTrabalhador",
"consumes": [],
"produces": [
"application/json",
"text/json",
"text/xml",
"multipart/form-data"
],
"responses": {
"200": {
"description": "Sucesso com retorno da(s) Ocupação(ões)",
"schema": {
"items": {
"$ref": "#/definitions/ResponseConsultaCategoriaTrabalhador"
              },
              "xml": {
                "name": "ResponseConsultaCategoriaTrabalhador",
                "wrapped": true
              },
              "type": "array"
            }
          },
          "204": {
            "description": "Nenhuma informação foi encontrada com os dados enviados",
            "schema": {
              "items": {
                "$ref": "#/definitions/ResponseConsultaCategoriaTrabalhador"
},
"xml": {
"name": "ResponseConsultaCategoriaTrabalhador",
"wrapped": true
},
"type": "array"
}
},
"400": {
"description": "Solicitação mal formatada"
},
"401": {
"description": "Acesso não autorizado (Solicitação sem autenticação)"
},
"403": {
"description": "Você não tem acesso à esta funcionalidade"
},
"500": {
"description": "Erro interno do servidor"
}
}
}
},
"/api/Unidade/GetList": {
"get": {
"tags": [
"Unidade"
],
"summary": "Lista de Unidades cadastradas no Grupo",
"operationId": "Unidade_GetList",
"consumes": [],
"produces": [
"application/json",
"text/json",
"text/xml",
"multipart/form-data"
],
"parameters": [
{
"name": "cd_empresa",
"in": "query",
"description": "Código da Empresa no Aplicativo (Opcional: para filtrar por Empresa). Formato: (integer).",
"required": false,
"type": "integer",
"format": "int32"
},
{
"name": "nr_cnpj_empresa",
"in": "query",
"description": "CNPJ da Empresa no Aplicativo (Opcional: para filtrar por Empresa). Formato: (string). Máx. de 20 caracteres.",
"required": false,
"type": "string"
}
],
"responses": {
"200": {
"description": "Sucesso com retorno da(s) Unidade(s)",
"schema": {
"items": {
"$ref": "#/definitions/ResponseConsultaUnidade"
              },
              "xml": {
                "name": "ResponseConsultaUnidade",
                "wrapped": true
              },
              "type": "array"
            }
          },
          "204": {
            "description": "Nenhuma informação foi encontrada com os dados enviados",
            "schema": {
              "items": {
                "$ref": "#/definitions/ResponseConsultaUnidade"
},
"xml": {
"name": "ResponseConsultaUnidade",
"wrapped": true
},
"type": "array"
}
},
"400": {
"description": "Solicitação mal formatada"
},
"401": {
"description": "Acesso não autorizado (Solicitação sem autenticação)"
},
"403": {
"description": "Você não tem acesso à esta funcionalidade"
},
"500": {
"description": "Erro interno do servidor"
}
}
}
},
"/api/Unidade/Get": {
"get": {
"tags": [
"Unidade"
],
"summary": "Retorna os dados de uma Unidade específica cadastrada no Grupo",
"operationId": "Unidade_Get",
"consumes": [],
"produces": [
"application/json",
"text/json",
"text/xml",
"multipart/form-data"
],
"parameters": [
{
"name": "cd_unidade",
"in": "query",
"description": "Código da Unidade na Indexmed (Obrigatório). Formato: (integer).",
"required": false,
"type": "integer",
"format": "int32"
},
{
"name": "cd_interno_unidade",
"in": "query",
"description": "Código Interno da Unidade na sua Empresa (Opcional). Formato: (string). Máx. de 20 caracteres.",
"required": false,
"type": "string",
"default": ""
},
{
"name": "nr_cnpj_unidade",
"in": "query",
"required": false,
"type": "string",
"default": ""
}
],
"responses": {
"200": {
"description": "Sucesso com retorno da Unidade",
"schema": {
"$ref": "#/definitions/ResponseConsultaUnidade"
            }
          },
          "204": {
            "description": "Nenhuma informação foi encontrada com os dados enviados",
            "schema": {
              "$ref": "#/definitions/ResponseConsultaUnidade"
}
},
"400": {
"description": "Solicitação mal formatada"
},
"401": {
"description": "Acesso não autorizado (Solicitação sem autenticação)"
},
"403": {
"description": "Você não tem acesso à esta funcionalidade"
},
"500": {
"description": "Erro interno do servidor"
}
}
}
},
"/api/Unidade": {
"put": {
"tags": [
"Unidade"
],
"summary": "Atualiza os dados de uma Unidade específica cadastrada na Empresa",
"operationId": "Unidade_Put",
"consumes": [
"application/json",
"text/json",
"text/xml",
"application/x-www-form-urlencoded",
"multipart/form-data"
],
"produces": [
"application/json",
"text/json",
"text/xml",
"multipart/form-data"
],
"parameters": [
{
"name": "request",
"in": "body",
"required": true,
"schema": {
"$ref": "#/definitions/RequestAtualizacaoUnidade"
}
}
],
"responses": {
"200": {
"description": "Sucesso com retorno do response",
"schema": {
"$ref": "#/definitions/ResponseCadastroUnidade"
            }
          },
          "204": {
            "description": "Nenhuma informação foi encontrada com os dados enviados",
            "schema": {
              "$ref": "#/definitions/ResponseCadastroUnidade"
}
},
"400": {
"description": "Solicitação mal formatada"
},
"401": {
"description": "Acesso não autorizado (Solicitação sem autenticação)"
},
"403": {
"description": "Você não tem acesso à esta funcionalidade"
},
"500": {
"description": "Erro interno do servidor"
}
}
},
"post": {
"tags": [
"Unidade"
],
"summary": "Cadastra uma Unidade na Empresa",
"operationId": "Unidade_Post",
"consumes": [
"application/json",
"text/json",
"text/xml",
"application/x-www-form-urlencoded",
"multipart/form-data"
],
"produces": [
"application/json",
"text/json",
"text/xml",
"multipart/form-data"
],
"parameters": [
{
"name": "request",
"in": "body",
"required": true,
"schema": {
"$ref": "#/definitions/RequestCadastroUnidade"
}
}
],
"responses": {
"200": {
"description": "Sucesso com retorno do response",
"schema": {
"$ref": "#/definitions/ResponseCadastroUnidade"
            }
          },
          "204": {
            "description": "Nenhuma informação foi encontrada com os dados enviados",
            "schema": {
              "$ref": "#/definitions/ResponseCadastroUnidade"
}
},
"400": {
"description": "Solicitação mal formatada"
},
"401": {
"description": "Acesso não autorizado (Solicitação sem autenticação)"
},
"403": {
"description": "Você não tem acesso à esta funcionalidade"
},
"500": {
"description": "Erro interno do servidor"
}
}
}
}
},
"definitions": {
"ResponseConsultaArquivo": {
"properties": {
"cd_documento": {
"type": "integer",
"format": "int32"
},
"nr_versao": {
"type": "integer",
"format": "int32"
},
"nm_documento": {
"type": "string"
},
"ds_documento": {
"type": "string"
},
"cd_documento_arquivo": {
"type": "integer",
"format": "int32"
},
"Arquivo": {
"$ref": "#/definitions/DocumentoArquivo"
        },
        "cd_tipo_documento": {
          "type": "integer",
          "format": "int32"
        },
        "nm_tipo_documento": {
          "type": "string"
        },
        "cd_grupo": {
          "type": "integer",
          "format": "int32"
        },
        "nm_grupo": {
          "type": "string"
        },
        "cd_empresa": {
          "type": "integer",
          "format": "int32"
        },
        "nm_empresa": {
          "type": "string"
        },
        "cd_unidade": {
          "type": "integer",
          "format": "int32"
        },
        "nm_unidade": {
          "type": "string"
        },
        "dt_validade_inicio": {
          "type": "string",
          "format": "date-time"
        },
        "dt_validade_final": {
          "type": "string",
          "format": "date-time"
        },
        "fl_status": {
          "type": "string"
        },
        "cd_user_manu": {
          "type": "integer",
          "format": "int32"
        },
        "ts_user_manu": {
          "type": "string"
        }
      },
      "xml": {
        "name": "ResponseConsultaArquivo"
      },
      "type": "object"
    },
    "DocumentoArquivo": {
      "properties": {
        "cd_documento_arquivo": {
          "type": "integer",
          "format": "int32"
        },
        "nm_arquivo": {
          "type": "string"
        },
        "url_arquivo": {
          "type": "string"
        },
        "cd_parent": {
          "type": "integer",
          "format": "int32"
        },
        "ds_mimetype": {
          "type": "string"
        },
        "fl_diretorio": {
          "type": "string"
        },
        "nr_tamanho": {
          "type": "integer",
          "format": "int32"
        },
        "arquivo": {
          "type": "string",
          "format": "byte"
        }
      },
      "xml": {
        "name": "DocumentoArquivo"
      },
      "type": "object"
    },
    "ResponseConsultaArquivoTipo": {
      "properties": {
        "cd_tipo_documento": {
          "type": "integer",
          "format": "int32"
        },
        "nm_tipo_documento": {
          "type": "string"
        }
      },
      "xml": {
        "name": "ResponseConsultaArquivoTipo"
      },
      "type": "object"
    },
    "ResponseConsultaFuncionario": {
      "properties": {
        "cd_grupo": {
          "type": "integer",
          "format": "int32"
        },
        "cd_empresa": {
          "type": "integer",
          "format": "int32"
        },
        "cd_unidade": {
          "type": "integer",
          "format": "int32"
        },
        "cd_setor": {
          "type": "integer",
          "format": "int32"
        },
        "cd_funcao": {
          "type": "integer",
          "format": "int32"
        },
        "cd_funcionario": {
          "type": "integer",
          "format": "int32"
        },
        "nm_funcionario": {
          "type": "string"
        },
        "dt_nascimento": {
          "type": "string",
          "format": "date-time"
        },
        "dt_admissao": {
          "type": "string",
          "format": "date-time"
        },
        "dt_ultimo_exame": {
          "type": "string",
          "format": "date-time"
        },
        "dt_proximo_exame": {
          "type": "string",
          "format": "date-time"
        },
        "fl_sexo": {
          "type": "string"
        },
        "nr_matricula": {
          "type": "string"
        },
        "cd_categoria_trabalhador": {
          "type": "integer",
          "format": "int32"
        },
        "nr_cpf": {
          "type": "string"
        },
        "fl_consentimento_lgpd": {
          "type": "string"
        },
        "ds_email": {
          "type": "string"
        },
        "fl_tipo": {
          "type": "string"
        },
        "fl_status": {
          "type": "string"
        },
        "cd_user_manu": {
          "type": "integer",
          "format": "int32"
        },
        "ts_user_manu": {
          "type": "string"
        }
      },
      "xml": {
        "name": "ResponseConsultaFuncionario"
      },
      "type": "object"
    },
    "RequestCadastroArquivo": {
      "properties": {
        "cd_grupo": {
          "type": "integer",
          "format": "int32"
        },
        "cd_empresa": {
          "type": "integer",
          "format": "int32"
        },
        "cd_unidade": {
          "type": "integer",
          "format": "int32"
        },
        "cd_tipo_documento": {
          "type": "integer",
          "format": "int32"
        },
        "nm_documento": {
          "type": "string"
        },
        "dt_validade_inicio": {
          "type": "string",
          "format": "date-time"
        },
        "dt_validade_final": {
          "type": "string",
          "format": "date-time"
        },
        "nm_arquivo": {
          "type": "string"
        },
        "arquivo": {
          "type": "string",
          "format": "byte"
        }
      },
      "xml": {
        "name": "RequestCadastroArquivo"
      },
      "type": "object"
    },
    "ResponseCadastroArquivo": {
      "properties": {
        "cd_documento": {
          "type": "integer",
          "format": "int32"
        },
        "return_msgm": {
          "type": "string"
        },
        "return_code": {
          "type": "integer",
          "format": "int32"
        },
        "request_id": {
          "example": "00000000-0000-0000-0000-000000000000",
          "type": "string",
          "format": "uuid"
        }
      },
      "xml": {
        "name": "ResponseCadastroArquivo"
      },
      "type": "object"
    },
    "RequestAtualizacaoArquivo": {
      "properties": {
        "cd_documento": {
          "type": "integer",
          "format": "int32"
        },
        "cd_grupo": {
          "type": "integer",
          "format": "int32"
        },
        "cd_empresa": {
          "type": "integer",
          "format": "int32"
        },
        "cd_unidade": {
          "type": "integer",
          "format": "int32"
        },
        "cd_tipo_documento": {
          "type": "integer",
          "format": "int32"
        },
        "nm_documento": {
          "type": "string"
        },
        "dt_validade_inicio": {
          "type": "string",
          "format": "date-time"
        },
        "dt_validade_final": {
          "type": "string",
          "format": "date-time"
        },
        "fl_status": {
          "type": "string"
        },
        "cd_documento_arquivo": {
          "type": "integer",
          "format": "int32"
        }
      },
      "xml": {
        "name": "RequestAtualizacaoArquivo"
      },
      "type": "object"
    },
    "RequestCadastroFuncionario": {
      "description": "RequestCadastroFuncionario = Objeto para cadastro do(a) Funcionário(a).",
      "required": [
        "nm_funcionario",
        "dt_nascimento",
        "dt_admissao",
        "fl_sexo",
        "fl_consentimento_lgpd",
        "fl_status",
        "fl_tipo"
      ],
      "properties": {
        "cd_empresa": {
          "description": "Código da empresa/empregador na plataforma Indexmed. Formato: (integer). Obrigatório se não foi enviado o nr_cnpj",
          "type": "integer",
          "format": "int32"
        },
        "nr_cnpj": {
          "description": "CNPJ da empresa ou CPF do Empregador onde o colaborador está contratado. Formato: (string). Sem máscaras. Máx. de 20 caracteres. Obrigatório se não foi enviado o cd_empresa",
          "type": "string"
        },
        "cd_unidade": {
          "description": "Código da Unidade na Indexmed (Obrigatório). Formato: (integer). Obrigatório se não for enviado o cd_interno_unidade.",
          "type": "integer",
          "format": "int32"
        },
        "cd_interno_unidade": {
          "description": "Código Interno da Unidade na sua Empresa (Opcional). Formato: (string). Máx. de 20 caracteres. Obrigatório se não for enviado o cd_unidade",
          "type": "string"
        },
        "cd_setor": {
          "description": "Código do Setor na plataforma Indexmed. Formato: (integer). Obrigatório se não for enviado o cd_interno_setor.",
          "type": "integer",
          "format": "int32"
        },
        "cd_interno_setor": {
          "description": "Código interno do setor na Empresa. Formato: (string). Máx. de 20 caracteres. Obrigatório se não for enviado o cd_setor.",
          "type": "string"
        },
        "cd_funcao": {
          "description": "Código da Função na plataforma Indexmed. Formato: (integer). Obrigatório se foi enviado o cd_interno_funcao.",
          "type": "integer",
          "format": "int32"
        },
        "cd_interno_funcao": {
          "description": "Código interno da função na Empresa. Formato: (string). Máx. de 20 caracteres. Obrigatório se não foi enviado o cd_funcao.",
          "type": "string"
        },
        "nm_funcionario": {
          "description": "Nome do(a) funcionário(a).  Formato: (string). Máx. de 255 caracteres. Obrigatório",
          "type": "string"
        },
        "dt_nascimento": {
          "description": "Data do nascimento do(a) funcionário(a).  Formato: (datetime universal Ex.: \"1985-09-14T23:33:11.168Z\" ). Obrigatório.",
          "type": "string",
          "format": "date-time"
        },
        "dt_admissao": {
          "description": "Data da admissão do(a) funcionário(a) na empresa.  Formato: (datetime universal Ex.: \"2005-04-10T23:33:11.168Z\" ). Obrigatório.",
          "type": "string",
          "format": "date-time"
        },
        "dt_ultimo_exame": {
          "description": "Data do nascimento do(a) funcionário(a).  Formato: (datetime universal Ex.: \"2022-02-01T23:33:11.168Z\" ). Opcional.",
          "type": "string",
          "format": "date-time"
        },
        "fl_sexo": {
          "description": "Gênero/Sexo do funcionário(a). Valores válidos M,F (M = Masculino, F = Feminino). Formato: (string). Máx. de 1 caractere. Obrigatório.",
          "type": "string"
        },
        "nr_matricula": {
          "description": "Matrícula do(a) funcionário(a) – Igual à do eSocial.  Formato: (string). Máx. de 30 caracteres. Obrigatório para contratos com vínculo (Categoria de trabalhador = 101)",
          "type": "string"
        },
        "cd_categoria_trabalhador": {
          "description": "Código de categoria do trabalhador. Formato: (integer). Tabela oficial: https://www.gov.br/esocial/pt-br/documentacao-tecnica/leiautes-esocial-versao-s-1-3-cons-ate-nt-04-2025-rev-26-08-2025/index.html/tabelas.html#01 . Obrigatório se não enviar a matrícula",
          "type": "integer",
          "format": "int32"
        },
        "nr_cpf": {
          "description": "CPF do colaborador. Formato: (string). Sem máscaras. Máx. de 20 caracteres. Obrigatório.",
          "type": "string"
        },
        "fl_consentimento_lgpd": {
          "description": "Indicação se há consentimento do funcionário em compartilhar os dados de saúde ocupacional. Valores válidos S, N (S = Sim, N = Não). Formato: (string). Máx. de 1 caractere. Obrigatório.",
          "type": "string"
        },
        "ds_email": {
          "description": "Email do funcionário. Formato: (string). Máx. de 100 caracteres.",
          "type": "string"
        },
        "fl_status": {
          "description": "Status da Função. Valores válidos A,I,F,T (A = Ativo, I = Inativo/Demitido, F = Férias, T = Afastado). Formato: (string). Máx. de 1 caractere. Obrigatório.",
          "type": "string"
        },
        "fl_tipo": {
          "description": "Tipo de cadastro, Funcionário efetivo ou Candidato à vaga. Valores válidos F, C (F = funcionário, C = Candidato). Formato: (string). Máx. de 1 caractere. Obrigatório.",
          "type": "string"
        }
      },
      "xml": {
        "name": "RequestCadastroFuncionario"
      },
      "type": "object"
    },
    "ResponseCadastroFuncionario": {
      "properties": {
        "cd_funcionario": {
          "type": "integer",
          "format": "int32"
        },
        "return_msgm": {
          "type": "string"
        },
        "return_code": {
          "type": "integer",
          "format": "int32"
        },
        "request_id": {
          "example": "00000000-0000-0000-0000-000000000000",
          "type": "string",
          "format": "uuid"
        }
      },
      "xml": {
        "name": "ResponseCadastroFuncionario"
      },
      "type": "object"
    },
    "RequestAtualizacaoFuncionario": {
      "description": "RequestAtualizacaoFuncionario = Objeto para atualização do(a) Funcionário(a).",
      "required": [
        "cd_funcionario",
        "nm_funcionario",
        "dt_nascimento",
        "dt_admissao",
        "fl_sexo",
        "fl_consentimento_lgpd",
        "fl_status",
        "fl_tipo"
      ],
      "properties": {
        "cd_funcionario": {
          "description": "Código do(a) funcionário(a) Unidade na Indexmed. Formato: (integer).Obrigatório.",
          "type": "integer",
          "format": "int32"
        },
        "cd_empresa": {
          "description": "Código da empresa/empregador na plataforma Indexmed. Formato: (integer). Obrigatório se não foi enviado o nr_cnpj",
          "type": "integer",
          "format": "int32"
        },
        "nr_cnpj": {
          "description": "CNPJ da empresa ou CPF do Empregador onde o colaborador está contratado. Formato: (string). Sem máscaras. Máx. de 20 caracteres. Obrigatório se não foi enviado o cd_empresa",
          "type": "string"
        },
        "cd_unidade": {
          "description": "Código da Unidade na Indexmed (Obrigatório). Formato: (integer). Obrigatório se não for enviado o cd_interno_unidade.",
          "type": "integer",
          "format": "int32"
        },
        "cd_interno_unidade": {
          "description": "Código Interno da Unidade na sua Empresa (Opcional). Formato: (string). Máx. de 20 caracteres. Obrigatório se não for enviado o cd_unidade",
          "type": "string"
        },
        "cd_setor": {
          "description": "Código do Setor na plataforma Indexmed. Formato: (integer). Obrigatório se não for enviado o cd_interno_setor.",
          "type": "integer",
          "format": "int32"
        },
        "cd_interno_setor": {
          "description": "Código interno do setor na Empresa. Formato: (string). Máx. de 20 caracteres. Obrigatório se não for enviado o cd_setor.",
          "type": "string"
        },
        "cd_funcao": {
          "description": "Código da Função na plataforma Indexmed. Formato: (integer). Obrigatório se foi enviado o cd_interno_funcao.",
          "type": "integer",
          "format": "int32"
        },
        "cd_interno_funcao": {
          "description": "Código interno da função na Empresa. Formato: (string). Máx. de 20 caracteres. Obrigatório se não foi enviado o cd_funcao.",
          "type": "string"
        },
        "nm_funcionario": {
          "description": "Nome do(a) funcionário(a).  Formato: (string). Máx. de 255 caracteres. Obrigatório",
          "type": "string"
        },
        "dt_nascimento": {
          "description": "Data do nascimento do(a) funcionário(a).  Formato: (datetime universal Ex.: \"1985-09-14T23:33:11.168Z\" ). Obrigatório.",
          "type": "string",
          "format": "date-time"
        },
        "dt_admissao": {
          "description": "Data da admissão do(a) funcionário(a) na empresa.  Formato: (datetime universal Ex.: \"2005-04-10T23:33:11.168Z\" ). Obrigatório.",
          "type": "string",
          "format": "date-time"
        },
        "dt_ultimo_exame": {
          "description": "Data do nascimento do(a) funcionário(a).  Formato: (datetime universal Ex.: \"2022-02-01T23:33:11.168Z\" ). Opcional.",
          "type": "string",
          "format": "date-time"
        },
        "fl_sexo": {
          "description": "Gênero/Sexo do funcionário(a). Valores válidos M,F (M = Masculino, F = Feminino). Formato: (string). Máx. de 1 caractere. Obrigatório.",
          "type": "string"
        },
        "nr_matricula": {
          "description": "Matrícula do(a) funcionário(a) – Igual à do eSocial.  Formato: (string). Máx. de 30 caracteres. Obrigatório para contratos com vínculo (Categoria de trabalhador = 101)",
          "type": "string"
        },
        "cd_categoria_trabalhador": {
          "description": "Código de categoria do trabalhador. Formato: (integer). Tabela oficial: https://www.gov.br/esocial/pt-br/documentacao-tecnica/leiautes-esocial-versao-s-1-3-cons-ate-nt-04-2025-rev-26-08-2025/index.html/tabelas.html#01 . Obrigatório se não enviar a matrícula",
          "type": "integer",
          "format": "int32"
        },
        "nr_cpf": {
          "description": "CPF do colaborador. Formato: (string). Sem máscaras. Máx. de 20 caracteres. Obrigatório.",
          "type": "string"
        },
        "fl_consentimento_lgpd": {
          "description": "Indicação se há consentimento do funcionário em compartilhar os dados de saúde ocupacional. Valores válidos S, N (S = Sim, N = Não). Formato: (string). Máx. de 1 caractere. Obrigatório.",
          "type": "string"
        },
        "ds_email": {
          "description": "Email do funcionário. Formato: (string). Máx. de 100 caracteres.",
          "type": "string"
        },
        "fl_status": {
          "description": "Status da Função. Valores válidos A,I,F,T (A = Ativo, I = Inativo/Demitido, F = Férias, T = Afastado). Formato: (string). Máx. de 1 caractere. Obrigatório.",
          "type": "string"
        },
        "fl_tipo": {
          "description": "Tipo de cadastro, Funcionário efetivo ou Candidato à vaga. Valores válidos F, C (F = funcionário, C = Candidato). Formato: (string). Máx. de 1 caractere. Obrigatório.",
          "type": "string"
        }
      },
      "xml": {
        "name": "RequestAtualizacaoFuncionario"
      },
      "type": "object"
    },
    "ResponseConsultaEmpresa": {
      "properties": {
        "cd_grupo": {
          "type": "integer",
          "format": "int32"
        },
        "cd_empresa": {
          "type": "integer",
          "format": "int32"
        },
        "nr_cnpj": {
          "type": "string"
        },
        "nm_fantasia": {
          "type": "string"
        },
        "nm_razao_social": {
          "type": "string"
        },
        "ds_cep": {
          "type": "string"
        },
        "ds_logradouro": {
          "type": "string"
        },
        "ds_numero": {
          "type": "string"
        },
        "ds_complemento": {
          "type": "string"
        },
        "ds_bairro": {
          "type": "string"
        },
        "cd_cidade": {
          "type": "integer",
          "format": "int32"
        },
        "cd_estado": {
          "type": "integer",
          "format": "int32"
        },
        "cd_cnae": {
          "type": "string"
        },
        "nr_grau_risco": {
          "type": "integer",
          "format": "int32"
        },
        "cd_medico_coordenador": {
          "type": "integer",
          "format": "int32"
        },
        "nm_medico_coordenador": {
          "type": "string"
        },
        "nr_crm": {
          "type": "string"
        },
        "cd_estado_medico": {
          "type": "integer",
          "format": "int32"
        },
        "nr_cpf_medico": {
          "type": "string"
        },
        "ds_telefone": {
          "type": "string"
        },
        "fl_status": {
          "type": "string"
        },
        "fl_regra_padrao_pcmso": {
          "type": "boolean"
        },
        "cd_grupo_esocial": {
          "type": "integer",
          "format": "int32"
        },
        "cd_user_manu": {
          "type": "integer",
          "format": "int32"
        },
        "ts_user_manu": {
          "type": "string"
        }
      },
      "xml": {
        "name": "ResponseConsultaEmpresa"
      },
      "type": "object"
    },
    "RequestCadastroEmpresa": {
      "description": "RequestCadastroEmpresa = Objeto para cadastrar a Empresa.",
      "required": [
        "cd_grupo",
        "nm_fantasia",
        "ds_cep",
        "ds_logradouro",
        "ds_numero",
        "ds_complemento",
        "ds_bairro",
        "nr_cnpj_cpf",
        "cd_cnae",
        "nm_razao_social_empregador",
        "fl_regra_padrao_pcmso",
        "cd_grupo_esocial"
      ],
      "properties": {
        "cd_grupo": {
          "description": "Código do grupo na plataforma Indexmed. Formato: (integer).Obrigatório.",
          "type": "integer",
          "format": "int32"
        },
        "nm_fantasia": {
          "description": "Nome fantasia da empresa (ajuda a identificar nos selects do aplicativo). Formato: (string). Máx. de 100 caracteres. Obrigatório.",
          "type": "string"
        },
        "ds_cep": {
          "description": "CEP da empresa. Formato: (string). Máx. de 9 caracteres. Obrigatório.",
          "type": "string"
        },
        "ds_logradouro": {
          "description": "Rua ou avenida do endereço da empresa. Formato: (string). Máx. de 140 caracteres. Obrigatório.",
          "type": "string"
        },
        "ds_numero": {
          "description": "Número do endereço da empresa. Formato: (string). Máx. de 6 caracteres. Obrigatório.",
          "type": "string"
        },
        "ds_complemento": {
          "description": "Complemento do endereço da empresa. Formato: (string). Máx. de 60 caracteres. Obrigatório.",
          "type": "string"
        },
        "ds_bairro": {
          "description": "Bairro do endereço da empresa. Formato: (string). Máx. de 80 caracteres. Obrigatório.",
          "type": "string"
        },
        "nr_cnpj_cpf": {
          "description": "CNPJ da Empresa ou CPF do Empregador. Sem máscaras. Formato: (string). Máx. de 20 caracteres. Obrigatório.",
          "type": "string"
        },
        "cd_cnae": {
          "description": "CNAE da Empresa (5 dígitos) relativo ao anexo da NR-4. Formato: (string). Máx. de 5 caracteres.Obrigatório.",
          "type": "string"
        },
        "nm_razao_social_empregador": {
          "description": "Razão social empresa ou nome do empregador. Formato: (string). Máx. de 100 caracteres. Obrigatório.",
          "type": "string"
        },
        "nr_crm": {
          "description": "CRM do Médico Responsável pelo PCMSO da empresa. Formato: (string). Máx. de 15 caracteres. Opcional.",
          "type": "string"
        },
        "uf_crm": {
          "description": "Sigla do estado do CRM do Médico Responsável pelo PCMSO da empresa. Formato: (string). Máx. de 1 caractere. Opcional.",
          "type": "string"
        },
        "nm_medico_coordenador": {
          "description": "Nome do Médico Responsável pelo PCMSO da empresa. Formato: (string). Máx. de 80 caracteres. Opcional.",
          "type": "string"
        },
        "ds_telefone_medico": {
          "description": "Telefone do Médico Responsável pelo PCMSO da empresa. Formato: (string). Máx. de 20 caracteres. Opcional.",
          "type": "string"
        },
        "fl_regra_padrao_pcmso": {
          "description": "Aplicação da Regra padrão do PCMSO na Empresa (Protocolo de exames básico). Formato: (boleano). Valores válidos: true ou false. Obrigatório.",
          "type": "boolean"
        },
        "cd_grupo_esocial": {
          "description": "Grupo econômico que a empresa se enquadra no eSocial (valores válidos 1, 2, ou 3). Formato: (integer). Obrigatório.",
          "type": "integer",
          "format": "int32"
        }
      },
      "xml": {
        "name": "RequestCadastroEmpresa"
      },
      "type": "object"
    },
    "ResponseCadastroEmpresa": {
      "properties": {
        "cd_empresa": {
          "type": "integer",
          "format": "int32"
        },
        "return_msgm": {
          "type": "string"
        },
        "return_code": {
          "type": "integer",
          "format": "int32"
        },
        "request_id": {
          "example": "00000000-0000-0000-0000-000000000000",
          "type": "string",
          "format": "uuid"
        }
      },
      "xml": {
        "name": "ResponseCadastroEmpresa"
      },
      "type": "object"
    },
    "RequestAtualizacaoEmpresa": {
      "description": "RequestAtualizacaoEmpresa = Objeto para atualização da Empresa.",
      "required": [
        "cd_empresa",
        "cd_grupo",
        "nm_fantasia",
        "ds_cep",
        "ds_logradouro",
        "ds_numero",
        "ds_complemento",
        "ds_bairro",
        "nr_cnpj_cpf",
        "cd_cnae",
        "nm_razao_social",
        "fl_regra_padrao_pcmso",
        "cd_grupo_esocial",
        "fl_status"
      ],
      "properties": {
        "cd_empresa": {
          "description": "Código da empresa/empregador na plataforma Indexmed. Formato: (integer).Obrigatório.",
          "type": "integer",
          "format": "int32"
        },
        "cd_grupo": {
          "description": "Código do grupo na plataforma Indexmed. Formato: (integer).Obrigatório.",
          "type": "integer",
          "format": "int32"
        },
        "nm_fantasia": {
          "description": "Nome fantasia da empresa (ajuda a identificar nos selects do aplicativo). Formato: (string). Máx. de 100 caracteres. Obrigatório.",
          "type": "string"
        },
        "ds_cep": {
          "description": "CEP da empresa. Formato: (string). Máx. de 9 caracteres. Obrigatório.",
          "type": "string"
        },
        "ds_logradouro": {
          "description": "Rua ou avenida do endereço da empresa. Formato: (string). Máx. de 140 caracteres. Obrigatório.",
          "type": "string"
        },
        "ds_numero": {
          "description": "Número do endereço da empresa. Formato: (string). Máx. de 6 caracteres. Obrigatório.",
          "type": "string"
        },
        "ds_complemento": {
          "description": "Complemento do endereço da empresa. Formato: (string). Máx. de 60 caracteres. Obrigatório.",
          "type": "string"
        },
        "ds_bairro": {
          "description": "Bairro do endereço da empresa. Formato: (string). Máx. de 80 caracteres. Obrigatório.",
          "type": "string"
        },
        "nr_cnpj_cpf": {
          "description": "CNPJ da Empresa ou CPF do Empregador. Sem máscaras. Formato: (string). Máx. de 20 caracteres. Obrigatório.",
          "type": "string"
        },
        "cd_cnae": {
          "description": "CNAE da Empresa (5 dígitos) relativo ao anexo da NR-4. Formato: (string). Máx. de 5 caracteres.Obrigatório.",
          "type": "string"
        },
        "nm_razao_social": {
          "description": "Razão social empresa ou nome do empregador. Formato: (string). Máx. de 100 caracteres. Obrigatório.",
          "type": "string"
        },
        "nr_crm": {
          "description": "CRM do Médico Responsável pelo PCMSO da empresa. Formato: (string). Máx. de 15 caracteres. Opcional.",
          "type": "string"
        },
        "uf_crm": {
          "description": "Sigla do estado do CRM do Médico Responsável pelo PCMSO da empresa. Formato: (string). Máx. de 1 caractere. Opcional.",
          "type": "string"
        },
        "nm_medico_coordenador": {
          "description": "Nome do Médico Responsável pelo PCMSO da empresa. Formato: (string). Máx. de 80 caracteres. Opcional.",
          "type": "string"
        },
        "ds_telefone_medico": {
          "description": "Telefone do Médico Responsável pelo PCMSO da empresa. Formato: (string). Máx. de 20 caracteres. Opcional.",
          "type": "string"
        },
        "fl_regra_padrao_pcmso": {
          "description": "Aplicação da Regra padrão do PCMSO na Empresa (Protocolo de exames básico). Formato: (boleano). Valores válidos: true ou false. Obrigatório.",
          "type": "boolean"
        },
        "cd_grupo_esocial": {
          "description": "Grupo econômico que a empresa se enquadra no eSocial (valores válidos 1, 2, ou 3). Formato: (integer). Obrigatório.",
          "type": "integer",
          "format": "int32"
        },
        "fl_status": {
          "description": "Status da Empresa (valores válidos \"A\" = Ativo e \"I\" = Inativo). Formato: (string). Máx. de 1 caractere. Obrigatório.",
          "type": "string"
        }
      },
      "xml": {
        "name": "RequestAtualizacaoEmpresa"
      },
      "type": "object"
    },
    "EventoAsoEsocial": {
      "properties": {
        "EvtMonit": {
          "$ref": "#/definitions/EvtMonit"
}
},
"xml": {
"name": "EventoAsoEsocial"
},
"type": "object"
},
"EvtMonit": {
"properties": {
"ideEvento": {
"$ref": "#/definitions/IdeEvento"
        },
        "ideEmpregador": {
          "$ref": "#/definitions/IdeEmpregador"
},
"ideVinculo": {
"$ref": "#/definitions/IdeVinculo"
        },
        "exMedOcup": {
          "$ref": "#/definitions/ExMedOcup"
},
"Id": {
"type": "string"
}
},
"xml": {
"name": "EvtMonit"
},
"type": "object"
},
"IdeEvento": {
"properties": {
"indRetif": {
"type": "string"
},
"nrRecibo": {
"type": "string"
},
"tpAmb": {
"type": "integer",
"format": "int32"
},
"procEmi": {
"type": "integer",
"format": "int32"
},
"verProc": {
"type": "string"
}
},
"xml": {
"name": "IdeEvento"
},
"type": "object"
},
"IdeEmpregador": {
"properties": {
"tpInsc": {
"type": "integer",
"format": "int32"
},
"nrInsc": {
"type": "string"
}
},
"xml": {
"name": "IdeEmpregador"
},
"type": "object"
},
"IdeVinculo": {
"properties": {
"cpfTrab": {
"type": "string"
},
"matricula": {
"type": "string"
},
"codCateg": {
"type": "string"
}
},
"xml": {
"name": "IdeVinculo"
},
"type": "object"
},
"ExMedOcup": {
"properties": {
"tpExameOcup": {
"type": "integer",
"format": "int32"
},
"aso": {
"$ref": "#/definitions/Aso"
        },
        "respMonit": {
          "$ref": "#/definitions/RespMonit"
}
},
"xml": {
"name": "ExMedOcup"
},
"type": "object"
},
"Aso": {
"properties": {
"dtAso": {
"type": "string"
},
"resAso": {
"type": "integer",
"format": "int32"
},
"exame": {
"items": {
"$ref": "#/definitions/Exame"
          },
          "xml": {
            "name": "Exame",
            "wrapped": true
          },
          "type": "array"
        },
        "medico": {
          "$ref": "#/definitions/Medico"
}
},
"xml": {
"name": "Aso"
},
"type": "object"
},
"RespMonit": {
"properties": {
"cpfResp": {
"type": "string"
},
"nmResp": {
"type": "string"
},
"nrCRM": {
"type": "string"
},
"ufCRM": {
"type": "string"
}
},
"xml": {
"name": "RespMonit"
},
"type": "object"
},
"Exame": {
"properties": {
"dtExm": {
"type": "string"
},
"procRealizado": {
"type": "string"
},
"obsProc": {
"type": "string"
},
"ordExame": {
"type": "string"
},
"indResult": {
"type": "string"
}
},
"xml": {
"name": "Exame"
},
"type": "object"
},
"Medico": {
"properties": {
"nmMed": {
"type": "string"
},
"cpfMed": {
"type": "string"
},
"nisMed": {
"type": "string"
},
"nrCRM": {
"type": "string"
},
"ufCRM": {
"type": "string"
}
},
"xml": {
"name": "Medico"
},
"type": "object"
},
"EventoFatoresRiscoEsocial": {
"properties": {
"EvtExpRisco": {
"$ref": "#/definitions/EvtExpRisco"
        }
      },
      "xml": {
        "name": "EventoFatoresRiscoEsocial"
      },
      "type": "object"
    },
    "EvtExpRisco": {
      "properties": {
        "ideEvento": {
          "$ref": "#/definitions/IdeEvento"
},
"ideEmpregador": {
"$ref": "#/definitions/IdeEmpregador"
        },
        "ideVinculo": {
          "$ref": "#/definitions/IdeVinculo"
},
"infoExpRisco": {
"$ref": "#/definitions/ExpRisco"
        },
        "Id": {
          "type": "string"
        }
      },
      "xml": {
        "name": "EvtExpRisco"
      },
      "type": "object"
    },
    "ExpRisco": {
      "properties": {
        "dtIniCondicao": {
          "type": "string"
        },
        "infoAmb": {
          "$ref": "#/definitions/InfoAmb"
},
"infoAtiv": {
"$ref": "#/definitions/InfoAtiv"
        },
        "agNoc": {
          "items": {
            "$ref": "#/definitions/AgNoc"
},
"xml": {
"name": "AgNoc",
"wrapped": true
},
"type": "array"
},
"respReg": {
"$ref": "#/definitions/RespReg"
        },
        "obs": {
          "$ref": "#/definitions/Obs"
}
},
"xml": {
"name": "ExpRisco"
},
"type": "object"
},
"InfoAmb": {
"properties": {
"localAmb": {
"type": "integer",
"format": "int32"
},
"dscSetor": {
"type": "string"
},
"tpInsc": {
"type": "integer",
"format": "int32"
},
"nrInsc": {
"type": "string"
}
},
"xml": {
"name": "InfoAmb"
},
"type": "object"
},
"InfoAtiv": {
"properties": {
"dscAtivDes": {
"type": "string"
}
},
"xml": {
"name": "InfoAtiv"
},
"type": "object"
},
"AgNoc": {
"properties": {
"codAgNoc": {
"type": "string"
},
"dscAgNoc": {
"type": "string"
},
"tpAval": {
"type": "string"
},
"intConc": {
"type": "string"
},
"limTol": {
"type": "string"
},
"unMed": {
"type": "string"
},
"tecMedicao": {
"type": "string"
},
"nrProcJud": {
"type": "string"
},
"epcEpi": {
"$ref": "#/definitions/EpcEpi"
        }
      },
      "xml": {
        "name": "AgNoc"
      },
      "type": "object"
    },
    "RespReg": {
      "properties": {
        "cpfResp": {
          "type": "string"
        },
        "ideOC": {
          "type": "string"
        },
        "dscOC": {
          "type": "string"
        },
        "nrOC": {
          "type": "string"
        },
        "ufOC": {
          "type": "string"
        }
      },
      "xml": {
        "name": "RespReg"
      },
      "type": "object"
    },
    "Obs": {
      "properties": {
        "obsCompl": {
          "type": "string"
        }
      },
      "xml": {
        "name": "Obs"
      },
      "type": "object"
    },
    "EpcEpi": {
      "properties": {
        "utilizEPC": {
          "type": "integer",
          "format": "int32"
        },
        "eficEpc": {
          "type": "string"
        },
        "utilizEPI": {
          "type": "integer",
          "format": "int32"
        },
        "eficEpi": {
          "type": "string"
        },
        "epi": {
          "items": {
            "$ref": "#/definitions/EpiEvento"
},
"xml": {
"name": "EpiEvento",
"wrapped": true
},
"type": "array"
},
"epiCompl": {
"$ref": "#/definitions/EpiComplEvento"
        }
      },
      "xml": {
        "name": "EpcEpi"
      },
      "type": "object"
    },
    "EpiEvento": {
      "properties": {
        "docAval": {
          "type": "string"
        },
        "dscEPI": {
          "type": "string"
        }
      },
      "xml": {
        "name": "EpiEvento"
      },
      "type": "object"
    },
    "EpiComplEvento": {
      "properties": {
        "medProtecao": {
          "type": "string"
        },
        "condFuncto": {
          "type": "string"
        },
        "usoInint": {
          "type": "string"
        },
        "przValid": {
          "type": "string"
        },
        "periodicTroca": {
          "type": "string"
        },
        "higienizacao": {
          "type": "string"
        }
      },
      "xml": {
        "name": "EpiComplEvento"
      },
      "type": "object"
    },
    "EventoCatEsocial": {
      "properties": {
        "EvtCAT": {
          "$ref": "#/definitions/EvtCAT"
}
},
"xml": {
"name": "EventoCatEsocial"
},
"type": "object"
},
"EvtCAT": {
"properties": {
"ideEvento": {
"$ref": "#/definitions/IdeEvento"
        },
        "ideEmpregador": {
          "$ref": "#/definitions/IdeEmpregador"
},
"ideVinculo": {
"$ref": "#/definitions/IdeVinculo"
        },
        "cat": {
          "$ref": "#/definitions/CatAcidente"
},
"Id": {
"type": "string"
}
},
"xml": {
"name": "EvtCAT"
},
"type": "object"
},
"CatAcidente": {
"properties": {
"dtAcid": {
"type": "string"
},
"tpAcid": {
"type": "integer",
"format": "int32"
},
"dstpAcid": {
"type": "string"
},
"hrAcid": {
"type": "string"
},
"hrsTrabAntesAcid": {
"type": "string"
},
"tpCat": {
"type": "integer",
"format": "int32"
},
"dstpCat": {
"type": "string"
},
"indCatObito": {
"type": "string"
},
"dtObito": {
"type": "string"
},
"indComunPolicia": {
"type": "string"
},
"codSitGeradora": {
"type": "integer",
"format": "int32"
},
"iniciatCAT": {
"type": "integer",
"format": "int32"
},
"obsCAT": {
"type": "string"
},
"ultDiaTrab": {
"type": "string"
},
"houveAfast": {
"type": "string"
},
"localAcidente": {
"$ref": "#/definitions/LocalAcidente"
        },
        "parteAtingida": {
          "$ref": "#/definitions/ParteAtingida"
},
"agenteCausador": {
"$ref": "#/definitions/AgenteCausador"
        },
        "atestado": {
          "$ref": "#/definitions/Atestado"
},
"catOrigem": {
"$ref": "#/definitions/CatOrigem"
        }
      },
      "xml": {
        "name": "CatAcidente"
      },
      "type": "object"
    },
    "LocalAcidente": {
      "properties": {
        "tpLocal": {
          "type": "integer",
          "format": "int32"
        },
        "dstpLocal": {
          "type": "string"
        },
        "dscLocal": {
          "type": "string"
        },
        "tpLograd": {
          "type": "string"
        },
        "dscLograd": {
          "type": "string"
        },
        "nrLograd": {
          "type": "string"
        },
        "complemento": {
          "type": "string"
        },
        "bairro": {
          "type": "string"
        },
        "cep": {
          "type": "string"
        },
        "codMunic": {
          "type": "integer",
          "format": "int32"
        },
        "uf": {
          "type": "string"
        },
        "pais": {
          "type": "string"
        },
        "codPostal": {
          "type": "string"
        },
        "ideLocalAcid": {
          "$ref": "#/definitions/IdeLocalAcid"
}
},
"xml": {
"name": "LocalAcidente"
},
"type": "object"
},
"ParteAtingida": {
"properties": {
"codParteAting": {
"type": "integer",
"format": "int32"
},
"lateralidade": {
"type": "string"
}
},
"xml": {
"name": "ParteAtingida"
},
"type": "object"
},
"AgenteCausador": {
"properties": {
"codAgntCausador": {
"type": "string"
}
},
"xml": {
"name": "AgenteCausador"
},
"type": "object"
},
"Atestado": {
"properties": {
"dtAtendimento": {
"type": "string"
},
"hrAtendimento": {
"type": "string"
},
"indInternacao": {
"type": "string"
},
"durTrat": {
"type": "string"
},
"indAfast": {
"type": "string"
},
"dscLesao": {
"type": "string"
},
"dscCompLesao": {
"type": "string"
},
"diagProvavel": {
"type": "string"
},
"codCID": {
"type": "string"
},
"observacao": {
"type": "string"
},
"emitente": {
"$ref": "#/definitions/Emitente"
        }
      },
      "xml": {
        "name": "Atestado"
      },
      "type": "object"
    },
    "CatOrigem": {
      "properties": {
        "nrRecCatOrig": {
          "type": "string"
        }
      },
      "xml": {
        "name": "CatOrigem"
      },
      "type": "object"
    },
    "IdeLocalAcid": {
      "properties": {
        "tpInsc": {
          "type": "integer",
          "format": "int32"
        },
        "nrInsc": {
          "type": "string"
        }
      },
      "xml": {
        "name": "IdeLocalAcid"
      },
      "type": "object"
    },
    "Emitente": {
      "properties": {
        "nmEmit": {
          "type": "string"
        },
        "ideOC": {
          "type": "string"
        },
        "nrOC": {
          "type": "string"
        },
        "ufOC": {
          "type": "string"
        }
      },
      "xml": {
        "name": "Emitente"
      },
      "type": "object"
    },
    "ResponseConsultaFuncao": {
      "properties": {
        "cd_empresa": {
          "type": "integer",
          "format": "int32"
        },
        "cd_funcao": {
          "type": "integer",
          "format": "int32"
        },
        "nm_funcao": {
          "type": "string"
        },
        "cd_interno_funcao": {
          "type": "string"
        },
        "cd_cbo": {
          "type": "string"
        },
        "ds_funcao": {
          "type": "string"
        },
        "fl_status": {
          "type": "string"
        },
        "cd_user_manu": {
          "type": "integer",
          "format": "int32"
        },
        "ts_user_manu": {
          "type": "string"
        }
      },
      "xml": {
        "name": "ResponseConsultaFuncao"
      },
      "type": "object"
    },
    "RequestCadastroFuncao": {
      "description": "RequestCadastroFuncao = Objeto para cadastro da Função / Cargo.",
      "required": [
        "nm_funcao",
        "ds_funcao",
        "fl_status"
      ],
      "properties": {
        "cd_empresa": {
          "description": "Código da empresa/empregador na plataforma Indexmed. Formato: (integer). Obrigatório se não for enviado o CNPJ.",
          "type": "integer",
          "format": "int32"
        },
        "nr_cnpj": {
          "description": "CNPJ da empresa ou CPF do empregador. Formato: (string). Máx. de 20 caracteres. Obrigatório se não for enviado o cd_empresa.",
          "type": "string"
        },
        "nm_funcao": {
          "description": "Nome da função. Formato: (string). Máx. de 100 caracteres. Obrigatório.",
          "type": "string"
        },
        "ds_funcao": {
          "description": "Descrição da Função que será enviada ao eSocial. Formato: (string). Máx. de 2000 caracteres. Obrigatório.",
          "type": "string"
        },
        "cd_interno_funcao": {
          "description": "Código da função (de outro sistema, para relação bidirecional). Formato: (string). Máx. de 20 caracteres. Obrigatório se não foi enviado o cd_funcao.",
          "type": "string"
        },
        "nr_cbo": {
          "description": "Código Brasileiro de Ocupações da função. Formato: (string). Máx. de 10 caracteres. Opcional.",
          "type": "string"
        },
        "fl_status": {
          "description": "Status da Função (valores válidos \"A\" = ATIVO e \"I\" = Inativo). Formato: (string). Máx. de 1 caractere. Obrigatório.",
          "type": "string"
        }
      },
      "xml": {
        "name": "RequestCadastroFuncao"
      },
      "type": "object"
    },
    "ResponseCadastroFuncao": {
      "properties": {
        "cd_funcao": {
          "type": "integer",
          "format": "int32"
        },
        "return_msgm": {
          "type": "string"
        },
        "return_code": {
          "type": "integer",
          "format": "int32"
        },
        "request_id": {
          "example": "00000000-0000-0000-0000-000000000000",
          "type": "string",
          "format": "uuid"
        }
      },
      "xml": {
        "name": "ResponseCadastroFuncao"
      },
      "type": "object"
    },
    "RequestAtualizacaoFuncao": {
      "description": "RequestAtualizacaoFuncao = Objeto para atualização da Função / Cargo.",
      "required": [
        "nm_funcao",
        "ds_funcao",
        "fl_status"
      ],
      "properties": {
        "cd_funcao": {
          "description": "Código da Função na plataforma Indexmed. Formato: (integer). Obrigatório se foi enviado o cd_interno_funcao.",
          "type": "integer",
          "format": "int32"
        },
        "cd_empresa": {
          "description": "Código da empresa/empregador na plataforma Indexmed. Formato: (integer). Obrigatório se não for enviado o CNPJ.",
          "type": "integer",
          "format": "int32"
        },
        "nr_cnpj": {
          "description": "CNPJ da empresa ou CPF do empregador. Formato: (string). Máx. de 20 caracteres. Obrigatório se não for enviado o cd_empresa.",
          "type": "string"
        },
        "nm_funcao": {
          "description": "Nome da função. Formato: (string). Máx. de 100 caracteres. Obrigatório.",
          "type": "string"
        },
        "ds_funcao": {
          "description": "Descrição da Função que será enviada ao eSocial. Formato: (string). Máx. de 2000 caracteres. Obrigatório.",
          "type": "string"
        },
        "cd_interno_funcao": {
          "description": "Código da função (de outro sistema, para relação bidirecional). Formato: (string). Máx. de 20 caracteres. Obrigatório se não foi enviado o cd_funcao.",
          "type": "string"
        },
        "nr_cbo": {
          "description": "Código Brasileiro de Ocupações da função. Formato: (string). Máx. de 10 caracteres. Opcional.",
          "type": "string"
        },
        "fl_status": {
          "description": "Status da Função (valores válidos \"A\" = ATIVO e \"I\" = Inativo). Formato: (string). Máx. de 1 caractere. Obrigatório.",
          "type": "string"
        }
      },
      "xml": {
        "name": "RequestAtualizacaoFuncao"
      },
      "type": "object"
    },
    "ResponseConsultaGrupo": {
      "properties": {
        "cd_grupo": {
          "type": "integer",
          "format": "int32"
        },
        "nm_grupo": {
          "type": "string"
        },
        "fl_status": {
          "type": "string"
        },
        "cd_user_manu": {
          "type": "integer",
          "format": "int32"
        },
        "ts_user_manu": {
          "type": "string"
        }
      },
      "xml": {
        "name": "ResponseConsultaGrupo"
      },
      "type": "object"
    },
    "RequestCadastroGrupo": {
      "description": "RequestCadastroGrupo = Objeto para cadastrar o Grupo/Cliente.",
      "required": [
        "nm_grupo"
      ],
      "properties": {
        "nm_grupo": {
          "description": "Nome do Grupo/Cliente no Aplicativo Indexmed. Obrigatório. Formato: (string). Máx.de 100 caracteres.",
          "type": "string"
        }
      },
      "xml": {
        "name": "RequestCadastroGrupo"
      },
      "type": "object"
    },
    "ResponseCadastroGrupo": {
      "properties": {
        "cd_grupo": {
          "type": "integer",
          "format": "int32"
        },
        "return_msgm": {
          "type": "string"
        },
        "return_code": {
          "type": "integer",
          "format": "int32"
        },
        "request_id": {
          "example": "00000000-0000-0000-0000-000000000000",
          "type": "string",
          "format": "uuid"
        }
      },
      "xml": {
        "name": "ResponseCadastroGrupo"
      },
      "type": "object"
    },
    "RequestAtualizacaoGrupo": {
      "description": "RequestAtualizacaoGrupo = Objeto para atualizar a Empresa.",
      "required": [
        "cd_grupo",
        "nm_grupo",
        "fl_status"
      ],
      "properties": {
        "cd_grupo": {
          "description": "Código do grupo na plataforma Indexmed. Formato: (integer). Obrigatório.",
          "type": "integer",
          "format": "int32"
        },
        "nm_grupo": {
          "description": "Nome do Grupo/Cliente no Aplicativo Indexmed. Formato: (string). Máx.de 100 caracteres. Obrigatório.",
          "type": "string"
        },
        "fl_status": {
          "description": "Status do Grupo/Cliente (valores válidos \"A\" = ATIVO e \"I\" = Inativo. Formato: (string). Máx. de 1 caractere. Obrigatório.",
          "type": "string"
        }
      },
      "xml": {
        "name": "RequestAtualizacaoGrupo"
      },
      "type": "object"
    },
    "RequestImportarFichaClinica": {
      "properties": {
        "cpf_funcionario": {
          "type": "string"
        },
        "dtExame": {
          "type": "string",
          "format": "date-time"
        },
        "tabagismo": {
          "type": "string"
        },
        "bebidas_alcoolicas": {
          "type": "string"
        },
        "atividade_fisica": {
          "type": "string"
        },
        "qual_atividade_fisica": {
          "type": "string"
        },
        "frequencia_irritado_estressado": {
          "type": "string"
        },
        "relato_patologias": {
          "type": "string"
        },
        "relato_patologias_outros": {
          "type": "string"
        },
        "fazendo_tratamento": {
          "type": "string"
        },
        "qual_tratamento": {
          "type": "string"
        },
        "ultima_consulta_acompanhamento": {
          "type": "string"
        },
        "usa_medicamento": {
          "type": "string"
        },
        "qual_medicamento": {
          "type": "string"
        },
        "doencas_anteriores_cirurgias": {
          "type": "string"
        },
        "doencas_familia": {
          "type": "string"
        },
        "doencas_familia_outros": {
          "type": "string"
        },
        "hobby_atividade": {
          "type": "string"
        },
        "hobby_atividade_outros": {
          "type": "string"
        },
        "atividades_trabalho_anteriores": {
          "type": "string"
        },
        "tempo_atividades_trabalho_anteriores": {
          "type": "string"
        },
        "possui_cat_anterior": {
          "type": "string"
        },
        "motivo_cat_anterior": {
          "type": "string"
        },
        "doenca_ocupacional": {
          "type": "string"
        },
        "qual_doenca_ocupacional": {
          "type": "string"
        },
        "afastamento_previdenciario": {
          "type": "string"
        },
        "motivo_afastamento": {
          "type": "string"
        },
        "pcd_reabilitado_inss": {
          "type": "string"
        },
        "quantos_empregos": {
          "type": "integer",
          "format": "int32"
        },
        "quantas_horas_trabalho_dia": {
          "type": "number",
          "format": "double"
        },
        "queixas": {
          "type": "string"
        },
        "qual_queixas": {
          "type": "string"
        },
        "outras_queixas": {
          "type": "string"
        },
        "pressao_sistolica": {
          "type": "integer",
          "format": "int32"
        },
        "pressao_diastolica": {
          "type": "integer",
          "format": "int32"
        },
        "peso": {
          "type": "number",
          "format": "double"
        },
        "frequencia_cardiaca": {
          "type": "integer",
          "format": "int32"
        },
        "altura": {
          "type": "number",
          "format": "double"
        },
        "circunferencia_abdominal": {
          "type": "integer",
          "format": "int32"
        },
        "cabeca_pescoco": {
          "type": "string"
        },
        "cabeca_pescoco_obs": {
          "type": "string"
        },
        "olhos": {
          "type": "string"
        },
        "olhos_obs": {
          "type": "string"
        },
        "pele_mucosa": {
          "type": "string"
        },
        "pele_mucosa_obs": {
          "type": "string"
        },
        "respiratorio": {
          "type": "string"
        },
        "respiratorio_obs": {
          "type": "string"
        },
        "cardiovascular": {
          "type": "string"
        },
        "cardiovascular_obs": {
          "type": "string"
        },
        "abdome": {
          "type": "string"
        },
        "abdome_obs": {
          "type": "string"
        },
        "membros_inferiores_extrem": {
          "type": "string"
        },
        "membros_inferiores_extrem_obs": {
          "type": "string"
        },
        "ombros": {
          "type": "string"
        },
        "ombros_obs": {
          "type": "string"
        },
        "maos_punhos": {
          "type": "string"
        },
        "maos_punhos_obs": {
          "type": "string"
        },
        "queixas_necessitam_investigacao": {
          "type": "string"
        },
        "houve_encaminhamento": {
          "type": "string"
        },
        "encaminhamento_especialidade": {
          "type": "string"
        },
        "observacoes_gerais": {
          "type": "string"
        },
        "diagnosticos_possiveis": {
          "type": "string"
        },
        "existe_patologia_ocupacional": {
          "type": "string"
        }
      },
      "xml": {
        "name": "RequestImportarFichaClinica"
      },
      "type": "object"
    },
    "ResponseImportarFichaClinica": {
      "properties": {
        "cd_ficha_clinica": {
          "type": "integer",
          "format": "int32"
        },
        "return_msgm": {
          "type": "string"
        },
        "return_code": {
          "type": "integer",
          "format": "int32"
        }
      },
      "xml": {
        "name": "ResponseImportarFichaClinica"
      },
      "type": "object"
    },
    "RequestImportarLoteEventos": {
      "properties": {
        "cd_empresa": {
          "type": "integer",
          "format": "int32"
        },
        "protocoloEnvio": {
          "type": "string"
        },
        "dhRecepcao": {
          "type": "string",
          "format": "date-time"
        },
        "Eventos": {
          "items": {
            "$ref": "#/definitions/Eventos"
},
"xml": {
"name": "Eventos",
"wrapped": true
},
"type": "array"
}
},
"xml": {
"name": "RequestImportarLoteEventos"
},
"type": "object"
},
"Eventos": {
"properties": {
"id_evento": {
"type": "string"
},
"cd_funcionario": {
"type": "integer",
"format": "int32"
},
"cd_tipo_evento": {
"type": "integer",
"format": "int32"
},
"dt_evento": {
"type": "string",
"format": "date-time"
},
"nrRecibo": {
"type": "string"
},
"xml_evento": {
"type": "string"
}
},
"xml": {
"name": "Eventos"
},
"type": "object"
},
"ResponseImportarLoteEventos": {
"properties": {
"cd_lote": {
"type": "integer",
"format": "int32"
},
"return_msgm": {
"type": "string"
},
"return_code": {
"type": "integer",
"format": "int32"
}
},
"xml": {
"name": "ResponseImportarLoteEventos"
},
"type": "object"
},
"RequestInformarExames": {
"properties": {
"cd_funcionario": {
"type": "integer",
"format": "int32"
},
"cd_tipo_exame_clinico": {
"type": "integer",
"format": "int32"
},
"dtAso": {
"type": "string",
"format": "date-time"
},
"resAso": {
"type": "integer",
"format": "int32"
},
"fl_apto_altura": {
"type": "string"
},
"fl_apto_confinado": {
"type": "string"
},
"fl_apto_alimentos": {
"type": "string"
},
"fl_apto_eletricidade": {
"type": "string"
},
"cd_empresa": {
"type": "integer",
"format": "int32"
},
"cd_profissional": {
"type": "integer",
"format": "int32"
},
"cd_ambulatorio": {
"type": "integer",
"format": "int32"
},
"nm_arquivo_aso": {
"type": "string"
},
"arquivo_aso": {
"type": "string"
},
"nm_arquivo_prontuario": {
"type": "string"
},
"arquivo_prontuario": {
"type": "string"
},
"ExamesComplementares": {
"items": {
"$ref": "#/definitions/TabelaPedidoGuia"
},
"xml": {
"name": "TabelaPedidoGuia",
"wrapped": true
},
"type": "array"
}
},
"xml": {
"name": "RequestInformarExames"
},
"type": "object"
},
"TabelaPedidoGuia": {
"properties": {
"cd_exame": {
"type": "integer",
"format": "int32"
},
"dt_exame": {
"type": "string",
"format": "date-time"
},
"ordExame": {
"type": "integer",
"format": "int32"
},
"fl_resultado": {
"type": "string"
},
"obsProc": {
"type": "string"
}
},
"xml": {
"name": "TabelaPedidoGuia"
},
"type": "object"
},
"ResponseInformarExames": {
"properties": {
"cd_guia": {
"type": "integer",
"format": "int32"
},
"return_msgm": {
"type": "string"
},
"return_code": {
"type": "integer",
"format": "int32"
}
},
"xml": {
"name": "ResponseInformarExames"
},
"type": "object"
},
"ResponseConsultaMedicoCoordenador": {
"properties": {
"cd_grupo": {
"type": "integer",
"format": "int32"
},
"cd_medico_coordenador": {
"type": "integer",
"format": "int32"
},
"nm_medico_coordenador": {
"type": "string"
},
"nr_crm": {
"type": "string"
},
"uf_crm": {
"type": "string"
},
"fl_status": {
"type": "string"
},
"cd_user_manu": {
"type": "integer",
"format": "int32"
},
"ts_user_manu": {
"type": "string"
}
},
"xml": {
"name": "ResponseConsultaMedicoCoordenador"
},
"type": "object"
},
"RequestCadastroMedicoCoordenador": {
"properties": {
"cd_grupo": {
"type": "integer",
"format": "int32"
},
"nm_grupo": {
"type": "string"
},
"cd_empresa": {
"type": "integer",
"format": "int32"
},
"nr_cnpj_empresa": {
"type": "string"
},
"cd_unidade": {
"type": "integer",
"format": "int32"
},
"nr_cnpj_unidade": {
"type": "string"
},
"cd_interno_unidade": {
"type": "string"
},
"nm_medico_coordenador": {
"type": "string"
},
"nr_crm": {
"type": "string"
},
"uf_crm": {
"type": "string"
},
"ds_telefone": {
"type": "string"
}
},
"xml": {
"name": "RequestCadastroMedicoCoordenador"
},
"type": "object"
},
"ResponseCadastroMedicoCoordenador": {
"properties": {
"cd_medico_coordenador": {
"type": "integer",
"format": "int32"
},
"return_msgm": {
"type": "string"
},
"return_code": {
"type": "integer",
"format": "int32"
},
"request_id": {
"example": "00000000-0000-0000-0000-000000000000",
"type": "string",
"format": "uuid"
}
},
"xml": {
"name": "ResponseCadastroMedicoCoordenador"
},
"type": "object"
},
"RequestAtualizacaoMedicoCoordenador": {
"properties": {
"cd_medico_coordenador": {
"type": "integer",
"format": "int32"
},
"cd_grupo": {
"type": "integer",
"format": "int32"
},
"nm_grupo": {
"type": "string"
},
"cd_empresa": {
"type": "integer",
"format": "int32"
},
"nr_cnpj_empresa": {
"type": "string"
},
"cd_unidade": {
"type": "integer",
"format": "int32"
},
"cd_interno_unidade": {
"type": "string"
},
"nr_cnpj_unidade": {
"type": "string"
},
"nm_medico_coordenador": {
"type": "string"
},
"nr_crm": {
"type": "string"
},
"uf_crm": {
"type": "string"
},
"ds_telefone": {
"type": "string"
},
"fl_status": {
"type": "string"
}
},
"xml": {
"name": "RequestAtualizacaoMedicoCoordenador"
},
"type": "object"
},
"ResponseConsultaSetor": {
"properties": {
"cd_empresa": {
"type": "integer",
"format": "int32"
},
"cd_setor": {
"type": "integer",
"format": "int32"
},
"nm_setor": {
"type": "string"
},
"cd_interno_setor": {
"type": "string"
},
"fl_ds_setor_esocial": {
"type": "string"
},
"nr_metro_quadrado": {
"type": "number",
"format": "double"
},
"nr_altura_pe_direito": {
"type": "number",
"format": "double"
},
"cd_tipo_edificacao": {
"type": "integer",
"format": "int32"
},
"cd_tipo_fechamento": {
"type": "integer",
"format": "int32"
},
"cd_tipo_iluminacao": {
"type": "integer",
"format": "int32"
},
"cd_tipo_piso": {
"type": "integer",
"format": "int32"
},
"cd_tipo_ventilacao": {
"type": "integer",
"format": "int32"
},
"cd_tipo_cobertura": {
"type": "integer",
"format": "int32"
},
"fl_status": {
"type": "string"
},
"cd_user_manu": {
"type": "integer",
"format": "int32"
},
"ts_user_manu": {
"type": "string"
}
},
"xml": {
"name": "ResponseConsultaSetor"
},
"type": "object"
},
"RequestCadastroSetor": {
"description": "RequestCadastroSetor = Objeto para cadastro do Setor / Departamento.",
"required": [
"nm_setor",
"fl_ds_setor_esocial",
"fl_status"
],
"properties": {
"cd_empresa": {
"description": "Código da empresa/empregador na plataforma Indexmed. Formato: (integer). Obrigatório se não for enviado o CNPJ.",
"type": "integer",
"format": "int32"
},
"nr_cnpj": {
"description": "CNPJ da empresa ou CPF do empregador. Formato: (string). Máx. de 20 caracteres. Obrigatório se não for enviado o cd_empresa.",
"type": "string"
},
"nm_setor": {
"description": "Nome do Setor na plataforma Indexmed. Formato: (string). Máx. de 100 caracteres. Obrigatório.",
"type": "string"
},
"cd_interno_setor": {
"description": "Código do setor (de outro sistema, para relação bidirecional). Formato: (string). Máx. de 20 caracteres.",
"type": "string"
},
"fl_ds_setor_esocial": {
"description": "Usar a descrição do setor nos eventos do eSocial ao invés do nome do setor. (valores válidos S ou N) S = SIM, N = NÃO. Formato: (string). Máx. de 1 caractere. Obrigatório.",
"type": "string"
},
"ds_setor_esocial": {
"description": "Descrição do Setor que será enviada ao eSocial caso o campo fl_ds_setor_esocial = “S” Formato: (string). Máx. de 100 caracteres.",
"type": "string"
},
"fl_status": {
"description": "Status do Setor (valores válidos \"A\" = ATIVO e \"I\" = Inativo). Formato: (string). Máx. de 1 caractere.",
"type": "string"
}
},
"xml": {
"name": "RequestCadastroSetor"
},
"type": "object"
},
"ResponseCadastroSetor": {
"properties": {
"cd_setor": {
"type": "integer",
"format": "int32"
},
"return_msgm": {
"type": "string"
},
"return_code": {
"type": "integer",
"format": "int32"
},
"request_id": {
"example": "00000000-0000-0000-0000-000000000000",
"type": "string",
"format": "uuid"
}
},
"xml": {
"name": "ResponseCadastroSetor"
},
"type": "object"
},
"RequestAtualizacaoSetor": {
"description": "RequestAtualizacaoSetor = Objeto para atualização do Setor / Departamento.",
"required": [
"nm_setor",
"fl_ds_setor_esocial",
"fl_status"
],
"properties": {
"cd_setor": {
"description": "Código do Setor na plataforma Indexmed. Formato: (integer). Obrigatório se não for enviado o cd_interno_setor.",
"type": "integer",
"format": "int32"
},
"cd_empresa": {
"description": "Código da empresa/empregador na plataforma Indexmed. Formato: (integer). Obrigatório se não for enviado o CNPJ.",
"type": "integer",
"format": "int32"
},
"nr_cnpj": {
"description": "CNPJ da empresa ou CPF do empregador. Formato: (string). Máx. de 20 caracteres. Obrigatório se não for enviado o cd_empresa.",
"type": "string"
},
"nm_setor": {
"description": "Nome do Setor na plataforma Indexmed. Formato: (string). Máx. de 100 caracteres. Obrigatório.",
"type": "string"
},
"cd_interno_setor": {
"description": "Código do setor (de outro sistema, para relação bidirecional). Formato: (string). Máx. de 20 caracteres.",
"type": "string"
},
"fl_ds_setor_esocial": {
"description": "Usar a descrição do setor nos eventos do eSocial ao invés do nome do setor. (valores válidos S ou N) S = SIM, N = NÃO. Formato: (string). Máx. de 1 caractere. Obrigatório.",
"type": "string"
},
"ds_setor_esocial": {
"description": "Descrição do Setor que será enviada ao eSocial caso o campo fl_ds_setor_esocial = “S” Formato: (string). Máx. de 100 caracteres.",
"type": "string"
},
"fl_status": {
"description": "Status do Setor (valores válidos \"A\" = ATIVO e \"I\" = Inativo). Formato: (string). Máx. de 1 caractere.",
"type": "string"
}
},
"xml": {
"name": "RequestAtualizacaoSetor"
},
"type": "object"
},
"ResponseConsultaExame": {
"properties": {
"cd_exame": {
"type": "integer",
"format": "int32"
},
"ds_apelido": {
"type": "string"
},
"ds_exame": {
"type": "string"
},
"fl_status": {
"type": "string"
},
"cd_user_manu": {
"type": "integer",
"format": "int32"
},
"ts_user_manu": {
"type": "string"
}
},
"xml": {
"name": "ResponseConsultaExame"
},
"type": "object"
},
"ResponseConsultaRisco": {
"properties": {
"cd_agente_risco": {
"type": "integer",
"format": "int32"
},
"cd_tipo_risco": {
"type": "integer",
"format": "int32"
},
"ds_tipo_risco": {
"type": "string"
},
"codigo_risco_esocial": {
"type": "string"
},
"ds_agente_risco": {
"type": "string"
},
"ds_agente_risco_esocial": {
"type": "string"
},
"fl_status": {
"type": "string"
},
"cd_user_manu": {
"type": "integer",
"format": "int32"
},
"ts_user_manu": {
"type": "string"
}
},
"xml": {
"name": "ResponseConsultaRisco"
},
"type": "object"
},
"ResponseConsultaEstado": {
"properties": {
"cd_pais": {
"type": "integer",
"format": "int32"
},
"cd_estado": {
"type": "integer",
"format": "int32"
},
"nm_estado": {
"type": "string"
},
"sgl_estado": {
"type": "string"
},
"cd_ibge": {
"type": "integer",
"format": "int32"
}
},
"xml": {
"name": "ResponseConsultaEstado"
},
"type": "object"
},
"ResponseConsultaCidade": {
"properties": {
"cd_estado": {
"type": "integer",
"format": "int32"
},
"cd_cidade": {
"type": "integer",
"format": "int32"
},
"nm_cidade": {
"type": "string"
},
"fl_capital": {
"type": "string"
},
"cd_ibge": {
"type": "integer",
"format": "int32"
}
},
"xml": {
"name": "ResponseConsultaCidade"
},
"type": "object"
},
"ResponseConsultaCnae": {
"properties": {
"cd_cnae": {
"type": "string"
},
"ds_cnae": {
"type": "string"
},
"nr_grau_risco": {
"type": "integer",
"format": "int32"
},
"cd_user_manu": {
"type": "integer",
"format": "int32"
},
"ts_user_manu": {
"type": "string"
}
},
"xml": {
"name": "ResponseConsultaCnae"
},
"type": "object"
},
"ResponseConsultaCbo": {
"properties": {
"cd_cbo": {
"type": "integer",
"format": "int32"
},
"cd_familia_cbo": {
"type": "integer",
"format": "int32"
},
"nm_cbo": {
"type": "string"
},
"nr_cbo": {
"type": "string"
},
"nr_cbo_94": {
"type": "string"
},
"nr_ciuo": {
"type": "string"
},
"sg_cbo": {
"type": "string"
},
"cd_user_manu": {
"type": "integer",
"format": "int32"
},
"ts_user_manu": {
"type": "string"
}
},
"xml": {
"name": "ResponseConsultaCbo"
},
"type": "object"
},
"ResponseConsultaTipoInscricao": {
"properties": {
"cd_tipo_inscricao": {
"type": "integer",
"format": "int32"
},
"nm_tipo_inscricao": {
"type": "string"
}
},
"xml": {
"name": "ResponseConsultaTipoInscricao"
},
"type": "object"
},
"ResponseConsultaTipoLocalTrabalho": {
"properties": {
"cd_tipo_local_trabalho": {
"type": "integer",
"format": "int32"
},
"nm_tipo_local_trabalho": {
"type": "string"
}
},
"xml": {
"name": "ResponseConsultaTipoLocalTrabalho"
},
"type": "object"
},
"ResponseConsultaTipoEdificacao": {
"properties": {
"cd_tipo_edificacao": {
"type": "integer",
"format": "int32"
},
"ds_tipo_edificacao": {
"type": "string"
}
},
"xml": {
"name": "ResponseConsultaTipoEdificacao"
},
"type": "object"
},
"ResponseConsultaTipoCobertura": {
"properties": {
"cd_tipo_cobertura": {
"type": "integer",
"format": "int32"
},
"ds_tipo_cobertura": {
"type": "string"
}
},
"xml": {
"name": "ResponseConsultaTipoCobertura"
},
"type": "object"
},
"ResponseConsultaTipoPiso": {
"properties": {
"cd_tipo_piso": {
"type": "integer",
"format": "int32"
},
"ds_tipo_piso": {
"type": "string"
}
},
"xml": {
"name": "ResponseConsultaTipoPiso"
},
"type": "object"
},
"ResponseConsultaTipoIluminacao": {
"properties": {
"cd_tipo_iluminacao": {
"type": "integer",
"format": "int32"
},
"ds_tipo_iluminacao": {
"type": "string"
}
},
"xml": {
"name": "ResponseConsultaTipoIluminacao"
},
"type": "object"
},
"ResponseConsultaTipoFechamento": {
"properties": {
"cd_tipo_fechamento": {
"type": "integer",
"format": "int32"
},
"ds_tipo_fechamento": {
"type": "string"
}
},
"xml": {
"name": "ResponseConsultaTipoFechamento"
},
"type": "object"
},
"ResponseConsultaTipoVentilacao": {
"properties": {
"cd_tipo_ventilacao": {
"type": "integer",
"format": "int32"
},
"ds_tipo_ventilacao": {
"type": "string"
}
},
"xml": {
"name": "ResponseConsultaTipoVentilacao"
},
"type": "object"
},
"ResponseConsultaCategoriaTrabalhador": {
"properties": {
"cd_categoria_trabalhador": {
"type": "integer",
"format": "int32"
},
"nm_categoria_trabalhador": {
"type": "string"
}
},
"xml": {
"name": "ResponseConsultaCategoriaTrabalhador"
},
"type": "object"
},
"ResponseConsultaUnidade": {
"properties": {
"cd_empresa": {
"type": "integer",
"format": "int32"
},
"cd_unidade": {
"type": "integer",
"format": "int32"
},
"cd_interno_unidade": {
"type": "string"
},
"cd_localAmb": {
"type": "integer",
"format": "int32"
},
"nr_cnpj": {
"type": "string"
},
"nm_fantasia": {
"type": "string"
},
"nm_razao_social": {
"type": "string"
},
"cd_cnae": {
"type": "string"
},
"nr_grau_risco": {
"type": "integer",
"format": "int32"
},
"ds_cep": {
"type": "string"
},
"ds_logradouro": {
"type": "string"
},
"ds_numero": {
"type": "string"
},
"ds_complemento": {
"type": "string"
},
"ds_bairro": {
"type": "string"
},
"cd_cidade": {
"type": "integer",
"format": "int32"
},
"cd_estado": {
"type": "integer",
"format": "int32"
},
"cd_medico_coordenador": {
"type": "integer",
"format": "int32"
},
"nm_medico_coordenador": {
"type": "string"
},
"nr_crm": {
"type": "string"
},
"nr_cpf_medico": {
"type": "string"
},
"ds_telefone": {
"type": "string"
},
"fl_status": {
"type": "string"
},
"cd_user_manu": {
"type": "integer",
"format": "int32"
},
"ts_user_manu": {
"type": "string"
}
},
"xml": {
"name": "ResponseConsultaUnidade"
},
"type": "object"
},
"RequestCadastroUnidade": {
"description": "RequestCadastroUnidade = Objeto para cadastro da Unidade / Estabelecimento.",
"required": [
"cd_grupo",
"cd_empresa",
"nr_cnpj_empresa",
"cd_tipo_inscricao",
"cd_localAmb",
"nm_fantasia",
"cd_interno_unidade",
"ds_cep",
"ds_logradouro",
"ds_numero",
"ds_complemento",
"ds_bairro",
"nr_cnpj",
"cd_cnae",
"nm_razao_social"
],
"properties": {
"cd_grupo": {
"description": "Código do grupo na plataforma Indexmed. Formato: (integer). Obrigatório.",
"type": "integer",
"format": "int32"
},
"cd_empresa": {
"description": "Código da empresa/empregador na plataforma Indexmed. Formato: (integer). Obrigatório.",
"type": "integer",
"format": "int32"
},
"nr_cnpj_empresa": {
"description": "CNPJ da empresa ou CPF do empregador. Formato: (string). Máx. de 20 caracteres. Obrigatório.",
"type": "string"
},
"cd_tipo_inscricao": {
"description": "Tipo de inscrição no eSocial (valores válidos 1, 3, ou 4) 1 = CNPJ, 3 = CAEPF, 4 = CNO . Formato: (integer). Obrigatório.",
"type": "integer",
"format": "int32"
},
"cd_localAmb": {
"description": "Tipo de local de trabalho (valores válidos 1 ou 2) 1 = Estabelecimento próprio, 2 = Estabelecimento de terceiros . Formato: (integer). Obrigatório.",
"type": "integer",
"format": "int32"
},
"nm_fantasia": {
"description": "Nome Fantasia da Unidade/Estabelecimento. Formato: (string). Máx. de 100 caracteres. Obrigatório.",
"type": "string"
},
"cd_interno_unidade": {
"description": "Código da Unidade/Estabelecimento (de outro sistema, para relação bidirecional). Formato: (string). Máx. de 20 caracteres. Obrigatório.",
"type": "string"
},
"ds_cep": {
"description": "CEP da Unidade/Estabelecimento. Formato: (string). Máx. de 9 caracteres. Obrigatório.",
"type": "string"
},
"ds_logradouro": {
"description": "Rua ou avenida do endereço da Unidade/Estabelecimento. Formato: (string). Máx. de 140 caracteres. Obrigatório.",
"type": "string"
},
"ds_numero": {
"description": "Número do endereço da Unidade/Estabelecimento. Formato: (string). Máx. de 6 caracteres. Obrigatório.",
"type": "string"
},
"ds_complemento": {
"description": "Complemento do Unidade/Estabelecimento da empresa. Formato: (string). Máx. de 60 caracteres. Obrigatório.",
"type": "string"
},
"ds_bairro": {
"description": "Bairro do endereço da Unidade/Estabelecimento. Formato: (string). Máx. de 80 caracteres. Obrigatório.",
"type": "string"
},
"nr_cnpj": {
"description": "CNPJ ou CAEPF ou CNO da Unidade. Formato: (string). Máx. de 20 caracteres. Obrigatório.",
"type": "string"
},
"cd_cnae": {
"description": "CNAE da Empresa (5 dígitos) relativo ao anexo da NR-4. Formato: (string). Máx. de 5 caracteres. Obrigatório.",
"type": "string"
},
"nm_razao_social": {
"description": "Razão social Unidade ou nome do empregador. Formato: (string). Máx. de 100 caracteres. Obrigatório.",
"type": "string"
},
"nr_crm": {
"description": "CRM do Médico Responsável pelo PCMSO da Unidade. Formato: (string). Máx. de 15 caracteres. Opcional.",
"type": "string"
},
"uf_crm": {
"description": "Sigla do estado do CRM do Médico Responsável pelo PCMSO da Unidade. Formato: (string). Máx. de 1 caractere. Opcional.",
"type": "string"
},
"nm_medico_coordenador": {
"description": "Nome do Médico Responsável pelo PCMSO da Unidade. Formato: (string). Máx. de 80 caracteres. Opcional.",
"type": "string"
},
"ds_telefone_medico": {
"description": "Telefone do Médico Responsável pelo PCMSO da Unidade. Formato: (string). Máx. de 20 caracteres. Opcional.",
"type": "string"
}
},
"xml": {
"name": "RequestCadastroUnidade"
},
"type": "object"
},
"ResponseCadastroUnidade": {
"properties": {
"cd_unidade": {
"type": "integer",
"format": "int32"
},
"return_msgm": {
"type": "string"
},
"return_code": {
"type": "integer",
"format": "int32"
},
"request_id": {
"example": "00000000-0000-0000-0000-000000000000",
"type": "string",
"format": "uuid"
}
},
"xml": {
"name": "ResponseCadastroUnidade"
},
"type": "object"
},
"RequestAtualizacaoUnidade": {
"description": "RequestAtualizacaoUnidade = Objeto para atualização da Unidade / Estabelecimento.",
"required": [
"cd_unidade",
"cd_grupo",
"cd_empresa",
"nr_cnpj_empresa",
"cd_tipo_inscricao",
"cd_localAmb",
"nm_fantasia",
"cd_interno_unidade",
"ds_cep",
"ds_logradouro",
"ds_numero",
"ds_complemento",
"ds_bairro",
"nr_cnpj",
"cd_cnae",
"nm_razao_social",
"fl_status"
],
"properties": {
"cd_unidade": {
"description": "Código da Unidade/Estabelecimento na plataforma Indexmed. Formato: (integer).Obrigatório.",
"type": "integer",
"format": "int32"
},
"cd_grupo": {
"description": "Código do grupo na plataforma Indexmed. Formato: (integer).Obrigatório.",
"type": "integer",
"format": "int32"
},
"cd_empresa": {
"description": "Código da empresa/empregador na plataforma Indexmed. Formato: (integer).Obrigatório.",
"type": "integer",
"format": "int32"
},
"nr_cnpj_empresa": {
"description": "CNPJ da empresa ou CPF do empregador. Formato: (string). Máx. de 20 caracteres.Obrigatório.",
"type": "string"
},
"cd_tipo_inscricao": {
"description": "Tipo de inscrição no eSocial (valores válidos 1, 3, ou 4) 1 = CNPJ, 3 = CAEPF, 4 = CNO . Formato: (integer). Obrigatório.",
"type": "integer",
"format": "int32"
},
"cd_localAmb": {
"description": "Tipo de local de trabalho (valores válidos 1 ou 2) 1 = Estabelecimento próprio, 2 = Estabelecimento de terceiros . Formato: (integer). Obrigatório.",
"type": "integer",
"format": "int32"
},
"nm_fantasia": {
"description": "Nome Fantasia da Unidade/Estabelecimento. Formato: (string). Máx. de 100 caracteres.Obrigatório.",
"type": "string"
},
"cd_interno_unidade": {
"description": "Código da Unidade/Estabelecimento (de outro sistema, para relação bidirecional). Formato: (string). Máx. de 20 caracteres.Obrigatório.",
"type": "string"
},
"ds_cep": {
"description": "CEP da Unidade/Estabelecimento. Formato: (string). Máx. de 9 caracteres. Obrigatório.",
"type": "string"
},
"ds_logradouro": {
"description": "Rua ou avenida do endereço da Unidade/Estabelecimento. Formato: (string). Máx. de 140 caracteres. Obrigatório.",
"type": "string"
},
"ds_numero": {
"description": "Número do endereço da Unidade/Estabelecimento. Formato: (string). Máx. de 6 caracteres. Obrigatório.",
"type": "string"
},
"ds_complemento": {
"description": "Complemento do Unidade/Estabelecimento da empresa. Formato: (string). Máx. de 60 caracteres. Obrigatório.",
"type": "string"
},
"ds_bairro": {
"description": "Bairro do endereço da Unidade/Estabelecimento. Formato: (string). Máx. de 80 caracteres. Obrigatório.",
"type": "string"
},
"nr_cnpj": {
"description": "CNPJ ou CAEPF ou CNO da Unidade. Formato: (string). Máx. de 20 caracteres.Obrigatório.",
"type": "string"
},
"cd_cnae": {
"description": "CNAE da Empresa (5 dígitos) relativo ao anexo da NR-4. Formato: (string). Máx. de 5 caracteres. Obrigatório.",
"type": "string"
},
"nm_razao_social": {
"description": "Razão social Unidade ou nome do empregador. Formato: (string). Máx. de 100 caracteres. Obrigatório.",
"type": "string"
},
"nr_crm": {
"description": "CRM do Médico Responsável pelo PCMSO da Unidade. Formato: (string). Máx. de 15 caracteres. Opcional.",
"type": "string"
},
"uf_crm": {
"description": "Sigla do estado do CRM do Médico Responsável pelo PCMSO da Unidade. Formato: (string). Máx. de 1 caractere. Opcional.",
"type": "string"
},
"nm_medico_coordenador": {
"description": "Nome do Médico Responsável pelo PCMSO da Unidade. Formato: (string). Máx. de 80 caracteres. Opcional.",
"type": "string"
},
"ds_telefone_medico": {
"description": "Telefone do Médico Responsável pelo PCMSO da Unidade. Formato: (string). Máx. de 20 caracteres. Opcional.",
"type": "string"
},
"fl_status": {
"description": "Status da Unidade (valores válidos \"A\" = Ativo e \"I\" = Inativo). Formato: (string). Máx. de 1 caractere. Obrigatório.",
"type": "string"
}
},
"xml": {
"name": "RequestAtualizacaoUnidade"
},
"type": "object"
}
},
"securityDefinitions": {
"basic": {
"type": "basic",
"description": "Autenticação Basic HTTP"
}
},
"security": [
{
"basic": []
}
],
"tags": [
{
"name": "Arquivo"
},
{
"name": "Colaborador"
},
{
"name": "Empresa"
},
{
"name": "Esocial"
},
{
"name": "Funcao"
},
{
"name": "Grupo"
},
{
"name": "ImportarFichaClinica"
},
{
"name": "ImportarLoteEventos"
},
{
"name": "InformarExames"
},
{
"name": "MedicoCoordenador"
},
{
"name": "Setor"
},
{
"name": "TabelasComplementares"
},
{
"name": "Unidade"
}
]
}
