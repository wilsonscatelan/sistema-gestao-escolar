<?php
require_once __DIR__ . '/../helpers/auth.php'; require_login();
require_once __DIR__ . '/../controllers/MatriculaController.php';


$ctrl = new MatriculaController();
$result = $ctrl->store($_POST, $_FILES);


if (isset($result['success']) && $result['success']) {
header('Location: ' . get_base_url() . '/matriculas?msg=created');
exit;
} else {
$err = $result['error'] ?? 'Erro desconhecido';
echo "<p>Erro: " . htmlspecialchars($err) . "</p>";
echo "<p><a href='" . get_base_url() . "/matriculas/create.php'>Voltar</a></p>";
}