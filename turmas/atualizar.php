<?php
require_once '../config/conexao.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_turma = intval($_POST['id_turma']);
    $nome_turma = trim($_POST['nome_turma']);
    $ano = trim($_POST['ano']);
    $id_professor_fk = !empty($_POST['id_professor_fk']) ? intval($_POST['id_professor_fk']) : null;
    $descricao = trim($_POST['descricao']); // Novo
    $carga_horaria = !empty($_POST['carga_horaria']) ? intval($_POST['carga_horaria']) : null; 
    $imagem_anterior = $_POST['imagem_anterior'] ?? null;

    $caminho_imagem = $imagem_anterior;
    if (isset($_FILES['nova_imagem']) && $_FILES['nova_imagem']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = '../uploads/';
        if ($imagem_anterior && file_exists($upload_dir . $imagem_anterior)) {
            unlink($upload_dir . $imagem_anterior);
        }
        $nome_imagem = uniqid() . '-' . basename($_FILES['nova_imagem']['name']);
        if (move_uploaded_file($_FILES['nova_imagem']['tmp_name'], $upload_dir . $nome_imagem)) {
            $caminho_imagem = $nome_imagem;
        }
    }

    if (!empty($nome_turma) && !empty($ano) && $id_turma > 0) {
        $sql = "UPDATE turmas SET nome_turma = :nome, ano = :ano, id_professor_fk = :id_prof, imagem_turma = :imagem, descricao = :descricao, carga_horaria = :carga WHERE id_turma = :id";
        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            ':nome' => $nome_turma,
            ':ano' => $ano,
            ':id_prof' => $id_professor_fk,
            ':imagem' => $caminho_imagem,
            ':descricao' => $descricao,
            ':carga' => $carga_horaria,
            ':id' => $id_turma
        ]);
        
        header("Location: index.php?atualizado=1");
        exit();
    } else {
        echo "Dados inválidos para atualização.";
    }
}
?>