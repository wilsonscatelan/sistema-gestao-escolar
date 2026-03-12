<?php
require_once '../config/conexao.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_professor = trim($_POST['id_professor']);
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $telefone = trim($_POST['telefone']);
    $foto_anterior = $_POST['foto_anterior'] ?? null;

    $caminho_foto = $foto_anterior;

    if (isset($_FILES['nova_foto']) && $_FILES['nova_foto']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = '../uploads/';
        $nome_foto = basename($_FILES['nova_foto']['name']);
        $nome_unico = uniqid() . '-' . $nome_foto;
        $caminho_completo = $upload_dir . $nome_unico;

        if (move_uploaded_file($_FILES['nova_foto']['tmp_name'], $caminho_completo)) {
            $caminho_foto = $nome_unico;
            if ($foto_anterior && file_exists($upload_dir . $foto_anterior)) {
                unlink($upload_dir . $foto_anterior);
            }
        }
    }

    if (!empty($nome) && !empty($email) && $id_professor > 0) {
        $sql = "UPDATE professores SET nome = :nome, email = :email, telefone = :telefone, foto_professor = :foto WHERE id_professor = :id";
        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(':nome', $nome, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':telefone', $telefone, PDO::PARAM_STR);
        $stmt->bindParam(':foto', $caminho_foto, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id_professor, PDO::PARAM_INT);

        try {
            if ($stmt->execute()) {
                $sql_usuario = "UPDATE usuarios SET email = :email WHERE id_referencia = :id AND tipo_usuario = 'professor'";
                $stmt_usuario = $pdo->prepare($sql_usuario);
                $stmt_usuario->bindParam(':email', $email, PDO::PARAM_STR);
                $stmt_usuario->bindParam(':id', $id_professor, PDO::PARAM_INT);
                $stmt_usuario->execute();

                header("Location: index.php?sucesso=2"); 
                exit();
            }
        } catch (PDOException $e) {
            die("Erro ao atualizar professor: " . $e->getMessage());
        }
    } else {
        echo "Dados inválidos para atualização.";
    }
} else {
    echo "Acesso inválido.";
}
?>