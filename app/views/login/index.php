<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="./style.css">
</head>
<body>
    <div class="login-container">
        <div class="logo"></div>
        <form class="login-form" action="login.php" method="POST">
            <div class="input-group">
                <label for="email">email</label>
                <input type="email" id="email" name="email" placeholder=" ">
            </div>
            <div class="input-group">
                <label for="password">senha</label>
                <input type="password" id="password" name="password" placeholder=" ">
            </div>
            <button type="submit" class="access-button">acessar</button>
        </form>
        <div class="signup-link">
            Não existe cadastro? <a href="cadastro.php">clique aqui</a>
        </div>
    </div>
</body>
</html>