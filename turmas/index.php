<?php
require_once '../templates/header.php';
require_once '../config/conexao.php';

$sql = "SELECT t.*, p.nome AS nome_professor FROM turmas t LEFT JOIN professores p ON t.id_professor_fk = p.id_professor ORDER BY t.nome_turma ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$turmas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <h2>Turmas</h2>
        <a href="cadastrar.php" class="btn-gerenciar" style="background-color: #28a745;">Adicionar Nova Turma</a>
        <a href="relatorio_turmas.php" class="btn-gerenciar" 
   style="background-color:#007bff; color:white; margin-left:10px;">
    Gerar PDF
</a>

    </div>

    <table class="user-list">
        <thead>
            <tr>
                <th>Nome da Turma</th>
                <th>Ano</th>
                <th>Professor Responsável</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($turmas) > 0) : ?>
                <?php foreach ($turmas as $turma) : ?>
                    <tr>
                        <td>
                            <?php if (!empty($turma['imagem_turma'])) : ?>
                                <img src="../uploads/<?= htmlspecialchars($turma['imagem_turma']) ?>" alt="Imagem da Turma" class="course-thumbnail">
                            <?php else : ?>
                                <img src="https://via.placeholder.com/80x45" alt="Sem Imagem" class="course-thumbnail">
                            <?php endif; ?>
                            <a href="editar.php?id=<?= $turma['id_turma'] ?>" class="user-name">
                                <?= htmlspecialchars($turma['nome_turma']) ?>
                            </a>
                        </td>
                        <td><?= htmlspecialchars($turma['ano']) ?></td>
                        <td><?= htmlspecialchars($turma['nome_professor'] ?? 'Nenhum') ?></td>
                        <td>
                            <a href="editar.php?id=<?= $turma['id_turma'] ?>">Editar</a> |
                            <a href="excluir.php?id=<?= $turma['id_turma'] ?>" onclick="return confirm('Tem certeza?');" style="color: red;">Excluir</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="4">Nenhuma turma cadastrada.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php
require_once '../templates/footer.php';
?>