<?php

session_start();

require_once 'config/conexao.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $sql = "SELECT * FROM usuarios WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario && password_verify($senha, $usuario['senha'])) {

        $_SESSION['usuario_id'] = $usuario['id_usuario'];
        $_SESSION['usuario_tipo'] = $usuario['tipo_usuario'];
        $_SESSION['id_referencia'] = $usuario['id_referencia'];


        if ($usuario['tipo_usuario'] == 'adm') {
            $_SESSION['usuario_nome'] = 'Administrador';
        } elseif ($usuario['tipo_usuario'] == 'professor') {
            $sql_nome = "SELECT nome FROM professores WHERE id_professor = :id";
            $stmt_nome = $pdo->prepare($sql_nome);
            $stmt_nome->bindParam(':id', $usuario['id_referencia'], PDO::PARAM_INT);
            $stmt_nome->execute();
            $result = $stmt_nome->fetch(PDO::FETCH_ASSOC);
            $_SESSION['usuario_nome'] = $result['nome'];
        } elseif ($usuario['tipo_usuario'] == 'aluno') {
            $sql_nome = "SELECT nome_aluno FROM alunos WHERE id_aluno = :id";
            $stmt_nome = $pdo->prepare($sql_nome);
            $stmt_nome->bindParam(':id', $usuario['id_referencia'], PDO::PARAM_INT);
            $stmt_nome->execute();
            $result = $stmt_nome->fetch(PDO::FETCH_ASSOC);
            $_SESSION['usuario_nome'] = $result['nome_aluno'];
        }

        if ($_SESSION['usuario_tipo'] == 'adm') {
            header("Location: index.php"); 
            exit();
        } elseif ($_SESSION['usuario_tipo'] == 'professor') {
            header("Location: painel_professor.php"); 
            exit();
        } elseif ($_SESSION['usuario_tipo'] == 'aluno') {
            header("Location: painel_aluno.php"); 
            exit();
        }
    } else {
        header("Location: login.php?erro=1");
        exit();
    }
} else {
    header("Location: login.php");
    exit();
}
?>