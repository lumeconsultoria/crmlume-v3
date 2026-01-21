<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Link Inválido - CRM Lume v3</title>
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

        .icon-error {
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

        .btn-primary {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 0.875rem 2rem;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            transition: transform 0.2s;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="icon-error">❌</div>

        <h1 class="title">Link Inválido ou Expirado</h1>

        <p class="message">
            O link de primeiro acesso não é mais válido. Isso pode ter ocorrido porque:
        </p>

        <ul style="text-align: left; color: #6b7280; margin-bottom: 2rem;">
            <li>O link já foi utilizado</li>
            <li>O link expirou (válido por 24 horas)</li>
            <li>O link está incorreto</li>
        </ul>

        <a href="{{ route('primeiro-acesso.show') }}" class="btn-primary">
            Solicitar Novo Acesso
        </a>
    </div>
</body>

</html>