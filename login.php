<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - ESCOLA DO WILSON</title>
    <style>
        body { 
            font-family: sans-serif; 
            margin: 0; 
            background-color: #eaf2f8; 
        }
        .main-title {
            text-align: center;
            color: #2c3e50; 
            font-size: 2.8em;
            font-weight: bold;
            margin-top: 60px;
            margin-bottom: 30px;
        }
        .login-container { 
            max-width: 400px; 
            margin: 20px auto; 
            padding: 30px; 
            background-color: white; 
            border-radius: 8px; 
            box-shadow: 0 4px 10px rgba(0,0,0,0.1); 
        }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; }
        input[type="email"], input[type="password"] { width: 100%; padding: 10px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px;}
        button { width: 100%; padding: 12px; background-color: #337ab7; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 1em; }
        button:hover { background-color: #286090; }
        .alert { padding: 15px; margin-bottom: 20px; border: 1px solid transparent; border-radius: 4px; text-align: center; }
        .alert-danger { color: #721c24; background-color: #f8d7da; border-color: #f5c6cb; }
    </style>
</head>
<body>

    <h1 class="main-title">ESCOLA DO WILSON</h1>

    <div class="login-container">
        <h2 style="text-align: center; font-weight: normal; margin-top: 0;">Login</h2>
        
        <?php
        if (isset($_GET['erro'])) {
            $mensagem_erro = ($_GET['erro'] == 1) ? "Email ou senha inválidos." : "Acesso negado. Por favor, faça o login.";
            echo '<div class="alert alert-danger">' . $mensagem_erro . '</div>';
        }
        ?>

        <form action="autenticar.php" method="POST">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="senha">Senha</label>
                <input type="password" id="senha" name="senha" required>
            </div>
            <button type="submit">Entrar</button>
        </form>
    </div>

</body>
</html>