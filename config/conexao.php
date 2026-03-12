<?php

$host = 'localhost';
$port = '5432'; 
$dbname = 'escola_db';
$user = 'postgres'; 
$pass = 'wrx852852'; 


$dsn = "pgsql:host=$host;port=$port;dbname=$dbname";


try {
   
    $pdo = new PDO($dsn, $user, $pass);

   
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro ao conectar com o banco de dados: " . $e->getMessage());
}

?>