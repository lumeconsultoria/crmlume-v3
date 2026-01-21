<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Confirmar Email - CRM Lume v3</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            font-family: system-ui, -apple-system, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }

        .container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            padding: 2.5rem;
            max-width: 500px;
            width: 100%;
        }

        .logo {
            text-align: center;
            margin-bottom: 2rem;
        }

        .logo h1 {
            font-size: 1.875rem;
            font-weight: 700;
            color: #1f2937;
            margin: 0;
        }

        .logo p {
            color: #6b7280;
            margin-top: 0.5rem;
            font-size: 0.875rem;
        }

        .info-box {
            background-color: #f3f4f6;
            border-left: 4px solid #667eea;
            padding: 1rem;
            border-radius: 4px;
            margin-bottom: 1.5rem;
        }

        .info-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.5rem;
        }

        .info-item:last-child {
            margin-bottom: 0;
        }

        .info-label {
            font-weight: 600;
            color: #4b5563;
            font-size: 0.875rem;
        }

        .info-value {
            color: #1f2937;
            font-size: 0.875rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        label {
            display: block;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: #374151;
            font-size: 0.875rem;
        }

        input {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s;
            box-sizing: border-box;
        }

        input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .btn-submit {
            width: 100%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 0.875rem;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }

        .btn-submit:active {
            transform: translateY(0);
        }

        .alert {
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            font-size: 0.875rem;
        }

        .alert-info {
            background-color: #dbeafe;
            color: #1e40af;
            border-left: 4px solid #3b82f6;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="logo">
            <h1>CRM Lume v3</h1>
            <p>Confirmar Email de Acesso</p>
        </div>

        <div class="info-box">
            <div class="info-item">
                <span class="info-label">Nome:</span>
                <span class="info-value">{{ $colaborador['nome'] }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Empresa:</span>
                <span class="info-value">{{ $colaborador['empresa'] ?? 'Não informado' }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Unidade:</span>
                <span class="info-value">{{ $colaborador['unidade'] ?? 'Não informado' }}</span>
            </div>
        </div>

        <div class="alert alert-info">
            <strong>Importante:</strong> Informe seu email para receber o link de criação de senha.
        </div>

        <form method="POST" action="{{ route('primeiro-acesso.email') }}">
            @csrf

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="seu.email@exemplo.com" required
                    autocomplete="off" value="{{ old('email') }}">
                @error('email')
                <p style="color: #dc2626; font-size: 0.875rem; margin-top: 0.5rem;">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="email_confirmacao">Confirmar Email</label>
                <input type="email" id="email_confirmacao" name="email_confirmacao" placeholder="seu.email@exemplo.com"
                    required autocomplete="off">
                @error('email_confirmacao')
                <p style="color: #dc2626; font-size: 0.875rem; margin-top: 0.5rem;">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="btn-submit">
                Enviar Link de Acesso
            </button>
        </form>
    </div>
</body>

</html>