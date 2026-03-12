<?php
session_start();
if (!isset($_SESSION['usuario_id']) || ($_SESSION['usuario_tipo'] != 'professor' && $_SESSION['usuario_tipo'] != 'adm')) { header('Location: login.php?erro=2'); exit(); }
require_once 'config/conexao.php';
$id_matricula = isset($_GET['matricula_id']) ? intval($_GET['matricula_id']) : 0;
if ($id_matricula <= 0) { echo "Matrícula inválida."; exit(); }
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $descricao = trim($_POST['descricao']);
    $nota = trim($_POST['nota']);
    if (!empty($descricao) && is_numeric($nota)) {
        $sql_insert = "INSERT INTO notas (id_matricula_fk, descricao_avaliacao, nota) VALUES (:id_matricula, :descricao, :nota)";
        $stmt_insert = $pdo->prepare($sql_insert);
        $stmt_insert->bindParam(':id_matricula', $id_matricula, PDO::PARAM_INT);
        $stmt_insert->bindParam(':descricao', $descricao, PDO::PARAM_STR);
        $stmt_insert->bindParam(':nota', $nota);
        if ($stmt_insert->execute()) { header("Location: lancar_notas.php?matricula_id=$id_matricula"); exit(); }
    }
}
$sql_info = "SELECT a.nome_aluno, t.nome_turma, t.id_turma FROM matriculas m JOIN alunos a ON m.id_aluno_fk = a.id_aluno JOIN turmas t ON m.id_turma_fk = t.id_turma WHERE m.id_matricula = :id_matricula";
$stmt_info = $pdo->prepare($sql_info);
$stmt_info->bindParam(':id_matricula', $id_matricula, PDO::PARAM_INT);
$stmt_info->execute();
$info = $stmt_info->fetch(PDO::FETCH_ASSOC);
if (!$info) { echo "Matrícula não encontrada."; exit(); }
$sql_notas = "SELECT * FROM notas WHERE id_matricula_fk = :id_matricula ORDER BY data_avaliacao DESC";
$stmt_notas = $pdo->prepare($sql_notas);
$stmt_notas->bindParam(':id_matricula', $id_matricula, PDO::PARAM_INT);
$stmt_notas->execute();
$notas = $stmt_notas->fetchAll(PDO::FETCH_ASSOC);
$nome_usuario = $_SESSION['usuario_nome'];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Notas de <?= htmlspecialchars($info['nome_aluno']) ?></title>
    <link rel="stylesheet" href="/projeto_escola/style.css">
</head>
<body>
    <div class="navbar">
        <span><strong>Portal EAD</strong></span>
        <span>Bem-vindo(a), <?= htmlspecialchars($nome_usuario) ?>! | <a href="logout.php">Sair</a></span>
    </div>

    <div class="container">
        <a href="turma_detalhes.php?id=<?= $info['id_turma'] ?>" class="btn-voltar">&larr; Voltar para a Turma</a>
        
        <h2>Notas de: <span style="color: #28a745;"><?= htmlspecialchars($info['nome_aluno']) ?></span></h2>
        <p><strong>Curso:</strong> <?= htmlspecialchars($info['nome_turma']) ?></p>
        
        <hr>

        <h3>Notas Lançadas</h3>
        <?php if (count($notas) > 0): ?>
            <table class="user-list">
                <thead><tr><th>Descrição da Avaliação</th><th>Nota</th><th>Data</th></tr></thead>
                <tbody>
                    <?php foreach ($notas as $nota): ?>
                        <tr>
                            <td><?= htmlspecialchars($nota['descricao_avaliacao']) ?></td>
                            <td><?= htmlspecialchars(number_format($nota['nota'], 2, ',', '.')) ?></td>
                            <td><?= htmlspecialchars(date('d/m/Y', strtotime($nota['data_avaliacao']))) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Nenhuma nota lançada para este aluno nesta turma.</p>
        <?php endif; ?>

        <hr>

        <h3>Lançar Nova Nota</h3>
        <form method="POST" action="lancar_notas.php?matricula_id=<?= $id_matricula ?>">
            <div class="form-row">
                <div class="form-group"><label for="descricao">Descrição da Avaliação</label><input type="text" id="descricao" name="descricao" placeholder="Ex: Prova 1" required></div>
                <div class="form-group"><label for="nota">Nota</label><input type="text" id="nota" name="nota" placeholder="Ex: 8.5" required></div>
                <button type="submit">Salvar Nota</button>
            </div>
        </form>
    </div>
</body>
</html>