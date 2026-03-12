<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] != 'aluno') { header('Location: login.php?erro=2'); exit(); }
require_once 'config/conexao.php';
$nome_usuario = $_SESSION['usuario_nome'];
$id_aluno = $_SESSION['id_referencia'];

$sql = "SELECT
            t.id_turma, t.nome_turma, t.ano, t.imagem_turma, t.descricao, t.carga_horaria,
            p.nome AS nome_professor
        FROM matriculas m
        JOIN turmas t ON m.id_turma_fk = t.id_turma
        JOIN professores p ON t.id_professor_fk = p.id_professor
        WHERE m.id_aluno_fk = :id_aluno";

$stmt = $pdo->prepare($sql);
$stmt->bindParam(':id_aluno', $id_aluno, PDO::PARAM_INT);
$stmt->execute();
$cursos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Portal do Aluno</title>
    <link rel="stylesheet" href="/projeto_escola/style.css">
</head>
<body>
    <div class="navbar">
        <span><strong>Portal do Aluno</strong></span>
        <span>Bem-vindo(a), <?= htmlspecialchars($nome_usuario) ?>! | <a href="logout.php">Sair</a></span>
    </div>
    <div class="container">
        <h2>Meus Cursos</h2>
        <?php if ($cursos && count($cursos) > 0) : ?>
            <div class="course-grid">
                <?php foreach ($cursos as $curso) : ?>
                    <div class="course-card">
                        <?php if(!empty($curso['imagem_turma'])): ?>
                            <img src="/projeto_escola/uploads/<?= htmlspecialchars($curso['imagem_turma']) ?>" alt="Imagem do Curso">
                        <?php else: ?>
                            <img src="https://via.placeholder.com/280x150.png?text=Curso" alt="Imagem Padrão">
                        <?php endif; ?>
                        <div class="course-card-content">
                            <h3><?= htmlspecialchars($curso['nome_turma']) ?></h3>
                            <p><strong>Ano:</strong> <?= htmlspecialchars($curso['ano']) ?></p>
                            <p><strong>Carga Horária:</strong> <?= htmlspecialchars($curso['carga_horaria']) ?>h</p>
                            <p><strong>Professor:</strong> <?= htmlspecialchars($curso['nome_professor']) ?></p>
                            <p style="font-size: 0.8em; color: #666; margin-top: 10px;"><?= htmlspecialchars($curso['descricao']) ?></p>
                        </div>
                        <div class="course-card-footer">
                            <a href="turma_detalhes.php?id=<?= $curso['id_turma'] ?>" class="btn-acesso">Acesso</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else : ?>
            <p>Você não está matriculado em nenhum curso no momento.</p>
        <?php endif; ?>
    </div>
</body>
</html>