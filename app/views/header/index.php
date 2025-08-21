<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>SugarBeat</title>
    <link rel="icon" type="image/png" href="../../../fotos/imgsite.jpg">
    <link rel="stylesheet" href="../header/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Ancizar+Serif:ital,wght@0,300..900;1,300..900&family=Bitter:ital,wght@0,100..900;1,100..900&family=Caudex:ital,wght@0,400;0,700;1,400;1,700&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Marcellus&family=Merriweather:ital,opsz,wght@0,18..144,300..900;1,18..144,300..900&family=Noto+Serif:ital,wght@0,100..900;1,100..900&family=Padauk:wght@400;700&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<div class="topo">
    <div class="logo-area">
        <img src="../../../fotos/logo.jpg" alt="Logo da Empresa" class="logo">
        <span class="nome-empresa">SugarBeat</span>
    </div>

    <div class="icons">
        <div class="icon" title="Carrinho">
            <i class="icon fas fa-shopping-cart carrinho-icon"></i>
        </div>

        <div class="icon perfil-menu">
            <div class="icon" id="perfilIcon" title="Perfil">
                <i class="fas fa-user-circle avatar-icon"></i>
            </div>

            <!-- Dropdown escondido -->
            <div class="dropdown" id="perfilDropdown">
                <?php
                // Inicie a sessão se ainda não tiver sido iniciada.
                if (session_status() == PHP_SESSION_NONE) {
                    session_start();
                }

                // Verifique se o usuário está logado (se o id_cliente existe na sessão)
                if (isset($_SESSION['cliente_id'])) {
                    // Se estiver logado, o link "Meus Pedidos" redireciona para a página de meus pedidos com o parâmetro de edição
                    echo '<a href="../pedidos?editar=true">Meus Pedidos</a>';
                } else {
                    // Se não estiver logado, o link redireciona para a página de login
                    echo '<a href="../login">Meus Pedidos</a>';
                }
                ?>

                <?php
                // Inicie a sessão se ainda não tiver sido iniciada.
                if (session_status() == PHP_SESSION_NONE) {
                    session_start();
                }

                // Verifique se o usuário está logado (se o id_cliente existe na sessão)
                if (isset($_SESSION['cliente_id'])) {
                    // Se estiver logado, o link "Meus Dados" redireciona para a página de cadastro com o parâmetro de edição
                    echo '<a href="../cadastro?editar=true">Meus Dados</a>';
                     echo '<a href="../../controllers/longout.php">Sair</a>';
                } else {
                    // Se não estiver logado, o link redireciona para a página de login
                    echo '<a href="../login">Meus Dados</a>';
                }
                ?>
            </div>
        </div>
    </div>
</div>
</div>

<script>
    const perfilIcon = document.getElementById("perfilIcon");
    const perfilDropdown = document.getElementById("perfilDropdown");

    perfilIcon.addEventListener("click", () => {
        perfilDropdown.style.display =
            perfilDropdown.style.display === "block" ? "none" : "block";
    });

    // Fecha o dropdown se clicar fora
    document.addEventListener("click", (event) => {
        if (!perfilIcon.contains(event.target) && !perfilDropdown.contains(event.target)) {
            perfilDropdown.style.display = "none";
        }
    });
</script>