<?php
require_once '../config/conexao.php';
$sql_turmas = "SELECT id_turma, nome_turma FROM turmas ORDER BY nome_turma ASC";
$stmt_turmas = $pdo->prepare($sql_turmas);
$stmt_turmas->execute();
$turmas = $stmt_turmas->fetchAll(PDO::FETCH_ASSOC);
require_once '../templates/header.php';
?>
<div class="container">
    <h2>Cadastrar Novo Aluno</h2>
    <form action="salvar.php" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="nome_aluno">Nome do Aluno</label>
            <input type="text" id="nome_aluno" name="nome_aluno" required>
        </div>
        <div class="form-group">
            <label for="cpf">CPF</label>
            <input type="text" id="cpf" name="cpf" maxlength="11" placeholder="Apenas números">
        </div>
        <div class="form-group">
            <label for="telefone">Telefone</label>
            <input type="tel" id="telefone" name="telefone" maxlength="11" placeholder="Apenas números (Ex: 67999999999)">
        </div>
        <div class="form-group">
            <label for="data_nascimento">Data de Nascimento</label>
            <input type="date" id="data_nascimento" name="data_nascimento" required>
        </div>
        
        <hr>

        <h4>Matricular nos Cursos</h4>
        <div class="form-group">
            <?php foreach ($turmas as $turma) : ?>
                <div class="form-check">
                    <input type="checkbox" name="cursos[]" value="<?= $turma['id_turma'] ?>">
                    <label><?= htmlspecialchars($turma['nome_turma']) ?></label>
                </div>
            <?php endforeach; ?>
        </div>
        <hr>

        <div class="form-group">
            <label for="foto">Foto do Aluno</label>
            <input type="file" id="foto" name="foto" accept="image/png, image/jpeg">
        </div>
        <button type="submit" class="btn btn-success">Salvar</button>
        <a href="index.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
<?php
require_once '../templates/footer.php';
?>