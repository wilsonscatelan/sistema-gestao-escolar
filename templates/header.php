<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


if (!isset($_SESSION['usuario_id'])) {
    header('Location: /projeto_escola/login.php?erro=2'); 
    exit();
}

if ($_SESSION['usuario_tipo'] != 'adm') {
    echo "<h1>Acesso Negado</h1>";
    echo "<p>Você não tem permissão para acessar esta página.</p>";
    exit();
}


$nome_usuario = $_SESSION['usuario_nome'];
?>
<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Gestão Escolar</title>
    <link rel="stylesheet" href="/projeto_escola/style.css"></head>
<body>

<header>
    <h1>Gestão Escolar</h1>
    <div class="user-info">
        <span>Bem-vindo(a), <?= htmlspecialchars($nome_usuario) ?>!</span>
    </div>
</header>

<nav>
    <a href="/projeto_escola/index.php">Início</a>
    <a href="/projeto_escola/alunos/">Alunos</a>
    <a href="/projeto_escola/professores/">Professores</a>
    <a href="/projeto_escola/turmas/">Turmas</a>
    <a href="/projeto_escola/logout.php" style="color: #ffc107; float: right;">Sair</a>
</nav>

<main>