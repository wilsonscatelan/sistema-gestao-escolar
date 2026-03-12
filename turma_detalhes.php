<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php?erro=2');
    exit();
}

require_once 'config/conexao.php';

$id_turma = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id_turma <= 0) { echo "ID da turma inválido."; exit(); }

$sql_turma = "SELECT nome_turma FROM turmas WHERE id_turma = :id";
$stmt_turma = $pdo->prepare($sql_turma);
$stmt_turma->bindParam(':id', $id_turma, PDO::PARAM_INT);
$stmt_turma->execute();
$turma = $stmt_turma->fetch(PDO::FETCH_ASSOC);

if (!$turma) { echo "Turma não encontrada."; exit(); }

$nome_usuario = $_SESSION['usuario_nome'];
$tipo_usuario = $_SESSION['usuario_tipo'];

$url_voltar = 'index.php';
if ($tipo_usuario == 'aluno') { $url_voltar = 'painel_aluno.php'; } 
elseif ($tipo_usuario == 'professor') { $url_voltar = 'painel_professor.php'; }
elseif ($tipo_usuario == 'adm') { $url_voltar = 'turmas/index.php'; }
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Detalhes do Curso - <?= htmlspecialchars($turma['nome_turma']) ?></title>
    <link rel="stylesheet" href="/projeto_escola/style.css">
</head>
<body>
    <div class="navbar">
        <span><strong>Portal EAD</strong></span>
        <span>Bem-vindo(a), <?= htmlspecialchars($nome_usuario) ?>! | <a href="logout.php">Sair</a></span>
    </div>

    <div class="container">
        <a href="<?= $url_voltar ?>" class="btn-voltar">&larr; Voltar</a>

        <div class="course-header">
            <h2><?= htmlspecialchars($turma['nome_turma']) ?></h2>
        </div>

        <?php if ($tipo_usuario == 'aluno'): ?>
            <div style="text-align: center; margin-bottom: 20px;">
                <a href="participantes.php?id=<?= $id_turma ?>" class="btn-gerenciar" style="background-color: #17a2b8;">Ver Colegas e Professor</a>
            </div>
        <?php endif; ?>

        <?php if ($tipo_usuario == 'professor'): ?>
            <div style="text-align: center;">
                <a href="gerenciar_turma.php?id=<?= $id_turma ?>" class="btn-gerenciar">Gerenciar Alunos e Notas</a>
            </div>
        <?php endif; ?>
        
        <div class="topic-grid" style="margin-top: 40px;">
            <div class="topic-box"><h4>01 - Apresentação</h4><p>Boas-vindas e plano de ensino.</p></div>
            <div class="topic-box"><h4>02 - Fundamentos</h4><p>Conteúdo da primeira unidade.</p></div>
            <div class="topic-box"><h4>03 - Aprofundamento</h4><p>Conteúdo da segunda unidade.</p></div>
            <div class="topic-box"><h4>04 - Atividade Prática</h4><p>Envio do trabalho da disciplina.</p></div>
            <div class="topic-box"><h4>Avaliação</h4><p>Prova final da disciplina.</p></div>
        </div>
    </div>
</body>
</html>