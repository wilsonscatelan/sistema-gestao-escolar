<?php
require_once __DIR__ . '/../config.php';


class Matricula {
private static function getPDO() {
$cfg = require __DIR__ . '/../config.php';
static $pdo = null;
if ($pdo === null) {
$dsn = $cfg['db']['dsn'];
$user = $cfg['db']['user'];
$pass = $cfg['db']['pass'];
$pdo = new PDO($dsn, $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
}
return $pdo;
}


public static function create($id_aluno, $id_turma, $documento=null) {
$pdo = self::getPDO();
$sql = 'INSERT INTO matriculas (id_aluno_fk, id_turma_fk, documento) VALUES (:a,:t,:d) RETURNING id_matricula';
$stmt = $pdo->prepare($sql);
$stmt->execute([':a'=>$id_aluno,':t'=>$id_turma,':d'=>$documento]);
return $stmt->fetch(PDO::FETCH_COLUMN);
}


public static function allByTurma($id_turma) {
$pdo = self::getPDO();
$sql = 'SELECT m.*, a.nome_aluno FROM matriculas m JOIN alunos a ON a.id_aluno = m.id_aluno_fk WHERE m.id_turma_fk = :t ORDER BY a.nome_aluno';
$stmt = $pdo->prepare($sql);
$stmt->execute([':t'=>$id_turma]);
return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
}