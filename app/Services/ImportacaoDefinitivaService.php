<?php

namespace App\Services;

use App\Models\Colaborador;
use App\Models\Empresa;
use App\Models\Funcao;
use App\Models\Grupo;
use App\Models\Setor;
use App\Models\Unidade;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ImportacaoDefinitivaService
{
    private array $summary = [];

    public function run(array $estruturaRows, array $colaboradoresRows, bool $confirmar = false): array
    {
        if ($confirmar !== true) {
            throw new \RuntimeException('Execução não confirmada. Use confirmar=true para prosseguir.');
        }

        if ($estruturaRows === [] || $colaboradoresRows === []) {
            throw new \RuntimeException('Arquivos CSV vazios. Estrutura e colaboradores são obrigatórios.');
        }

        $this->summary = $this->initSummary();

        DB::transaction(function () use ($estruturaRows, $colaboradoresRows): void {
            Log::info('Importação definitiva iniciada.', [
                'estrutura_total' => count($estruturaRows),
                'colaboradores_total' => count($colaboradoresRows),
            ]);

            $this->upsertEstrutura($estruturaRows);
            $this->upsertColaboradores($colaboradoresRows);

            Log::info('Importação definitiva finalizada com sucesso.', $this->summary);
        });

        return $this->summary;
    }

    private function upsertEstrutura(array $rows): void
    {
        // TODO: aplicar validação canônica e normalização de dados antes do upsert.
        // TODO: garantir ordem hierárquica (Grupo -> Empresa -> Unidade -> Setor -> Função).

        foreach ($rows as $row) {
            $grupoNome = $this->normalize($row['Grupo_Nome'] ?? '');
            $empresaNome = $this->normalize($row['Empresa_Nome'] ?? '');
            $unidadeNome = $this->normalize($row['Unidade_Nome'] ?? '');
            $setorNome = $this->normalize($row['Setor_Nome'] ?? '');
            $funcaoNome = $this->normalize($row['Funcao_Nome'] ?? '');

            $grupo = Grupo::query()->firstOrCreate([
                'nome' => $grupoNome,
            ], [
                'ativo' => $this->normalizeAtivo($row['Ativo'] ?? null),
            ]);

            if ($grupo->wasRecentlyCreated) {
                $this->summary['total_grupos_criados']++;
                Log::info('Grupo criado na importação definitiva.', [
                    'nome' => $grupo->nome,
                    'id' => $grupo->id,
                ]);
            }

            $empresa = Empresa::query()->firstOrCreate([
                'nome' => $empresaNome,
                'grupo_id' => $grupo->id,
            ], [
                'ativo' => $this->normalizeAtivo($row['Ativo'] ?? null),
            ]);

            if ($empresa->wasRecentlyCreated) {
                $this->summary['total_empresas_criadas']++;
                Log::info('Empresa criada na importação definitiva.', [
                    'nome' => $empresa->nome,
                    'id' => $empresa->id,
                    'grupo_id' => $grupo->id,
                ]);
            }

            $unidade = Unidade::query()->firstOrCreate([
                'nome' => $unidadeNome,
                'empresa_id' => $empresa->id,
            ], [
                'ativo' => $this->normalizeAtivo($row['Ativo'] ?? null),
            ]);

            if ($unidade->wasRecentlyCreated) {
                $this->summary['total_unidades_criadas']++;
                Log::info('Unidade criada na importação definitiva.', [
                    'nome' => $unidade->nome,
                    'id' => $unidade->id,
                    'empresa_id' => $empresa->id,
                ]);
            }

            $setor = Setor::query()->firstOrCreate([
                'nome' => $setorNome,
                'unidade_id' => $unidade->id,
            ], [
                'ativo' => $this->normalizeAtivo($row['Ativo'] ?? null),
            ]);

            if ($setor->wasRecentlyCreated) {
                $this->summary['total_setores_criados']++;
                Log::info('Setor criado na importação definitiva.', [
                    'nome' => $setor->nome,
                    'id' => $setor->id,
                    'unidade_id' => $unidade->id,
                ]);
            }

            $funcao = Funcao::query()->firstOrCreate([
                'nome' => $funcaoNome,
                'setor_id' => $setor->id,
            ], [
                'ativo' => $this->normalizeAtivo($row['Ativo'] ?? null),
            ]);

            if ($funcao->wasRecentlyCreated) {
                $this->summary['total_funcoes_criadas']++;
                Log::info('Função criada na importação definitiva.', [
                    'nome' => $funcao->nome,
                    'id' => $funcao->id,
                    'setor_id' => $setor->id,
                ]);
            }
        }
    }

    private function upsertColaboradores(array $rows): void
    {
        // TODO: aplicar validação canônica e normalização de dados antes do upsert.
        // TODO: identificar duplicados (Nome + Empresa + Unidade) e registrar logs.

        foreach ($rows as $row) {
            $grupoNome = $this->normalize($row['Grupo_Nome'] ?? '');
            $empresaNome = $this->normalize($row['Empresa_Nome'] ?? '');
            $unidadeNome = $this->normalize($row['Unidade_Nome'] ?? '');
            $setorNome = $this->normalize($row['Setor_Nome'] ?? '');
            $funcaoNome = $this->normalize($row['Funcao_Nome'] ?? '');
            $colaboradorNome = $this->normalize($row['Colaborador_Nome'] ?? '');

            $grupo = Grupo::query()->where('nome', $grupoNome)->first();
            $empresa = $grupo
                ? Empresa::query()->where('nome', $empresaNome)->where('grupo_id', $grupo->id)->first()
                : null;
            $unidade = $empresa
                ? Unidade::query()->where('nome', $unidadeNome)->where('empresa_id', $empresa->id)->first()
                : null;
            $setor = $unidade
                ? Setor::query()->where('nome', $setorNome)->where('unidade_id', $unidade->id)->first()
                : null;
            $funcao = $setor
                ? Funcao::query()->where('nome', $funcaoNome)->where('setor_id', $setor->id)->first()
                : null;

            if (! $grupo || ! $empresa || ! $unidade || ! $setor || ! $funcao) {
                throw new \RuntimeException('Estrutura não resolvida para colaborador: ' . ($row['Colaborador_Nome'] ?? ''));
            }

            $colaborador = Colaborador::query()->updateOrCreate([
                'nome' => $colaboradorNome,
                'empresa_id' => $empresa->id,
                'unidade_id' => $unidade->id,
            ], [
                'funcao_id' => $funcao->id,
                'ativo' => $this->normalizeAtivo($row['Colaborador_Ativo'] ?? null),
            ]);

            if ($colaborador->wasRecentlyCreated) {
                $this->summary['colaboradores_criados']++;
                Log::info('Colaborador criado na importação definitiva.', [
                    'nome' => $colaborador->nome,
                    'id' => $colaborador->id,
                ]);
            } else {
                $this->summary['colaboradores_atualizados']++;
                Log::info('Colaborador atualizado na importação definitiva.', [
                    'nome' => $colaborador->nome,
                    'id' => $colaborador->id,
                ]);
            }

            $email = $this->normalizeEmail($row['Usuario_Email'] ?? '');
            if ($email === '' || ! filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $this->summary['usuarios_pendentes']++;
                Log::info('Usuário ignorado na importação definitiva (sem email válido).', [
                    'colaborador' => $colaborador->nome,
                ]);
                continue;
            }

            $user = User::query()->where('email', $email)->first();

            if (! $user) {
                $user = new User();
                $user->fill([
                    'colaborador_id' => $colaborador->id,
                    'name' => $colaboradorNome,
                    'email' => $email,
                    'ativo' => $this->normalizeAtivo($row['Usuario_Ativo'] ?? null),
                ]);
                $user->password = Hash::make(Str::random(32));
                $user->save();

                $this->attachUserToColaborador($user, $colaborador);

                $this->summary['usuarios_criados']++;
                Log::info('Usuário criado na importação definitiva.', [
                    'colaborador' => $colaborador->nome,
                    'user_id' => $user->id,
                    'email' => $user->email,
                ]);
                continue;
            }

            $associacoes = $this->resolveUserAssociations($user);
            $mesmaEmpresa = in_array($empresa->id, $associacoes['empresa_ids'], true);
            $mesmoGrupo = in_array($empresa->grupo_id, $associacoes['grupo_ids'], true);

            $this->attachUserToColaborador($user, $colaborador);

            if ($mesmaEmpresa) {
                $this->summary['usuarios_reaproveitados_mesma_empresa']++;
                Log::info('Usuário reaproveitado (mesma empresa).', [
                    'colaborador' => $colaborador->nome,
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'empresa_id' => $empresa->id,
                    'grupo_id' => $empresa->grupo_id,
                ]);
                continue;
            }

            if ($mesmoGrupo) {
                $this->summary['usuarios_reaproveitados_multi_empresa']++;
                Log::warning('Usuário reaproveitado em múltiplas empresas (mesmo grupo).', [
                    'colaborador' => $colaborador->nome,
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'empresa_id' => $empresa->id,
                    'grupo_id' => $empresa->grupo_id,
                ]);
                continue;
            }

            $this->summary['usuarios_reaproveitados_multi_grupo']++;
            Log::critical('Usuário reaproveitado em múltiplos grupos.', [
                'colaborador' => $colaborador->nome,
                'user_id' => $user->id,
                'email' => $user->email,
                'empresa_id' => $empresa->id,
                'grupo_id' => $empresa->grupo_id,
            ]);
        }
    }

    private function attachUserToColaborador(User $user, Colaborador $colaborador): void
    {
        if ($user->colaborador_id === null) {
            $user->colaborador_id = $colaborador->id;
            $user->save();
        }

        $user->colaboradores()->syncWithoutDetaching([$colaborador->id]);
    }

    private function resolveUserAssociations(User $user): array
    {
        $colaboradores = $user->colaboradores()->with(['empresa.grupo'])->get();
        $primario = $user->colaborador()->with(['empresa.grupo'])->first();

        if ($primario) {
            $colaboradores = $colaboradores->push($primario)->unique('id');
        }

        $empresaIds = [];
        $grupoIds = [];

        foreach ($colaboradores as $colaborador) {
            if ($colaborador->empresa_id) {
                $empresaIds[] = $colaborador->empresa_id;
            }

            $grupoId = $colaborador->empresa?->grupo_id;
            if ($grupoId) {
                $grupoIds[] = $grupoId;
            }
        }

        return [
            'empresa_ids' => array_values(array_unique($empresaIds)),
            'grupo_ids' => array_values(array_unique($grupoIds)),
        ];
    }

    private function normalize(string $value): string
    {
        $value = trim($value);
        $value = preg_replace('/\s+/', ' ', $value) ?? $value;

        return mb_strtoupper($value);
    }

    private function normalizeEmail(string $value): string
    {
        $value = trim($value);

        return mb_strtolower($value);
    }

    /**
     * Normaliza o campo Ativo para TINYINT(1).
     */
    private function normalizeAtivo(mixed $value): int
    {
        if (is_bool($value)) {
            return $value ? 1 : 0;
        }

        $normalized = mb_strtolower(trim((string) $value));

        return in_array($normalized, ['1', 'true', 'sim', 's'], true) ? 1 : 0;
    }

    private function initSummary(): array
    {
        return [
            'total_grupos_criados' => 0,
            'total_empresas_criadas' => 0,
            'total_unidades_criadas' => 0,
            'total_setores_criados' => 0,
            'total_funcoes_criadas' => 0,
            'colaboradores_criados' => 0,
            'colaboradores_atualizados' => 0,
            'usuarios_criados' => 0,
            'usuarios_reaproveitados_mesma_empresa' => 0,
            'usuarios_reaproveitados_multi_empresa' => 0,
            'usuarios_reaproveitados_multi_grupo' => 0,
            'usuarios_pendentes' => 0,
        ];
    }
}
