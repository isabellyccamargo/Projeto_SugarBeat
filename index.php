<?php
// Inclua os arquivos de classe
require_once 'app/config/connection.php';
require_once 'app/repositories/ProdutoRepository.php';
require_once 'app/services/ProdutoService.php';
require_once 'app/controllers/ProdutoController.php';
require_once 'app/models/Produto.php'; 

// Instancie os objetos
$conexao = Connection::connect();
$produtoRepository = new ProdutoRepository($conexao);
$produtoService = new ProdutoService($produtoRepository);
$produtoController = new ProdutoController($produtoService);

// Chame o método que retorna os produtos e armazene na variável $produtos
$produtos = $produtoController->get();

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>SugarBeat</title>
    <link rel="icon" type="image/png" href="fotos/imgsite.jpg">
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Ancizar+Serif:ital,wght@0,300..900;1,300..900&family=Bitter:ital,wght@0,100..900;1,100..900&family=Caudex:ital,wght@0,400;0,700;1,400;1,700&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Marcellus&family=Merriweather:ital,opsz,wght@0,18..144,300..900;1,18..144,300..900&family=Noto+Serif:ital,wght@0,100..900;1,100..900&family=Padauk:wght@400;700&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>

    <div class="topo">
        <div class="logo-area">
            <img src="fotos/logo.jpg" alt="Logo da Empresa" class="logo">
            <span class="nome-empresa">SugarBeat</span>
        </div>

        <div class="icons">
            <div class="icon" title="Carrinho">
                <i class="icon fas fa-shopping-cart carrinho-icon"></i>
            </div>

            <div class="icon" title="Histórico de Compras">
                <i class="fas fa-bag-shopping historico-icon"></i>
            </div>

            <div class="icon" title="Perfil">
                <i class="icon fas fa-user-circle avatar-icon"></i>
            </div>
        </div>
    </div>

    <div class="banner">
        <img src="fotos/banner4.jpg" alt="Banner" class="banner-img">
    </div>

    <div class="tabela-precos">
        <h2 class="titulo-precos">Tabela de Preços</h2>
        <div class="precos-grid">
            <div class="preco-item">
                <h3>Unidade</h3>
                <p>R$ 1,30</p>
            </div>
            <div class="preco-item">
                <h3>Meio Cento</h3>
                <p>R$ 65,00</p>
            </div>
            <div class="preco-item">
                <h3>Um Cento</h3>
                <p>R$130,00</p>
            </div>
        </div>
    </div>

    <div class="produtos">
        <h2 class="sabores">Nossos Sabores</h2>
        <div class="grid">
            <?php
            // A variável $produtos já foi carregada pelo PHP no início do arquivo
            if (isset($produtos) && is_array($produtos)) {
                foreach ($produtos as $produto) {
                    echo '<div class="card">';
                    echo '<img src="' . htmlspecialchars($produto->getImagem()) . '" alt="' . htmlspecialchars($produto->getNome()) . '">';
                    echo '<p>' . htmlspecialchars($produto->getNome()) . '</p>';
                    echo '<form method="post" action="adicionar_pedido.php">';
                    echo '<input type="hidden" name="produto" value="' . htmlspecialchars($produto->getIdProduto()) . '">';
                    echo '<button type="submit">Adicionar</button>';
                    echo '</form>';
                    echo '</div>';
                }
            } else {
                echo '<p>Nenhum produto encontrado.</p>';
            }
            ?>

</body>

</html>