<?php
if (session_status() === PHP_SESSION_NONE) session_start();


require_once __DIR__ . '/../config.php';
$config = require __DIR__ . '/../config.php';


function is_logged_in() {
return isset($_SESSION['user']);
}


function require_login() {
if (!is_logged_in()) {
header('Location: ' . get_base_url() . '/login.php');
exit;
}
}


function login_user($user_row) {
$_SESSION['user'] = [
'id_usuario' => $user_row['id_usuario'],
'email' => $user_row['email'],
'tipo' => $user_row['tipo_usuario'],
'id_referencia' => $user_row['id_referencia']
];
}


function logout_user() {
session_unset();
session_destroy();
}


function get_base_url() {
$cfg = require __DIR__ . '/../config.php';
return $cfg['base_url'];
}