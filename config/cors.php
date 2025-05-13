<?php

return [

    'paths' => ['api/*', 'loginChat', 'broadcasting/auth'], // Ajuste para incluir suas rotas de API
    'allowed_methods' => ['*'], // Permitir todos os métodos
    'allowed_origins' => ['http://localhost:8081', 'http://localhost:8082', 'https://viacep.com.br/ws/'], // Permitir apenas a origem específica
    'allowed_headers' => ['*'], // Permitir todos os cabeçalhos
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => true, // Se você estiver usando cookies ou autenticação

];
