<?php
require_once '../config/conexao.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_aluno = intval($_POST['id_aluno']);
    if ($id_aluno <= 0) { die("ID de aluno inválido."); }

    $pdo->beginTransaction();

    try {
        $nome_aluno = trim($_POST['nome_aluno']);
        $data_nascimento = trim($_POST['data_nascimento']);
        $cpf = trim($_POST['cpf']); 
        $telefone = trim($_POST['telefone']); 
        $caminho_foto = $_POST['foto_antiga'] ?? null;

        if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = '../uploads/';
            if ($caminho_foto && file_exists($upload_dir . $caminho_foto)) {
                unlink($upload_dir . $caminho_foto);
            }
            $nome_foto = uniqid() . '-' . basename($_FILES['foto']['name']);
            if (move_uploaded_file($_FILES['foto']['tmp_name'], $upload_dir . $nome_foto)) {
                $caminho_foto = $nome_foto;
            }
        }

        $sql_update_aluno = "UPDATE alunos SET nome_aluno = :nome, data_nascimento = :data_nasc, caminho_foto = :foto, cpf = :cpf, telefone = :telefone WHERE id_aluno = :id";
        $stmt_update_aluno = $pdo->prepare($sql_update_aluno);
        $stmt_update_aluno->execute([
            ':nome' => $nome_aluno,
            ':data_nasc' => $data_nascimento,
            ':foto' => $caminho_foto,
            ':cpf' => $cpf, 
            ':telefone' => $telefone, 
            ':id' => $id_aluno
        ]);

        $sql_delete_matriculas = "DELETE FROM matriculas WHERE id_aluno_fk = :id_aluno";
        $stmt_delete = $pdo->prepare($sql_delete_matriculas);
        $stmt_delete->bindParam(':id_aluno', $id_aluno, PDO::PARAM_INT);
        $stmt_delete->execute();
        
        if (isset($_POST['cursos']) && is_array($_POST['cursos'])) {
            $sql_insert_matricula = "INSERT INTO matriculas (id_aluno_fk, id_turma_fk) VALUES (:id_aluno, :id_turma)";
            $stmt_insert = $pdo->prepare($sql_insert_matricula);
            
            foreach ($_POST['cursos'] as $id_curso) {
                $stmt_insert->execute([
                    ':id_aluno' => $id_aluno,
                    ':id_turma' => intval($id_curso)
                ]);
            }
        }
        
        $pdo->commit();
        
        header("Location: index.php?atualizado=1");
        exit();

    } catch (Exception $e) {
        $pdo->rollBack();
        die("Erro ao atualizar o aluno: " . $e->getMessage());
    }
}
?>