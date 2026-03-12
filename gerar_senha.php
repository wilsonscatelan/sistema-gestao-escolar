<?php
$senha_para_criptografar = '123456';
$hash_gerado = password_hash($senha_para_criptografar, PASSWORD_DEFAULT);

echo "<h1>Use este código no seu pgAdmin4</h1>";
echo "<p>O hash gerado para a senha '{$senha_para_criptografar}' é:</p>";
echo "<pre style='background-color: #eee; padding: 10px; border: 1px solid #ccc;'>{$hash_gerado}</pre>";

echo "<p>Execute o seguinte comando SQL na sua Query Tool:</p>";
echo "<pre style='background-color: #d4edda; padding: 10px; border: 1px solid #c3e6cb;'>";
echo "UPDATE usuarios SET senha = '{$hash_gerado}' WHERE email = 'adm@escola.com';";
echo "</pre>";
?>