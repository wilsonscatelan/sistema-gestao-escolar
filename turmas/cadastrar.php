<?php
require_once '../config/conexao.php';
$sql_professores = "SELECT id_professor, nome FROM professores ORDER BY nome ASC";
$stmt_professores = $pdo->prepare($sql_professores);
$stmt_professores->execute();
$professores = $stmt_professores->fetchAll(PDO::FETCH_ASSOC);
require_once '../templates/header.php';
?>
<div class="container">
    <h2>Cadastrar Novo Curso</h2>
    <form action="salvar.php" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="nome_turma">Nome do Curso</label>
            <input type="text" id="nome_turma" name="nome_turma" required>
        </div>
        <div class="form-group">
            <label for="descricao">Descrição</label>
            <textarea id="descricao" name="descricao" rows="4"></textarea>
        </div>
        <div class="form-group">
            <label for="ano">Ano</label>
            <input type="number" id="ano" name="ano" required>
        </div>
        <div class="form-group">
            <label for="carga_horaria">Carga Horária (em horas)</label>
            <input type="number" id="carga_horaria" name="carga_horaria">
        </div>
        <div class="form-group">
            <label for="id_professor_fk">Professor Responsável</label>
            <select id="id_professor_fk" name="id_professor_fk">
                <option value="">Selecione um professor</option>
                <?php foreach ($professores as $professor) : ?>
                    <option value="<?= htmlspecialchars($professor['id_professor']) ?>">
                        <?= htmlspecialchars($professor['nome']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="imagem_turma">Imagem do Curso</label>
            <input type="file" id="imagem_turma" name="imagem_turma" accept="image/png, image/jpeg">
        </div>
        <button type="submit" class="btn btn-success">Salvar</button>
        <a href="index.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
<?php
require_once '../templates/footer.php';
?>