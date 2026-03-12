<?php
require_once '../config/conexao.php';

if (isset($_GET['id'])) {
    $id_professor = intval($_GET['id']);

    $pdo->beginTransaction();

    try {
        $sql_info = "SELECT email, foto_professor FROM professores WHERE id_professor = :id";
        $stmt_info = $pdo->prepare($sql_info);
        $stmt_info->bindParam(':id', $id_professor, PDO::PARAM_INT);
        $stmt_info->execute();
        $professor = $stmt_info->fetch(PDO::FETCH_ASSOC);

        if ($professor && !empty($professor['email'])) {
            $sql_delete_usuario = "DELETE FROM usuarios WHERE email = :email AND tipo_usuario = 'professor'";
            $stmt_delete_usuario = $pdo->prepare($sql_delete_usuario);
            $stmt_delete_usuario->bindParam(':email', $professor['email'], PDO::PARAM_STR);
            $stmt_delete_usuario->execute();
        }

        $sql_delete_prof = "DELETE FROM professores WHERE id_professor = :id";
        $stmt_delete_prof = $pdo->prepare($sql_delete_prof);
        $stmt_delete_prof->bindParam(':id', $id_professor, PDO::PARAM_INT);
        
        if ($stmt_delete_prof->execute()) {
            if ($professor && !empty($professor['foto_professor'])) {
                $caminho_arquivo = '../uploads/' . $professor['foto_professor'];
                if (file_exists($caminho_arquivo)) {
                    unlink($caminho_arquivo);
                }
            }
            $pdo->commit();
            header("Location: index.php?excluido=1");
            exit();
        } else {
            $pdo->rollBack();
            echo "Erro ao excluir o professor.";
        }

    } catch (PDOException $e) {
        $pdo->rollBack(); 
        if ($e->getCode() == '23503') {
            die("<b>Erro:</b> Não é possível excluir este professor porque ele ainda é responsável por uma ou mais turmas. Por favor, altere o professor responsável nessas turmas primeiro.");
        }
        die("Erro ao excluir o professor: " . $e->getMessage());
    }
} else {
    header("Location: index.php");
    exit();
}
?>