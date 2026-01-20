# Validação funcional e jurídica — Cartão de Ponto v1

## Objetivo

Registrar a validação do fluxo mínimo do Cartão de Ponto v1, assegurando integridade do relatório e conformidade básica com CLT/Portaria 671.

## Fluxo validado

- Entrada
- Saída
- Ajuste com motivo
- Exportação
- Validação de hash (assinatura interna)

## Evidência de integridade

- `assinatura_hash` **igual** a `file_hash`
- Resultado: **OK**

## Registro da validação

- **Data:** 2026-01-20
- **Ambiente:** homologação
- **Responsável:** equipe técnica (operador do sistema)

## Observações

- Assinatura eletrônica interna: hash do documento + usuário emissor + timestamp.
- Sem ICP-Brasil no v1.

## Armazenamento e retenção (v1)

- Arquivos de exportação armazenados em `storage/app/ponto/AAAA/MM/empresa_id` (fora do público).
- Um arquivo por exportação (append-only, sem sobrescrita).
- Hash SHA-256 registrado no banco.
- Retenção mínima de 5 anos (sem purge automático no v1).
- Acesso restrito a RH/Admin autenticado.

---

**Marco:** v1 funcional validado.
