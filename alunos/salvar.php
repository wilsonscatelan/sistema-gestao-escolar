<?php
session_start();

require_once __DIR__ . '/../vendor/autoload.php';
use App\Services\EmailService;

require_once '../config/conexao.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Dados do formulário
    $nome_aluno = $_POST['nome_aluno'];
    $data_nascimento = $_POST['data_nascimento'];
    $cpf = $_POST['cpf'];
    $telefone = $_POST['telefone'];

    // Email gerado automaticamente
    $email_gerado = strtolower(explode(" ", $nome_aluno)[0]) . "@escola.com";

    $pdo->beginTransaction();

    try {

        // 1️⃣ INSERIR NA TABELA ALUNOS
        $sqlAluno = "INSERT INTO alunos (nome_aluno, data_nascimento, cpf, telefone)
                     VALUES (:nome, :data, :cpf, :telefone)
                     RETURNING id_aluno";

        $stmtAluno = $pdo->prepare($sqlAluno);
        $stmtAluno->execute([
            ':nome' => $nome_aluno,
            ':data' => $data_nascimento,
            ':cpf' => $cpf,
            ':telefone' => $telefone
        ]);

        $id_aluno = $stmtAluno->fetchColumn();


        // 2️⃣ INSERIR NA TABELA USUÁRIOS
        $sqlUser = "INSERT INTO usuarios (email, senha, tipo_usuario, id_referencia)
                    VALUES (:email, :senha, 'aluno', :id)";

        $stmtUser = $pdo->prepare($sqlUser);
        $stmtUser->execute([
            ':email' => $email_gerado,
            ':senha' => password_hash("123456", PASSWORD_DEFAULT),
            ':id'    => $id_aluno
        ]);

        // 3️⃣ CONFIRMAR TUDO
        $pdo->commit();


        // 4️⃣ ENVIAR E-MAIL APÓS O COMMIT
        // Envio de email controlado para não quebrar no Linux Mint da apresentação
$enviar_email = false; // <-- MUDE PARA true SOMENTE EM CASA

if ($enviar_email) {

    $emailService = new EmailService();

    $mensagemHtml = "
        <h2>Olá, $nome_aluno!</h2>
        <p>Seu cadastro foi realizado com sucesso no sistema da Escola.</p>
        <p><strong>Email de acesso:</strong> $email_gerado</p>
        <p><strong>Senha padrão:</strong> 123456</p>
        <p>Recomendamos alterar sua senha após o primeiro login.</p>
    ";

    $emailService->enviarEmail(
        $email,
        "Cadastro realizado com sucesso",
        $mensagemHtml,
        "Seu cadastro foi realizado com sucesso. Email: $email_gerado - Senha: 123456"
    );
}



        $_SESSION['sucesso'] = "Aluno <strong>$nome_aluno</strong> cadastrado com sucesso!";
        header("Location: index.php");
        exit();

    } catch (PDOException $e) {

        $pdo->rollBack();

        if ($e->getCode() == "23505") {
            $_SESSION['erro'] = "Erro: Este CPF já está cadastrado.";
        } else {
            $_SESSION['erro'] = "Erro ao cadastrar aluno: " . $e->getMessage();
        }

        header("Location: cadastrar.php");
        exit();
    }
}
?>
