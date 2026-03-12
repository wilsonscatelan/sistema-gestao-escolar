<?php
require_once __DIR__ . '/../config.php';
$config = require __DIR__ . '/../config.php';


function ensure_dir($dir) {
if (!is_dir($dir)) mkdir($dir, 0775, true);
}


function sanitize_filename($name) {
$name = preg_replace('/[^A-Za-z0-9_\.-]/', '_', $name);
return $name;
}


function upload_file($file, $subdir='generic') {
$cfg = require __DIR__ . '/../config.php';
$base = rtrim($cfg['upload_dir'], '/\\') . '/' . trim($subdir, '/\\');
ensure_dir($base);


if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
return ['success'=>false,'error'=>'Erro no upload ou arquivo não enviado'];
}


$allowed = [
'image/jpeg','image/png','image/gif','application/pdf'
];


if (!in_array($file['type'], $allowed)) {
return ['success'=>false,'error'=>'Tipo de arquivo não permitido'];
}


if ($file['size'] > 5 * 1024 * 1024) {
return ['success'=>false,'error'=>'Arquivo excede 5MB'];
}


$ext = pathinfo($file['name'], PATHINFO_EXTENSION);
$name = uniqid() . '-' . sanitize_filename(basename($file['name']));
$target = $base . '/' . $name;


if (move_uploaded_file($file['tmp_name'], $target)) {
// caminho relativo para gravar no DB
$rel = 'Uploads/' . trim($subdir, '/\\') . '/' . $name;
return ['success'=>true,'path'=>$rel];
}


return ['success'=>false,'error'=>'Falha ao mover arquivo'];
}