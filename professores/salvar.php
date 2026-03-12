<?php
session_start();
require_once '../config/conexao.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $pdo->beginTransaction();
    try {
        $nome = trim($_POST['nome']);
        $email = trim($_POST['email']);
        $telefone = trim($_POST['telefone']);
        $caminho_foto = null;

        if (isset($_FILES['foto_professor']) && $_FILES['foto_professor']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = '../uploads/';
            $nome_foto = uniqid() . '-' . basename($_FILES['foto_professor']['name']);
            if (move_uploaded_file($_FILES['foto_professor']['tmp_name'], $upload_dir . $nome_foto)) {
                $caminho_foto = $nome_foto;
            }
        }

        if (empty($nome) || empty($email)) {
            throw new Exception("Nome e Email são obrigatórios.");
        }
        
        $sql_prof = "INSERT INTO professores (nome, email, telefone, foto_professor) VALUES (:nome, :email, :telefone, :foto)";
        $stmt_prof = $pdo->prepare($sql_prof);
        $stmt_prof->execute([
            ':nome' => $nome,
            ':email' => $email,
            ':telefone' => $telefone,
            ':foto' => $caminho_foto
        ]);
        $id_professor = $pdo->lastInsertId();

        $senha_padrao_hash = password_hash('123456', PASSWORD_DEFAULT);
        
        $sql_usuario = "INSERT INTO usuarios (email, senha, tipo_usuario, id_referencia) VALUES (:email, :senha, 'professor', :id_ref)";
        $stmt_usuario = $pdo->prepare($sql_usuario);
        $stmt_usuario->execute([
            ':email' => $email,
            ':senha' => $senha_padrao_hash,
            ':id_ref' => $id_professor
        ]);
        
        $pdo->commit();

        $_SESSION['sucesso'] = "Professor <strong>" . htmlspecialchars($nome) . "</strong> cadastrado com sucesso! O login dele pode ser feito com o email informado e a senha padrão '123456'.";
        header("Location: index.php");
        exit();

    } catch (PDOException $e) {
        $pdo->rollBack();
        if ($e->getCode() == '23505') { 
            die("Erro ao cadastrar professor: O email '{$email}' já está em uso.");
        }
        die("Erro ao cadastrar professor: " . $e->getMessage());
    } catch (Exception $e) {
        $pdo->rollBack();
        die("Erro: " . $e->getMessage());
    }
}
?>