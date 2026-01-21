## [STATUS DE FINALIZAÇÃO - 21/01/2026]

Resumo do dia:
- CSV de grau de risco (CNAE) salvo em database/cnae_risk_final.csv.
- Formulários de Empresa e Unidade em processo de limpeza final (remoção de código antigo, manter só método configure).

Próximos passos para amanhã:
- Finalizar limpeza dos formulários Empresa/Unidade (remover blocos antigos, garantir só método configure).
- Implementar leitura automática do CSV de grau de risco para preencher o campo grau_risco conforme o CNAE.
- Garantir que todos os campos estejam editáveis e funcionais.
- Validar visualmente e testar importação automática do grau de risco.

Pendências:
- Revisar se todos os campos obrigatórios estão presentes e funcionais.
- Testar cadastro completo com diferentes CNAEs.

Observação: continuar a partir deste status amanhã para garantir zero retrabalho.
## [MARCAÇÃO DE INTERAÇÃO - 21/01/2026]

Observações do usuário:
- No core de cadastro de empresas, não foi possível digitar o CNPJ ou o CPF, apenas aparece o placeholder, assim como o campo CEP.
- Estamos elaborando o documento para validação do CNAR NR-4, que será entregue ao sistema para completar o cadastro.
- Finalizando os trabalhos por hoje, continuidade será feita de outro computador.

Documento de Governança e Segurança – CRM Lume v3 (v1.1)
1. Objetivo do Documento
Este documento define o modelo oficial de governança, segurança e arquitetura técnica do CRM Lume v3. Ele serve como fonte única de verdade para decisões técnicas, garantindo que o sistema seja seguro, auditável e performático, rodando em ambiente local ou nuvem.
Stack Tecnológica Obrigatória: PHP 8.3+ (Laravel 12), Filament 4.5.
2. Modelo de Segurança Adotado
O CRM Lume v3 adota uma arquitetura de defesa em profundidade:
1.	RBAC (Role-Based Access Control): Define a capacidade (O quê).
2.	ABAC (Attribute-Based Access Control): Define o contexto e escopo (Onde/Quem).
3.	Zero Trust (Backend-First): A interface não é confiável; toda requisição é validada no backend (Policies).
4.	Auditoria Contínua: Rastreabilidade total de ações sensíveis.
3. Conceitos Fundamentais
•	3.1 Usuário: Identidade de acesso. Não define privilégios por si só.
•	3.2 Papel (Role): Agrupador de permissões (ex: "Gestor"). Gerenciado via spatie/laravel-permission.
•	3.3 Permissão: Ação atômica nomeada (ex: ponto.view).
•	3.4 Escopo (ABAC): Restrição de dados baseada em atributos (user_id, grupo_id). Implementado via Global Scopes do Laravel.
•	3.5 Interface (Filament): Apenas reflete as regras de negócio. Esconder um botão no Filament (visible()) não é segurança; a segurança está na Policy.
4. Papéis Oficiais (Business Logic)
Mantidos conforme v1.0 para integridade do negócio:
•	Super Admin: Governança técnica e gestão de acessos.
•	CEO Lume: Visão total, foco estratégico/operacional.
•	Admin Lume: Operacional administrativo (contratos, propostas).
•	Gestor de Grupo: Visão restrita ao grupo_id.
•	RH: Visão de ponto e banco de horas (sem permissão de marcação por terceiros).
•	Colaborador: Visão estrita ao user_id (Self).
5. Matriz de Permissões e Escopos
A aplicação deve respeitar a intersecção entre Permissão e Escopo.
Contexto	Permissões Chave (Exemplos)	Escopo Típico (ABAC)
Ponto	ponto.view.self, ponto.mark.self	Self (where user_id = ?)
Gestão	ponto.adjust.approve, colaborador.view.group	Grupo (where grupo_id = ?)
Global	contrato.view, relatorio.geral	Global (Sem filtros de escopo)
Exportar para as Planilhas
Regra de Ouro: O escopo sempre restringe a permissão. Ter ponto.view.global não permite ver dados se o atributo de escopo do usuário for restrito (embora, por definição, um papel global deva ter escopo global).
6. Diretrizes de Implementação Técnica (Laravel 12 & Filament 4.5)
6.1 Segurança no Backend (Policies)
•	Obrigatório: Todas os Models e Recursos do Filament devem ter uma Policy correspondente.
•	Lógica: A Policy deve verificar a permissão (RBAC) E o pertencimento do dado (ABAC).
PHP
// Exemplo de conformidade
public function update(User $user, Ponto $ponto) {
    return $user->can('ponto.edit') && $user->grupo_id === $ponto->grupo_id;
}
6.2 Performance (Ambiente Local)
Para garantir velocidade instantânea mesmo com segurança pesada:
•	Cache de Permissões: O spatie/permission deve ser configurado para usar cache (Redis ou Array em memória).
•	Eager Loading: Ao carregar usuários, carregar sempre os relacionamentos de escopo (grupo, empresa) para evitar N+1 queries nas validações de Policy.
•	Database Indexing: As colunas de escopo (grupo_id, user_id, created_at) devem ser indexadas no banco de dados.
6.3 Auditoria (Rastreabilidade)
•	Utilizar spatie/laravel-activitylog ou observadores nativos.
•	O que logar: Criação, Edição e Exclusão de registros sensíveis (Ponto, Contratos, Permissões).
•	Contexto: O log deve gravar user_id, ip_address, role_at_time e os dados old vs new.
7. Instruções Obrigatórias para IAs e Desenvolvedores
Ao solicitar código a uma IA (ChatGPT, Claude, Copilot) ou delegar a um dev, as seguintes regras são inegociáveis:
1.	Não Hardcode: Nunca usar IDs fixos (ex: if ($id == 1)). Usar Roles ou Permissions.
2.	Filament = Segurança: Nunca confiar apenas no método ->visible() do Filament. A proteção deve estar na Model Policy.
3.	Scopes Globais: Para implementar a visão de "Gestor" ou "Colaborador", criar GlobalScopes no Laravel que injetam automaticamente o where user_id ou where grupo_id baseados no usuário logado.
4.	Testes: Toda nova funcionalidade exige um teste (PestPHP/PHPUnit) que tente acessar o recurso com um usuário sem permissão para garantir que a Policy bloqueia (Teste de Falha).
8. Status do Documento
•	Versão: 1.1 (Revisão Técnica)
•	Data: Janeiro/2026
•	Stack: Laravel 12 / Filament 4.5
•	Situação: Vigente – Contrato Arquitetural Obrigatório.