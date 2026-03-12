<?php
require_once '../config/conexao.php';

if (isset($_GET['id'])) {
    $id_aluno = intval($_GET['id']);

    $pdo->beginTransaction();

    try {
        $sql_foto = "SELECT caminho_foto FROM alunos WHERE id_aluno = :id";
        $stmt_foto = $pdo->prepare($sql_foto);
        $stmt_foto->bindParam(':id', $id_aluno, PDO::PARAM_INT);
        $stmt_foto->execute();
        $aluno = $stmt_foto->fetch(PDO::FETCH_ASSOC);

        $sql_delete_usuario = "DELETE FROM usuarios WHERE id_referencia = :id_aluno AND tipo_usuario = 'aluno'";
        $stmt_delete_usuario = $pdo->prepare($sql_delete_usuario);
        $stmt_delete_usuario->bindParam(':id_aluno', $id_aluno, PDO::PARAM_INT);
        $stmt_delete_usuario->execute();
        
        $sql_delete_aluno = "DELETE FROM alunos WHERE id_aluno = :id";
        $stmt_delete_aluno = $pdo->prepare($sql_delete_aluno);
        $stmt_delete_aluno->bindParam(':id', $id_aluno, PDO::PARAM_INT);
        
        if ($stmt_delete_aluno->execute()) {
            if ($aluno && !empty($aluno['caminho_foto'])) {
                $caminho_arquivo = '../uploads/' . $aluno['caminho_foto'];
                if (file_exists($caminho_arquivo)) {
                    unlink($caminho_arquivo);
                }
            }
            $pdo->commit();
            header("Location: index.php?excluido=1");
            exit();
        } else {
            $pdo->rollBack(); 
            echo "Erro ao excluir o aluno.";
        }

    } catch (PDOException $e) {
        $pdo->rollBack(); 
        die("Erro ao excluir o aluno: " . $e->getMessage());
    }
} else {
    header("Location: index.php");
    exit();
}
?>