<!DOCTYPE html>
<html>
<head>
    <title>{{ $subject }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f9f9f9;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header img {
            max-width: 150px;
        }
        .content {
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 12px;
            color: #777;
        }
    </style>
</head>
<body>
    <!-- Logo da Empresa -->
    <div class="header">

    </div>

    <!-- Conteúdo do E-mail -->
    <div class="content">
        <h4>{{ $subject }}</h4>
        <p>{{ $mailMensagem }}</p>
        <p>Se precisar de mais assistência, não hesite em entrar em contato conosco.</p>
        <p>
            Atenciosamente,<br>
            <strong>Equipe de Suporte</strong><br>
            <a href="mailto:support@empresa.com">support@empresa.com</a><br>
            <a href="https://www.empresa.com" target="_blank">www.empresa.com</a>
        </p>
    </div>

    <!-- Rodapé -->
    <div class="footer">
        <p>Este e-mail foi enviado automaticamente. Por favor, não responda diretamente a esta mensagem.</p>
        <p>&copy; {{ date('Y') }} Sua Empresa. Todos os direitos reservados.</p>
    </div>
</body>
</html>
