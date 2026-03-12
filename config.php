<?php
// ajustes de conexão e configurações gerais
return [
'db' => [
'dsn' => 'pgsql:host=localhost;port=5432;dbname=escola',
'user' => 'seu_usuario',
'pass' => 'sua_senha'
],
'base_url' => '/projeto_escola',
'upload_dir' => __DIR__ . '/Uploads',
'smtp' => [
'host' => 'smtp.exemplo.com',
'port' => 587,
'user' => 'seu.email@exemplo.com',
'pass' => 'sua_senha_smtp',
'from' => 'no-reply@escola.com',
'from_name' => 'Escola'
]
];