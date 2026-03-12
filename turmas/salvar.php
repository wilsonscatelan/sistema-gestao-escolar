<?php
require_once '../config/conexao.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome_turma = trim($_POST['nome_turma']);
    $ano = trim($_POST['ano']);
    $id_professor_fk = !empty($_POST['id_professor_fk']) ? intval($_POST['id_professor_fk']) : null;
    $descricao = trim($_POST['descricao']); 
    $carga_horaria = !empty($_POST['carga_horaria']) ? intval($_POST['carga_horaria']) : null; 
    
    $caminho_imagem = null;
    if (isset($_FILES['imagem_turma']) && $_FILES['imagem_turma']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = '../uploads/';
        $nome_arquivo_original = basename($_FILES['imagem_turma']['name']);
        $nome_unico = uniqid() . '-' . $nome_arquivo_original;
        $caminho_completo = $upload_dir . $nome_unico;
        if (move_uploaded_file($_FILES['imagem_turma']['tmp_name'], $caminho_completo)) {
            $caminho_imagem = $nome_unico;
        }
    }

    if (!empty($nome_turma) && !empty($ano)) {
        $sql = "INSERT INTO turmas (nome_turma, ano, id_professor_fk, imagem_turma, descricao, carga_horaria) VALUES (:nome, :ano_curso, :id_prof, :imagem, :descricao, :carga)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':nome' => $nome_turma,
            ':ano_curso' => $ano,
            ':id_prof' => $id_professor_fk,
            ':imagem' => $caminho_imagem,
            ':descricao' => $descricao, 
            ':carga' => $carga_horaria 
        ]);
        header("Location: index.php?sucesso=1");
        exit();
    }
}
?>