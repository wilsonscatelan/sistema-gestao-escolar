<?php
require_once 'config/conexao.php';

echo "<h1>Criando usuários de teste...</h1>";

$senha_padrao = '123456';
$senha_hash = password_hash($senha_padrao, PASSWORD_DEFAULT);
echo "A senha padrão para todos é: <b>{$senha_padrao}</b><br><br>";

try {
    $email_adm = 'adm@escola.com';
    $sql_adm = "INSERT INTO usuarios (email, senha, tipo_usuario) VALUES (?, ?, 'adm') ON CONFLICT (email) DO NOTHING";
    $stmt_adm = $pdo->prepare($sql_adm);
    $stmt_adm->execute([$email_adm, $senha_hash]);
    echo "Usuário ADM criado ou já existente: <b>{$email_adm}</b><br>";

    $prof = $pdo->query("SELECT id_professor, email FROM professores LIMIT 1")->fetch(PDO::FETCH_ASSOC);
    if ($prof) {
        $email_prof = $prof['email'];
        $id_ref_prof = $prof['id_professor'];
        $sql_prof = "INSERT INTO usuarios (email, senha, tipo_usuario, id_referencia) VALUES (?, ?, 'professor', ?) ON CONFLICT (email) DO NOTHING";
        $stmt_prof = $pdo->prepare($sql_prof);
        $stmt_prof->execute([$email_prof, $senha_hash, $id_ref_prof]);
        echo "Usuário Professor criado ou já existente: <b>{$email_prof}</b> (ligado ao professor ID: {$id_ref_prof})<br>";
    } else {
        echo "Nenhum professor encontrado para criar o usuário.<br>";
    }

    $aluno = $pdo->query("SELECT id_aluno, nome_aluno FROM alunos LIMIT 1")->fetch(PDO::FETCH_ASSOC);
    if ($aluno) {
        $email_aluno = strtolower(str_replace(' ', '.', $aluno['nome_aluno'])) . '@escola.com';
        $id_ref_aluno = $aluno['id_aluno'];
        $sql_aluno = "INSERT INTO usuarios (email, senha, tipo_usuario, id_referencia) VALUES (?, ?, 'aluno', ?) ON CONFLICT (email) DO NOTHING";
        $stmt_aluno = $pdo->prepare($sql_aluno);
        $stmt_aluno->execute([$email_aluno, $senha_hash, $id_ref_aluno]);
        echo "Usuário Aluno criado ou já existente: <b>{$email_aluno}</b> (ligado ao aluno ID: {$id_ref_aluno})<br>";
    } else {
        echo "Nenhum aluno encontrado para criar o usuário.<br>";
    }

} catch (PDOException $e) {
    die("Erro ao criar usuários: " . $e->getMessage());
}
?>