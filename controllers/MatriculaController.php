<?php
require_once __DIR__ . '/../models/Matricula.php';
require_once __DIR__ . '/../models/Aluno.php';
require_once __DIR__ . '/../models/Turma.php';
require_once __DIR__ . '/../helpers/upload.php';
require_once __DIR__ . '/../helpers/mail.php';


class MatriculaController {
public function create() {
$turmas = Turma::all();
$alunos = Aluno::all();
include __DIR__ . '/../views/matriculas/create.php';
}


public function store($post, $files) {
$id_aluno = intval($post['id_aluno'] ?? 0);
$id_turma = intval($post['id_turma'] ?? 0);
$documento_path = null;


if (isset($files['documento']) && !empty($files['documento']['name'])) {
$upload = upload_file($files['documento'], 'matriculas');
if (!$upload['success']) {
return ['success'=>false,'error'=>$upload['error']];
}
$documento_path = $upload['path'];
}


$id = Matricula::create($id_aluno, $id_turma, $documento_path);


// opcional: enviar email ao aluno (se tiver email cadastrado)
$pdo = (new ReflectionClass('Matricula'))->getMethod('getPDO')->getClosure(null);


// tentamos pegar email do aluno (se existir)
try {
$cfg = require __DIR__ . '/../config.php';
$db = new PDO($cfg['db']['dsn'], $cfg['db']['user'], $cfg['db']['pass']);
$stmt = $db->prepare('SELECT email, nome_aluno FROM alunos WHERE id_aluno = :id LIMIT 1');
$stmt->execute([':id'=>$id_aluno]);
$al = $stmt->fetch(PDO::FETCH_ASSOC);
if ($al && !empty($al['email'])) {
$sub = 'Confirmação de Matrícula';
$body = "Olá {$al['nome_aluno']},<br/>Sua matrícula foi registrada.";
send_mail($al['email'], $sub, $body);
}
} catch (Exception $e) {
// não bloquear processo por falha de email
}


return ['success'=>true,'id'=>$id];
}
}