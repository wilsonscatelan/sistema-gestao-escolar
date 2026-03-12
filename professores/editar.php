<?php
require_once '../templates/header.php';
require_once '../config/conexao.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id > 0) {
    $sql = "SELECT * FROM professores WHERE id_professor = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $professor = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$professor) {
        echo "<p class='alert alert-danger'>Professor não encontrado.</p>";
        exit();
    }
} else {
    echo "<p class='alert alert-danger'>ID do professor inválido.</p>";
    exit();
}
?>

<div class="container">
    <h2>Editar Professor</h2>
    <form action="atualizar.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id_professor" value="<?= htmlspecialchars($professor['id_professor']) ?>">
        
        <div class="form-group">
            <label for="nome">Nome do Professor</label>
            <input type="text" class="form-control" id="nome" name="nome" value="<?= htmlspecialchars($professor['nome']) ?>" required>
        </div>
        
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($professor['email']) ?>" required>
        </div>

        <div class="form-group">
            <label for="telefone">Telefone</label>
            <input type="tel" class="form-control" id="telefone" name="telefone" value="<?= htmlspecialchars($professor['telefone'] ?? '') ?>" maxlength="11" placeholder="Somente números (Ex: 67999999999)">
        </div>

        <div class="form-group">
            <label>Foto Atual</label><br>
            <?php if (!empty($professor['foto_professor'])) : ?>
                <img src="../uploads/<?= htmlspecialchars($professor['foto_professor']) ?>" alt="Foto Atual" width="100" height="100" style="border-radius: 50%; object-fit: cover; display: block; margin-bottom: 10px;">
                <input type="hidden" name="foto_anterior" value="<?= htmlspecialchars($professor['foto_professor']) ?>">
            <?php else : ?>
                <p>Nenhuma foto atual.</p>
            <?php endif; ?>
            <label for="nova_foto">Mudar Foto</label>
            <input type="file" class="form-control-file" id="nova_foto" name="nova_foto" accept="image/png, image/jpeg">
        </div>

        <button type="submit" class="btn btn-success">Salvar Alterações</button>
        <a href="index.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>

<?php
require_once '../templates/footer.php';
?>