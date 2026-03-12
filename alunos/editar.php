<?php
require_once '../templates/header.php';
require_once '../config/conexao.php';

$id_aluno = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id_aluno <= 0) { echo "ID do aluno inválido."; exit(); }

$sql_aluno = "SELECT * FROM alunos WHERE id_aluno = :id";
$stmt_aluno = $pdo->prepare($sql_aluno);
$stmt_aluno->bindParam(':id', $id_aluno, PDO::PARAM_INT);
$stmt_aluno->execute();
$aluno = $stmt_aluno->fetch(PDO::FETCH_ASSOC);
if (!$aluno) { echo "Aluno não encontrado."; exit(); }

$sql_cursos = "SELECT id_turma, nome_turma FROM turmas ORDER BY nome_turma ASC";
$stmt_cursos = $pdo->prepare($sql_cursos);
$stmt_cursos->execute();
$todos_cursos = $stmt_cursos->fetchAll(PDO::FETCH_ASSOC);

$sql_matriculas = "SELECT id_turma_fk FROM matriculas WHERE id_aluno_fk = :id_aluno";
$stmt_matriculas = $pdo->prepare($sql_matriculas);
$stmt_matriculas->bindParam(':id_aluno', $id_aluno, PDO::PARAM_INT);
$stmt_matriculas->execute();
$cursos_do_aluno = $stmt_matriculas->fetchAll(PDO::FETCH_COLUMN, 0);
?>
<div class="container">
    <h2>Gerenciar Aluno</h2>
    <form action="atualizar.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id_aluno" value="<?= htmlspecialchars($aluno['id_aluno']) ?>">
        
        <h4>Dados Pessoais</h4>
        <div class="form-group"><label>Nome:</label><input type="text" name="nome_aluno" value="<?= htmlspecialchars($aluno['nome_aluno']) ?>" required></div>
        <div class="form-group"><label>CPF:</label><input type="text" name="cpf" value="<?= htmlspecialchars($aluno['cpf'] ?? '') ?>" maxlength="11" placeholder="Apenas números"></div>
        <div class="form-group"><label>Telefone:</label><input type="tel" name="telefone" value="<?= htmlspecialchars($aluno['telefone'] ?? '') ?>" maxlength="11" placeholder="Apenas números"></div>
        <div class="form-group"><label>Data de Nascimento:</label><input type="date" name="data_nascimento" value="<?= htmlspecialchars($aluno['data_nascimento']) ?>" required></div>
        
        <hr>

        <h4>Cursos Matriculados</h4>
        <div class="form-group">
            <p>Selecione os cursos para este aluno:</p>
            <?php foreach ($todos_cursos as $curso): ?>
                <div class="form-check">
                    <input type="checkbox" name="cursos[]" value="<?= $curso['id_turma'] ?>" 
                        <?php if (in_array($curso['id_turma'], $cursos_do_aluno)) echo 'checked'; ?>>
                    <label><?= htmlspecialchars($curso['nome_turma']) ?></label>
                </div>
            <?php endforeach; ?>
        </div>

        <hr>
        
        <div class="form-group">
            <label>Foto Atual</label><br>
            <?php if (!empty($aluno['caminho_foto'])) : ?>
                <img src="../uploads/<?= htmlspecialchars($aluno['caminho_foto']) ?>" width="100" style="border-radius: 50%;">
                <input type="hidden" name="foto_antiga" value="<?= htmlspecialchars($aluno['caminho_foto']) ?>">
            <?php endif; ?>
            <br><label for="foto">Trocar Foto</label>
            <input type="file" id="foto" name="foto">
        </div>

        <button type="submit" class="btn btn-success">Salvar Alterações</button>
        <a href="index.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
<?php
require_once '../templates/footer.php';
?>