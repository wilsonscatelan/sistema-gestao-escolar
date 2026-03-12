<?php
require_once '../templates/header.php';
require_once '../config/conexao.php';

if (session_status() === PHP_SESSION_NONE) { session_start(); }
if (isset($_SESSION['sucesso'])) { echo "<div class='container'><p class='alert alert-success'>" . $_SESSION['sucesso'] . "</p></div>"; unset($_SESSION['sucesso']); }

$sql = "SELECT
            a.id_aluno, a.nome_aluno, a.data_nascimento, a.caminho_foto, a.cpf, a.telefone,
            u.email AS email_aluno,
            string_agg(t.nome_turma, ', ') AS cursos
        FROM alunos a
        LEFT JOIN matriculas m ON a.id_aluno = m.id_aluno_fk
        LEFT JOIN turmas t ON m.id_turma_fk = t.id_turma
        LEFT JOIN usuarios u ON a.id_aluno = u.id_referencia AND u.tipo_usuario = 'aluno'
        GROUP BY a.id_aluno, u.email
        ORDER BY a.nome_aluno ASC";

$stmt = $pdo->prepare($sql);
$stmt->execute();
$alunos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <h2>Alunos</h2>
        <a href="cadastrar.php" class="btn-gerenciar" style="background-color: #28a745;">Adicionar Novo Aluno</a>
        <a href="relatorio_alunos.php" class="btn-gerenciar" style="background-color:#007bff; color:white;">
    Gerar Relatório PDF
</a>

    </div>

    <table class="user-list">
        <thead>
            <tr>
                <th>Nome do Aluno</th>
                <th>Email</th>
                <th>CPF</th>
                <th>Telefone</th>
                <th>Cursos Matriculados</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($alunos && count($alunos) > 0) : ?>
                <?php foreach ($alunos as $aluno) : ?>
                    <tr>
                        <td>
                            <?php if (!empty($aluno['caminho_foto'])) : ?>
                                <img src="../uploads/<?= htmlspecialchars($aluno['caminho_foto']) ?>" alt="Foto" class="user-avatar">
                            <?php else : ?>
                                <img src="https://via.placeholder.com/40" alt="Sem Foto" class="user-avatar">
                            <?php endif; ?>
                            <a href="editar.php?id=<?= $aluno['id_aluno'] ?>" class="user-name">
                                <?= htmlspecialchars($aluno['nome_aluno']) ?>
                            </a>
                        </td>
                        <td><?= htmlspecialchars($aluno['email_aluno'] ?? 'N/A') ?></td>
                        <td><?= htmlspecialchars($aluno['cpf'] ?? 'N/A') ?></td>
                        <td><?= htmlspecialchars($aluno['telefone'] ?? 'N/A') ?></td>
                        <td><?= htmlspecialchars($aluno['cursos'] ?? 'Nenhum curso') ?></td>
                        <td>
                            <a href="editar.php?id=<?= $aluno['id_aluno'] ?>">Editar</a> |
                            <a href="excluir.php?id=<?= $aluno['id_aluno'] ?>" onclick="return confirm('Tem certeza?');" style="color: red;">Excluir</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="6">Nenhum aluno cadastrado.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php
require_once '../templates/footer.php';
?>