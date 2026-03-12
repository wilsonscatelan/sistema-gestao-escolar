<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['sucesso'])) {
    echo "<div class='container'><p class='alert alert-success'>" . $_SESSION['sucesso'] . "</p></div>";
    unset($_SESSION['sucesso']); 
}

require_once '../templates/header.php';
require_once '../config/conexao.php';

$sql = "SELECT * FROM professores ORDER BY nome ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$professores = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <h2>Professores</h2>
        <a href="cadastrar.php" class="btn-gerenciar" style="background-color: #28a745;">Adicionar Novo Professor</a>
        <a href="relatorio_professores.php" class="btn-gerenciar" 
   style="background-color:#007bff; color:white; margin-left:10px;">
    Gerar PDF
</a>

    </div>

    <table class="user-list">
        <thead>
            <tr>
                <th>Nome / Sobrenome</th>
                <th>Email</th>
                <th>Telefone</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($professores) > 0) : ?>
                <?php foreach ($professores as $professor) : ?>
                    <tr>
                        <td>
                            <?php if (!empty($professor['foto_professor'])) : ?>
                                <img src="../uploads/<?= htmlspecialchars($professor['foto_professor']) ?>" alt="Foto" class="user-avatar">
                            <?php else : ?>
                                <img src="https://via.placeholder.com/40" alt="Sem Foto" class="user-avatar">
                            <?php endif; ?>
                            <a href="editar.php?id=<?= $professor['id_professor'] ?>" class="user-name">
                                <?= htmlspecialchars($professor['nome']) ?>
                            </a>
                        </td>
                        <td><?= htmlspecialchars($professor['email']) ?></td>
                        <td><?= htmlspecialchars($professor['telefone'] ?? 'N/A') ?></td>
                        <td>
                            <a href="editar.php?id=<?= $professor['id_professor'] ?>">Editar</a> |
                            <a href="excluir.php?id=<?= $professor['id_professor'] ?>" onclick="return confirm('Tem certeza?');" style="color: red;">Excluir</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="4">Nenhum professor cadastrado.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php
require_once '../templates/footer.php';
?>