<?php

session_start();

require_once '../../config/connection.php';
require_once '../../repositories/ProdutoRepository.php';
require_once '../../services/ProdutoService.php';
require_once '../../controllers/ProdutoController.php';
require_once '../../models/Produto.php';

$conexao = Connection::connect();
$produtoRepository = new ProdutoRepository($conexao);
$produtoService = new ProdutoService($produtoRepository);
$produtoController = new ProdutoController($produtoService);

$produtos = $produtoController->get();

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>SugarBeat</title>
    <link rel="icon" type="image/png" href="../../../fotos/imgsite.jpg">
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Ancizar+Serif:ital,wght@0,300..900;1,300..900&family=Bitter:ital,wght@0,100..900;1,100..900&family=Caudex:ital,wght@0,400;0,700;1,400;1,700&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Marcellus&family=Merriweather:ital,opsz,wght@0,18..144,300..900;1,18..144,300..900&family=Noto+Serif:ital,wght@0,100..900;1,100..900&family=Padauk:wght@400;700&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>

    <?php

    include '../header/index.php';

    ?>

    <div class="banner">
        <img src="../../../fotos/banner4.jpg" alt="Banner" class="banner-img">
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
                    echo '<button class="adicionar-btn" data-id="' . htmlspecialchars($produto->getIdProduto()) . '">Adicionar</button>';
                    echo '</div>';
                }
            } else {
                echo '<p>Nenhum produto encontrado.</p>';
            }
            ?>
        </div>
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

    <?php
    include '../footer/index.php'
    ?>

</body>

</html>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Scripts do carrinho
        const botoesAdicionar = document.querySelectorAll('div.grid .adicionar-btn');
        const contadorCarrinho = document.querySelector('.carrinho-contador');
        const iconeCarrinho = document.querySelector('.carrinho-icon');

        async function handleAdicionarClick(event) {
            const idProduto = event.target.dataset.id;
            const cardProduto = event.target.closest('.card');
            const imagemProduto = cardProduto.querySelector('img');

            // Animação de arrastar
            const imagemAnimada = imagemProduto.cloneNode(true);
            imagemAnimada.style.position = 'fixed';
            imagemAnimada.style.transition = 'all 1s ease-in-out';
            imagemAnimada.style.width = '100px'; 
            imagemAnimada.style.zIndex = '1000'; 

            const rectBotao = event.target.getBoundingClientRect();
            imagemAnimada.style.left = rectBotao.left + 'px';
            imagemAnimada.style.top = rectBotao.top + 'px';

            document.body.appendChild(imagemAnimada);

            const rectCarrinho = iconeCarrinho.getBoundingClientRect();

            setTimeout(() => {
                imagemAnimada.style.left = (rectCarrinho.left + rectCarrinho.width / 2 - 20) + 'px';
                imagemAnimada.style.top = (rectCarrinho.top + rectCarrinho.height / 2 - 20) + 'px';
                imagemAnimada.style.opacity = '0';
                imagemAnimada.style.transform = 'scale(0.1)';
            }, 100);
            
            setTimeout(() => {
                imagemAnimada.remove();
            }, 1100);

            // Lógica AJAX
            try {
                const response = await fetch('../carrinho/adicionar_pedido.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `id_produto=${idProduto}`
                });

                const result = await response.json();

                if (result.success) {
                    if (contadorCarrinho) {
                        contadorCarrinho.textContent = result.total_items;
                    }
                } else {
                    console.error(result.message);
                }
            } catch (error) {
                console.error('Erro na requisição AJAX:', error);
            }
        }

        botoesAdicionar.forEach(botao => {
            botao.addEventListener('click', handleAdicionarClick);
        });
    });
</script>

