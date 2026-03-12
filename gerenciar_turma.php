<?php
session_start();

if (!isset($_SESSION['usuario_id']) || ($_SESSION['usuario_tipo'] != 'professor' && $_SESSION['usuario_tipo'] != 'adm')) {
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

$sql_alunos = "SELECT
                   a.id_aluno,
                   a.nome_aluno,
                   u.email AS email_aluno,
                   a.caminho_foto,
                   m.id_matricula
               FROM
                   matriculas m
               JOIN
                   alunos a ON m.id_aluno_fk = a.id_aluno
               LEFT JOIN
                   usuarios u ON a.id_aluno = u.id_referencia AND u.tipo_usuario = 'aluno'
               WHERE
                   m.id_turma_fk = :id_turma
               ORDER BY
                   a.nome_aluno ASC";
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
    <title>Gerenciar Turma - <?= htmlspecialchars($turma['nome_turma']) ?></title>
    <style>
        body { font-family: sans-serif; margin: 0; background-color: #f8f9fa; } .navbar { background-color: #fff; padding: 15px 30px; border-bottom: 1px solid #ddd; display: flex; justify-content: space-between; align-items: center; } .container { padding: 30px; max-width: 1200px; margin: auto; } .btn-voltar { display: inline-block; margin-bottom: 20px; padding: 8px 15px; background-color: #6c757d; color: white; text-decoration: none; border-radius: 4px; } table { width: 100%; border-collapse: collapse; margin-top: 20px; } th, td { padding: 12px; border: 1px solid #ddd; text-align: left; vertical-align: middle; } th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <div class="navbar">
        <span><strong>Portal EAD</strong></span>
        <span>Bem-vindo(a), <?= htmlspecialchars($nome_usuario) ?>! | <a href="logout.php">Sair</a></span>
    </div>

    <div class="container">
        <a href="turma_detalhes.php?id=<?= $id_turma ?>" class="btn-voltar">&larr; Voltar para a Turma</a>

        <h2>Gerenciar Alunos e Notas</h2>
        <p><strong>Turma:</strong> <?= htmlspecialchars($turma['nome_turma']) ?></p>
        
        <?php if (count($alunos_da_turma) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Foto</th>
                        <th>ID</th>
                        <th>Aluno</th>
                        <th>Email</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($alunos_da_turma as $aluno): ?>
                        <tr>
                            <td>
                                <?php if (!empty($aluno['caminho_foto'])) : ?>
                                    <img src="uploads/<?= htmlspecialchars($aluno['caminho_foto']) ?>" alt="Foto" width="40" height="40" style="border-radius: 50%; object-fit: cover;">
                                <?php else : ?>
                                    <img src="https://via.placeholder.com/40" alt="Sem Foto" style="border-radius: 50%;">
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($aluno['id_aluno']) ?></td>
                            <td><?= htmlspecialchars($aluno['nome_aluno']) ?></td>
                            <td><?= htmlspecialchars($aluno['email_aluno'] ?? 'N/A') ?></td>
                            <td>
                                <a href="lancar_notas.php?matricula_id=<?= $aluno['id_matricula'] ?>">Lançar/Ver Notas</a>
                            </td>
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