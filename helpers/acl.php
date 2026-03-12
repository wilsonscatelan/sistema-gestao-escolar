<?php
require_once __DIR__ . '/auth.php';


function require_role($role) {
if (!is_logged_in() || $_SESSION['user']['tipo'] !== $role) {
http_response_code(403);
echo 'Acesso negado';
exit;
}
}