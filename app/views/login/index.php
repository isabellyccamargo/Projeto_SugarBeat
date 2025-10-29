<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="icon" type="image/png" href="../../../../fotos/imgsite.jpg">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Ancizar+Serif:ital,wght@0,300..900;1,300..900&family=Bitter:ital,wght@0,100..900;1,100..900&family=Caudex:ital,wght@0,400;0,700;1,400;1,700&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Marcellus&family=Merriweather:ital,opsz,wght@0,18..144,300..900;1,18..144,300..900&family=Noto+Serif:ital,wght@0,100..900;1,100..900&family=Padauk:wght@400;700&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>

    <?php

    include '../header/index.php';

    ?>

    <div class="login-container">
        <div class="logo">
            <img src="../../../../fotos/logo.jpg" alt="Logo da Empresa" class="logo">
        </div>
        <h2 class="textoLogin">Login</h2>
        <form class="login-form" action="processa_login.php" method="POST">
            <?php
            // Inicie a sessão se ainda não tiver sido iniciada
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
            // Verifica se a origem foi passada pela URL (ex: ?origem=pedidos)
            if (isset($_GET['origem'])) {
                // Salva a origem em uma variável de sessão
                $_SESSION['redirect_origem'] = $_GET['origem'];
            }
            // Adiciona o campo oculto apenas se a origem for definida
            if (isset($_SESSION['redirect_origem'])): ?>
                <input type="hidden" name="origem" value="<?php echo htmlspecialchars($_SESSION['redirect_origem']); ?>">
            <?php endif; ?>
            <div class="input-group">
                <label for="email">E-mail</label>
                <input type="email" id="email" name="email" placeholder=" " value="<?php echo isset($_GET['email']) ? htmlspecialchars($_GET['email']) : ''; ?>">
            </div>
            <div class="input-group">
                <div class="input-group">
                    <label for="password">Senha</label>
                    <input type="password" id="password" name="password" placeholder=" ">
                    <div class="show-password-container">
                        <input type="checkbox" id="show-password">
                        <label for="show-password" class="show-password-label">Mostrar senha</label>
                    </div>
                </div>
            </div>
            <?php
            // CÓDIGO PARA EXIBIR A MENSAGEM DE ERRO
            if (isset($_GET['erro'])) {
                $mensagem_erro = htmlspecialchars(urldecode($_GET['erro']));
                echo '<div style="color: red; text-align: center; margin-bottom: 15px;">' . $mensagem_erro . '</div>';
            }
            ?>
            <button type="submit" class="access-button">Acessar</button>
        </form>
        <div class="signup-link">
            Não existe cadastro? <a href="../cadastro/index.php">Clique aqui.</a>
        </div>
    </div>
</body>

<?php

include '../footer/index.php';

?>

</html>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const passwordInput = document.getElementById('password');
        const showPasswordCheckbox = document.getElementById('show-password');

        showPasswordCheckbox.addEventListener('change', function() {
            if (showPasswordCheckbox.checked) {
                passwordInput.type = 'text';
            } else {
                passwordInput.type = 'password';
            }
        });
    });
</script>