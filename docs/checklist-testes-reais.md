# Checklist antes de testes reais

## Status atual

- TESTE REAL CONTROLADO ativo.
- Sem novas features.
- Apenas correções de bugs críticos e ajustes pontuais de texto/UX.
- Logs e auditoria como prioridade.

## Preparação técnica

- Backup validado e plano de rollback definido.
- Migrações executadas em homologação.
- Seeds mínimos executados (roles e usuários de teste por perfil).
- Logs e auditoria ativos e acessíveis.

## Segurança e acesso

- Verificar matriz de acesso (roles + scopes + flags) por perfil:
    - Colaborador: apenas próprio ponto e espelho.
    - RH: empresa inteira, ajustes e exportações.
    - Gestor: equipe/escopo configurável, sem ajustes/exportações.
    - Lume (vendedor/administrativo/admin/super): somente escopos permitidos.
- Rate limit do Primeiro Acesso validado.
- Mensagens neutras em fluxos sensíveis.

## Encerramento Cartão de Ponto v1 (usuário)

- Telas mínimas confirmadas: login, primeiro acesso, liberação de usuários.
- Fluxos reais validados por perfil (Colaborador, RH, Gestor, Lume).
- UX básica ajustada para reduzir erro humano.

## Cartão de Ponto v1

- Registro de entrada/saída funcionando.
- Ajustes com motivo registrados.
- Exportação gera arquivo em storage/app/ponto/AAAA/MM/empresa_id.
- Hash de integridade validado.

## Operação

- Contas de teste criadas por perfil.
- Canal de feedback e rotina de suporte definidos.
- Congelamento de features (modo estabilização) comunicado.
- Monitoramento de erros ativo.
