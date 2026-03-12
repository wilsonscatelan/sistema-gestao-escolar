<?php
require_once '../templates/header.php';
require_once '../config/conexao.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id > 0) {
    $sql_turma = "SELECT * FROM turmas WHERE id_turma = :id";
    $stmt_turma = $pdo->prepare($sql_turma);
    $stmt_turma->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt_turma->execute();
    $turma = $stmt_turma->fetch(PDO::FETCH_ASSOC);
    if (!$turma) { echo "Turma não encontrada."; exit(); }
    
    $sql_professores = "SELECT id_professor, nome FROM professores ORDER BY nome ASC";
    $stmt_professores = $pdo->prepare($sql_professores);
    $stmt_professores->execute();
    $professores = $stmt_professores->fetchAll(PDO::FETCH_ASSOC);
} else {
    echo "ID da turma inválido."; exit();
}
?>

<div class="container">
    <h2>Editar Curso</h2>
    <form action="atualizar.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id_turma" value="<?= htmlspecialchars($turma['id_turma']) ?>">
        
        <div class="form-group">
            <label for="nome_turma">Nome do Curso</label>
            <input type="text" id="nome_turma" name="nome_turma" value="<?= htmlspecialchars($turma['nome_turma']) ?>" required>
        </div>

        <div class="form-group">
            <label for="descricao">Descrição</label>
            <textarea id="descricao" name="descricao" rows="4"><?= htmlspecialchars($turma['descricao'] ?? '') ?></textarea>
        </div>
        
        <div class="form-group">
            <label for="ano">Ano</label>
            <input type="number" id="ano" name="ano" value="<?= htmlspecialchars($turma['ano']) ?>" required>
        </div>

        <div class="form-group">
            <label for="carga_horaria">Carga Horária (em horas)</label>
            <input type="number" id="carga_horaria" name="carga_horaria" value="<?= htmlspecialchars($turma['carga_horaria'] ?? '') ?>">
        </div>
        
        <div class="form-group">
            <label for="id_professor_fk">Professor Responsável</label>
            <select id="id_professor_fk" name="id_professor_fk">
                <option value="">Nenhum</option>
                <?php foreach ($professores as $professor) : ?>
                    <option value="<?= htmlspecialchars($professor['id_professor']) ?>" <?= ($professor['id_professor'] == $turma['id_professor_fk']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($professor['nome']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label>Imagem Atual do Curso</label><br>
            <?php if (!empty($turma['imagem_turma'])) : ?>
                <img src="../uploads/<?= htmlspecialchars($turma['imagem_turma']) ?>" width="150" style="display: block; margin-bottom: 10px;">
                <input type="hidden" name="imagem_anterior" value="<?= htmlspecialchars($turma['imagem_turma']) ?>">
            <?php else : ?>
                <p>Nenhuma imagem cadastrada.</p>
            <?php endif; ?>
            <label for="nova_imagem">Mudar Imagem</label>
            <input type="file" id="nova_imagem" name="nova_imagem" accept="image/png, image/jpeg">
        </div>

        <button type="submit" class="btn btn-success">Salvar Alterações</button>
        <a href="index.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>

<?php
require_once '../templates/footer.php';
?>