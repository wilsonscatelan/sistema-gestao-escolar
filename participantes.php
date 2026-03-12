<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php?erro=2');
    exit();
}

require_once 'config/conexao.php';

$id_turma = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id_turma <= 0) { echo "ID da turma inválido."; exit(); }

$sql_turma = "SELECT * FROM turmas WHERE id_turma = :id";
$stmt_turma = $pdo->prepare($sql_turma);
$stmt_turma->bindParam(':id', $id_turma, PDO::PARAM_INT);
$stmt_turma->execute();
$turma = $stmt_turma->fetch(PDO::FETCH_ASSOC);

if (!$turma) { echo "Turma não encontrada."; exit(); }

$sql_professor = "SELECT * FROM professores WHERE id_professor = :id_prof";
$stmt_professor = $pdo->prepare($sql_professor);
$stmt_professor->bindParam(':id_prof', $turma['id_professor_fk'], PDO::PARAM_INT);
$stmt_professor->execute();
$professor = $stmt_professor->fetch(PDO::FETCH_ASSOC);

$sql_alunos = "SELECT a.*, u.email as email_aluno FROM alunos a JOIN matriculas m ON a.id_aluno = m.id_aluno_fk LEFT JOIN usuarios u ON a.id_aluno = u.id_referencia AND u.tipo_usuario = 'aluno' WHERE m.id_turma_fk = :id_turma ORDER BY a.nome_aluno ASC";
$stmt_alunos = $pdo->prepare($sql_alunos);
$stmt_alunos->bindParam(':id_turma', $id_turma, PDO::PARAM_INT);
$stmt_alunos->execute();
$alunos_da_turma = $stmt_alunos->fetchAll(PDO::FETCH_ASSOC);

$nome_usuario = $_SESSION['usuario_nome'];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Participantes - <?= htmlspecialchars($turma['nome_turma']) ?></title>
    <link rel="stylesheet" href="/projeto_escola/style.css">
</head>
<body>
    <div class="navbar">
        <span><strong>Portal EAD</strong></span>
        <span>Bem-vindo(a), <?= htmlspecialchars($nome_usuario) ?>! | <a href="logout.php">Sair</a></span>
    </div>

    <div class="container">
        <a href="turma_detalhes.php?id=<?= $id_turma ?>" class="btn-voltar">&larr; Voltar para o Curso</a>

        <h2>Participantes do Curso: <?= htmlspecialchars($turma['nome_turma']) ?></h2>
        
        <hr>
        <h3>Professor(a)</h3>
        <?php if ($professor): ?>
            <table class="user-list">
                <tbody>
                    <tr>
                        <td>
                            <?php if (!empty($professor['foto_professor'])) : ?>
                                <img src="uploads/<?= htmlspecialchars($professor['foto_professor']) ?>" alt="Foto" class="user-avatar">
                            <?php else : ?>
                                <img src="https://via.placeholder.com/40" alt="Sem Foto" class="user-avatar">
                            <?php endif; ?>
                            <span class="user-name" style="color: black; font-weight: bold;"><?= htmlspecialchars($professor['nome']) ?></span>
                        </td>
                        <td><?= htmlspecialchars($professor['email']) ?></td>
                    </tr>
                </tbody>
            </table>
        <?php endif; ?>

        <h3 style="margin-top: 40px;">Colegas de Turma</h3>
        <?php if (count($alunos_da_turma) > 0): ?>
            <table class="user-list">
                 <thead>
                    <tr>
                        <th>Nome do Aluno</th>
                        <th>Email</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($alunos_da_turma as $aluno): ?>
                        <tr>
                            <td>
                                <?php if (!empty($aluno['caminho_foto'])) : ?>
                                    <img src="uploads/<?= htmlspecialchars($aluno['caminho_foto']) ?>" alt="Foto" class="user-avatar">
                                <?php else : ?>
                                    <img src="https://via.placeholder.com/40" alt="Sem Foto" class="user-avatar">
                                <?php endif; ?>
                                <span style="vertical-align: middle;"><?= htmlspecialchars($aluno['nome_aluno']) ?></span>
                            </td>
                            <td><?= htmlspecialchars($aluno['email_aluno'] ?? 'N/A') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Nenhum aluno matriculado nesta turma.</p>
        <?php endif; ?>

    </div>
</body>
</html>