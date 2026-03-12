<?php
require_once '../templates/header.php';
?>

<div class="container">
    <h2>Cadastrar Novo Professor</h2>
    <form action="salvar.php" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="nome">Nome do Professor</label>
            <input type="text" class="form-control" id="nome" name="nome" required>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="telefone">Telefone</label>
            <input type="tel" class="form-control" id="telefone" name="telefone" maxlength="11" placeholder="Somente números (Ex: 67999999999)">
        </div>
        <div class="form-group">
            <label for="foto">Foto</label>
            <input type="file" class="form-control-file" id="foto" name="foto_professor" accept="image/png, image/jpeg">
        </div>
        <button type="submit" class="btn btn-success">Salvar</button>
        <a href="index.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>

<?php
require_once '../templates/footer.php';
?>