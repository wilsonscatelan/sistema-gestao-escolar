<?php require_once __DIR__ . '/../../helpers/auth.php'; require_login(); ?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Nova Matrícula</title></head>
<body>
<h2>Nova Matrícula</h2>
<form action="<?= get_base_url() ?>/matriculas/store.php" method="post" enctype="multipart/form-data">
<label>Aluno</label>
<select name="id_aluno" required>
<?php foreach($alunos as $a): ?>
<option value="<?= $a['id_aluno'] ?>"><?= htmlspecialchars($a['nome_aluno']) ?></option>
<?php endforeach; ?>
</select>


<label>Turma</label>
<select name="id_turma" required>
<?php foreach($turmas as $t): ?>
<option value="<?= $t['id_turma'] ?>"><?= htmlspecialchars($t['nome_turma']) ?></option>
<?php endforeach; ?>
</select>


<label>Documento (pdf / imagem) - opcional</label>
<input type="file" name="documento" accept=".pdf,image/*" />


<button type="submit">Salvar Matrícula</button>
</form>
</body>
</html>