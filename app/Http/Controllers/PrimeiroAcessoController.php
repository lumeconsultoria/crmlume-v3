<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\PrimeiroAcessoService;
use Illuminate\Http\Request;

class PrimeiroAcessoController extends Controller
{
    private const MENSAGEM_NEUTRA = 'Não foi possível validar seus dados. Verifique e tente novamente ou contate o RH.';

    public function show()
    {
        return view('primeiro-acesso');
    }

    public function store(Request $request, PrimeiroAcessoService $service)
    {
        $data = $request->validate([
            'cpf' => ['required', 'string'],
            'data_nascimento' => ['required', 'date'],
        ]);

        $resultado = $service->iniciar(
            $data['cpf'],
            $data['data_nascimento'],
            $request->ip(),
            $request->userAgent()
        );

        if ($resultado['status'] === 'colaborador_nao_encontrado') {
            return back()->withErrors(['cpf' => self::MENSAGEM_NEUTRA]);
        }

        if ($resultado['status'] === 'email_existente') {
            return back()->withErrors(['cpf' => 'Colaborador já possui acesso. Use a opção de recuperação de senha.']);
        }

        // email_inexistente - mostrar tela de solicitação de email
        session([
            'primeiro_acesso_colaborador' => $resultado['colaborador'],
            'primeiro_acesso_cpf' => $data['cpf'],
            'primeiro_acesso_data_nascimento' => $data['data_nascimento'],
        ]);

        return view('primeiro-acesso-email', [
            'colaborador' => $resultado['colaborador'],
        ]);
    }

    public function email(Request $request, PrimeiroAcessoService $service)
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
            'email_confirmacao' => ['required', 'same:email'],
        ]);

        $colaboradorData = session('primeiro_acesso_colaborador');
        $cpf = session('primeiro_acesso_cpf');
        $dataNascimento = session('primeiro_acesso_data_nascimento');

        if (!$colaboradorData || !$cpf || !$dataNascimento) {
            return redirect()->route('primeiro-acesso.show')
                ->withErrors(['error' => self::MENSAGEM_NEUTRA]);
        }

        $colaborador = $service->resolverColaborador($cpf, $dataNascimento);

        if (!$colaborador) {
            return redirect()->route('primeiro-acesso.show')
                ->withErrors(['error' => self::MENSAGEM_NEUTRA]);
        }

        // Gerar token
        $token = $service->gerarTokenPrimeiroAcesso(
            $colaborador,
            $data['email'],
            $request->ip(),
            $request->userAgent()
        );

        // Enviar email com link
        $link = route('primeiro-acesso.token', ['token' => $token]);

        // TODO: Implementar envio de email real
        // Mail::to($data['email'])->send(new PrimeiroAcessoMail($link));

        // Limpar sessão
        session()->forget(['primeiro_acesso_colaborador', 'primeiro_acesso_cpf', 'primeiro_acesso_data_nascimento']);

        // Por enquanto, mostrar o link na tela (apenas para desenvolvimento)
        return view('primeiro-acesso-link-enviado', [
            'email' => $data['email'],
            'link' => $link, // Remover em produção
        ]);
    }

    public function showToken(string $token, PrimeiroAcessoService $service)
    {
        $tokenModel = $service->validarToken($token);

        if (!$tokenModel) {
            return view('primeiro-acesso-token-invalido');
        }

        return view('primeiro-acesso-senha', [
            'token' => $token,
        ]);
    }

    public function criarSenha(Request $request, string $token, PrimeiroAcessoService $service)
    {
        $data = $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $tokenModel = $service->validarToken($token);

        if (!$tokenModel) {
            return back()->withErrors(['error' => 'Link inválido ou expirado.']);
        }

        // Criar usuário com senha
        $user = $service->criarUsuarioComSenha(
            $tokenModel,
            $data['password'],
            $request->ip(),
            $request->userAgent()
        );

        // Fazer login automático
        auth()->login($user);

        // Redirecionar para o painel apropriado
        // Por padrão, novos usuários vão para /ops (colaboradores)
        return redirect('/ops')->with('success', 'Conta criada com sucesso!');
    }
}
