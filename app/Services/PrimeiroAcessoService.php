<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Colaborador;
use App\Models\EmailPendencia;
use App\Models\PrimeiroAcesso;
use App\Models\PrimeiroAcessoToken;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PrimeiroAcessoService
{
    private const TOKEN_EXPIRATION_HOURS = 24;

    public function iniciar(string $cpf, string $dataNascimento, ?string $ip = null, ?string $userAgent = null): array
    {
        $cpfNormalizado = $this->normalizarCpf($cpf);
        $cpfHash = hash('sha256', $cpfNormalizado);
        $data = Carbon::parse($dataNascimento)->toDateString();

        $colaborador = $this->resolverColaborador($cpf, $dataNascimento);

        if (! $colaborador) {
            $this->registrarEvento(null, $cpfHash, $data, null, null, 'colaborador_nao_encontrado', $ip, $userAgent);

            return [
                'status' => 'colaborador_nao_encontrado',
            ];
        }

        $this->registrarEvento($colaborador->id, $cpfHash, $data, null, null, 'iniciado', $ip, $userAgent);

        $email = $colaborador->user?->email;

        return [
            'status' => $email ? 'email_existente' : 'email_inexistente',
            'colaborador' => [
                'nome' => $colaborador->nome,
                'empresa' => $colaborador->empresa?->nome,
                'unidade' => $colaborador->unidade?->nome,
                'email_mascarado' => $email ? $this->mascararEmail($email) : null,
            ],
        ];
    }

    public function enviarAcessoParaEmailExistente(Colaborador $colaborador, ?string $ip = null, ?string $userAgent = null): void
    {
        $email = $colaborador->user?->email;

        if (! $email) {
            $this->registrarEvento($colaborador->id, $colaborador->cpf_hash ?? '', $colaborador->data_nascimento?->toDateString(), null, null, 'bloqueado', $ip, $userAgent);
            return;
        }

        Password::sendResetLink(['email' => $email]);

        $this->registrarEvento($colaborador->id, $colaborador->cpf_hash ?? '', $colaborador->data_nascimento?->toDateString(), null, $email, 'email_existente_enviado', $ip, $userAgent);
        $this->registrarEvento($colaborador->id, $colaborador->cpf_hash ?? '', $colaborador->data_nascimento?->toDateString(), null, $email, 'token_emitido', $ip, $userAgent);
    }

    public function coletarEmailNovo(Colaborador $colaborador, string $email, ?string $ip = null, ?string $userAgent = null): string
    {
        $email = mb_strtolower(trim($email));

        $userExistente = User::query()->where('email', $email)->first();
        if ($userExistente && $userExistente->colaborador_id !== $colaborador->id) {
            $vinculo = $userExistente->colaboradores()
                ->whereKey($colaborador->id)
                ->exists();

            if ($vinculo) {
                Password::sendResetLink(['email' => $email]);

                $this->registrarEvento($colaborador->id, $colaborador->cpf_hash ?? '', $colaborador->data_nascimento?->toDateString(), $email, null, 'email_existente_enviado', $ip, $userAgent);
                $this->registrarEvento($colaborador->id, $colaborador->cpf_hash ?? '', $colaborador->data_nascimento?->toDateString(), $email, null, 'token_emitido', $ip, $userAgent);

                return 'email_existente_enviado';
            }

            $this->registrarEvento($colaborador->id, $colaborador->cpf_hash ?? '', $colaborador->data_nascimento?->toDateString(), $email, null, 'bloqueado', $ip, $userAgent);
            $this->registrarEmailCorrigido($colaborador, $email, 'Colisão de email com outro colaborador', null, $ip, $userAgent);
            return 'bloqueado';
        }

        $user = User::query()->firstOrNew(['colaborador_id' => $colaborador->id]);
        if (! $user->exists) {
            $user->name = $colaborador->nome;
            $user->password = Hash::make(Str::random(32));
        }
        $user->email = $email;
        $user->ativo = true;
        $user->save();

        $this->registrarEvento($colaborador->id, $colaborador->cpf_hash ?? '', $colaborador->data_nascimento?->toDateString(), $email, null, 'email_novo_coletado', $ip, $userAgent);

        Password::sendResetLink(['email' => $email]);

        $this->registrarEvento($colaborador->id, $colaborador->cpf_hash ?? '', $colaborador->data_nascimento?->toDateString(), $email, null, 'token_emitido', $ip, $userAgent);

        return 'token_emitido';
    }

    public function resolverColaborador(string $cpf, string $dataNascimento): ?Colaborador
    {
        $cpfNormalizado = $this->normalizarCpf($cpf);
        $cpfHash = hash('sha256', $cpfNormalizado);
        $data = Carbon::parse($dataNascimento)->toDateString();

        return Colaborador::query()
            ->where('cpf_hash', $cpfHash)
            ->whereDate('data_nascimento', $data)
            ->first();
    }

    public function registrarEmailCorrigido(Colaborador $colaborador, string $email, ?string $motivo = null, ?int $userId = null, ?string $ip = null, ?string $userAgent = null): void
    {
        $email = mb_strtolower(trim($email));

        EmailPendencia::query()->create([
            'colaborador_id' => $colaborador->id,
            'email_sugerido' => $email,
            'motivo' => $motivo,
            'criado_por_user_id' => $userId,
            'created_at' => now(),
        ]);

        $this->registrarEvento($colaborador->id, $colaborador->cpf_hash ?? '', $colaborador->data_nascimento?->toDateString(), $email, $colaborador->user?->email, 'email_corrigido', $ip, $userAgent);
    }

    public function aprovarEmailPendencia(EmailPendencia $pendencia, int $userId): void
    {
        $colaborador = $pendencia->colaborador;

        if (! $colaborador) {
            return;
        }

        $email = mb_strtolower(trim($pendencia->email_sugerido));

        $userExistente = User::query()->where('email', $email)->first();
        if ($userExistente && $userExistente->colaborador_id !== $colaborador->id) {
            $vinculo = $userExistente->colaboradores()
                ->whereKey($colaborador->id)
                ->exists();

            if (! $vinculo) {
                $this->registrarEvento($colaborador->id, $colaborador->cpf_hash ?? '', $colaborador->data_nascimento?->toDateString(), $email, $colaborador->user?->email, 'bloqueado', null, null);
                return;
            }
        }

        $user = User::query()->firstOrNew(['colaborador_id' => $colaborador->id]);
        if (! $user->exists) {
            $user->name = $colaborador->nome;
            $user->password = Hash::make(Str::random(32));
        }
        $user->email = $email;
        $user->ativo = true;
        $user->save();

        Password::sendResetLink(['email' => $email]);

        $this->registrarEvento($colaborador->id, $colaborador->cpf_hash ?? '', $colaborador->data_nascimento?->toDateString(), $email, $colaborador->user?->email, 'email_corrigido', null, null);
        $this->registrarEvento($colaborador->id, $colaborador->cpf_hash ?? '', $colaborador->data_nascimento?->toDateString(), $email, $colaborador->user?->email, 'token_emitido', null, null);
    }

    private function registrarEvento(?int $colaboradorId, string $cpfHash, ?string $dataNascimento, ?string $emailInformado, ?string $emailAnterior, string $status, ?string $ip, ?string $userAgent): void
    {
        PrimeiroAcesso::query()->create([
            'colaborador_id' => $colaboradorId,
            'cpf_hash' => $cpfHash,
            'data_nascimento' => $dataNascimento,
            'email_informado' => $emailInformado,
            'email_anterior' => $emailAnterior,
            'status' => $status,
            'ip' => $ip,
            'user_agent' => $userAgent,
            'created_at' => now(),
        ]);

        Log::info('primeiro_acesso', [
            'cpf_hash' => $cpfHash,
            'colaborador_id' => $colaboradorId,
            'status' => $status,
            'ip' => $ip,
            'user_agent' => $userAgent,
        ]);
    }

    private function normalizarCpf(string $cpf): string
    {
        return preg_replace('/\D+/', '', $cpf) ?? '';
    }

    private function mascararEmail(string $email): string
    {
        [$usuario, $dominio] = array_pad(explode('@', $email, 2), 2, '');
        $usuarioMascarado = $usuario !== '' ? mb_substr($usuario, 0, 2) . str_repeat('*', max(mb_strlen($usuario) - 2, 0)) : '';

        return $dominio ? $usuarioMascarado . '@' . $dominio : $usuarioMascarado;
    }

    public function gerarTokenPrimeiroAcesso(Colaborador $colaborador, string $email, ?string $ip = null, ?string $userAgent = null): string
    {
        $email = mb_strtolower(trim($email));

        // Invalidar tokens anteriores não usados
        PrimeiroAcessoToken::where('colaborador_id', $colaborador->id)
            ->where('email', $email)
            ->whereNull('used_at')
            ->update(['used_at' => now()]);

        // Gerar novo token
        $token = Str::random(64);
        $expiresAt = now()->addHours(self::TOKEN_EXPIRATION_HOURS);

        PrimeiroAcessoToken::create([
            'colaborador_id' => $colaborador->id,
            'token' => $token,
            'email' => $email,
            'expires_at' => $expiresAt,
            'ip' => $ip,
            'user_agent' => $userAgent,
        ]);

        $this->registrarEvento($colaborador->id, $colaborador->cpf_hash ?? '', $colaborador->data_nascimento?->toDateString(), $email, null, 'token_gerado', $ip, $userAgent);

        return $token;
    }

    public function validarToken(string $token): ?PrimeiroAcessoToken
    {
        $tokenModel = PrimeiroAcessoToken::where('token', $token)->first();

        if (!$tokenModel) {
            return null;
        }

        if (!$tokenModel->isValid()) {
            return null;
        }

        return $tokenModel;
    }

    public function criarUsuarioComSenha(PrimeiroAcessoToken $token, string $senha, ?string $ip = null, ?string $userAgent = null): User
    {
        $colaborador = $token->colaborador;

        // Criar usuário
        $user = User::firstOrNew(['colaborador_id' => $colaborador->id]);
        $user->name = $colaborador->nome;
        $user->email = $token->email;
        $user->password = Hash::make($senha);
        $user->email_verified_at = $user->email_verified_at ?? now();
        $user->ativo = true;
        $user->save();

        // Atribuir role de colaborador (acesso básico)
        if (!$user->hasAnyRole(['colaborador', 'admin', 'rh', 'gestor', 'ceo', 'super_admin'])) {
            $user->assignRole('colaborador');
        }

        // Marcar token como usado
        $token->used_at = now();
        $token->save();

        $this->registrarEvento($colaborador->id, $colaborador->cpf_hash ?? '', $colaborador->data_nascimento?->toDateString(), $token->email, null, 'usuario_criado', $ip, $userAgent);

        return $user;
    }
}
