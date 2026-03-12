<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] != 'professor') { header('Location: login.php?erro=2'); exit(); }
require_once 'config/conexao.php';
$nome_usuario = $_SESSION['usuario_nome'];
$id_professor = $_SESSION['id_referencia'];
$sql = "SELECT * FROM turmas WHERE id_professor_fk = :id_professor ORDER BY ano DESC, nome_turma ASC";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':id_professor', $id_professor, PDO::PARAM_INT);
$stmt->execute();
$cursos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Painel do Professor</title>
    <link rel="stylesheet" href="/projeto_escola/style.css">
</head>
<body>
    <div class="navbar">
        <span><strong>Painel do Professor</strong></span>
        <span>Bem-vindo(a), <?= htmlspecialchars($nome_usuario) ?>! | <a href="logout.php">Sair</a></span>
    </div>
    <div class="container">
        <h2>Minhas Turmas</h2>
        <?php if ($cursos && count($cursos) > 0) : ?>
            <div class="course-grid">
                <?php foreach ($cursos as $curso) : ?>
                    <div class="course-card">
                        <?php if(!empty($curso['imagem_turma'])): ?>
                            <img src="/projeto_escola/uploads/<?= htmlspecialchars($curso['imagem_turma']) ?>" alt="Imagem da Turma">
                        <?php else: ?>
                            <img src="https://via.placeholder.com/280x150.png?text=Turma" alt="Imagem Padrão">
                        <?php endif; ?>
                        <div class="course-card-content">
                            <h3><?= htmlspecialchars($curso['nome_turma']) ?></h3>
                            <p><strong>Ano:</strong> <?= htmlspecialchars($curso['ano']) ?></p>
                            <p><strong>Carga Horária:</strong> <?= htmlspecialchars($curso['carga_horaria']) ?>h</p>
                            <p style="font-size: 0.8em; color: #666; margin-top: 10px;"><?= htmlspecialchars($curso['descricao']) ?></p>
                        </div>
                        <div class="course-card-footer">
                            <a href="turma_detalhes.php?id=<?= $curso['id_turma'] ?>" class="btn-acesso">Acessar Turma</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else : ?>
            <p>Você não está associado a nenhuma turma no momento.</p>
        <?php endif; ?>
    </div>
</body>
</html>