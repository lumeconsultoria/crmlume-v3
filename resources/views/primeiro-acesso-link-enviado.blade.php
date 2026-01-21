<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Link Enviado - CRM Lume v3</title>
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
            text-align: center;
        }

        .icon-success {
            font-size: 4rem;
            margin-bottom: 1rem;
        }

        .title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 1rem;
        }

        .message {
            color: #6b7280;
            margin-bottom: 2rem;
            line-height: 1.6;
        }

        .email-display {
            background-color: #f3f4f6;
            padding: 0.75rem;
            border-radius: 8px;
            font-weight: 600;
            color: #667eea;
            margin-bottom: 1.5rem;
        }

        .dev-link {
            background-color: #fff3cd;
            border: 2px dashed #ffc107;
            padding: 1rem;
            border-radius: 8px;
            margin-top: 1.5rem;
        }

        .dev-link p {
            font-size: 0.875rem;
            color: #856404;
            margin-bottom: 0.5rem;
        }

        .dev-link a {
            color: #0066cc;
            word-break: break-all;
            font-size: 0.875rem;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="icon-success">✅</div>

        <h1 class="title">Link Enviado!</h1>

        <p class="message">
            Se os dados estiverem corretos, você receberá um email em:
        </p>

        <div class="email-display">
            {{ $email }}
        </div>

        <p class="message">
            Acesse sua caixa de entrada e clique no link para criar sua senha.
            <br>
            <small style="color: #9ca3af;">O link é válido por 24 horas.</small>
        </p>

        @if(isset($link))
        <div class="dev-link">
            <p><strong>⚠️ APENAS DESENVOLVIMENTO ⚠️</strong></p>
            <p>Link direto (remover em produção):</p>
            <a href="{{ $link }}" target="_blank">{{ $link }}</a>
        </div>
        @endif
    </div>
</body>

</html>