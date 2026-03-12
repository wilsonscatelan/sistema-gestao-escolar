<?php
require_once '../config/conexao.php';

if (isset($_GET['id'])) {
    $id_turma = intval($_GET['id']);

    $sql_imagem = "SELECT imagem_turma FROM turmas WHERE id_turma = :id";
    $stmt_imagem = $pdo->prepare($sql_imagem);
    $stmt_imagem->bindParam(':id', $id_turma, PDO::PARAM_INT);
    $stmt_imagem->execute();
    $turma = $stmt_imagem->fetch(PDO::FETCH_ASSOC);

    try {
        $sql_delete = "DELETE FROM turmas WHERE id_turma = :id";
        $stmt_delete = $pdo->prepare($sql_delete);
        $stmt_delete->bindParam(':id', $id_turma, PDO::PARAM_INT);

        if ($stmt_delete->execute()) {
            if ($turma && !empty($turma['imagem_turma'])) {
                $caminho_arquivo = '../uploads/' . $turma['imagem_turma'];
                if (file_exists($caminho_arquivo)) {
                    unlink($caminho_arquivo); 
                }
            }
            header("Location: index.php?excluido=1");
            exit();
        } else {
            echo "Erro ao excluir a turma.";
        }
    } catch (PDOException $e) {
        if ($e->getCode() == '23503') {
            die("<b>Erro:</b> Não é possível excluir esta turma porque ainda existem alunos matriculados nela. Por favor, remova ou mova os alunos desta turma antes de excluí-la.");
        } else {
            die("Erro ao excluir a turma: " . $e->getMessage());
        }
    }
} else {
    header("Location: index.php");
    exit();
}
?>