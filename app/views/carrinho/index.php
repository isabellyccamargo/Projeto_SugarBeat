<?php
session_start();

require_once '../../config/connection.php';
require_once '../../models/Produto.php';
require_once '../../repositories/ProdutoRepository.php';
require_once '../../services/ProdutoService.php';

$conexao = Connection::connect();
$produtoRepository = new ProdutoRepository($conexao);
$produtoService = new ProdutoService($produtoRepository);

$carrinho = $produtoService->getCarrinho();
$num_itens = $produtoService->getQuantidadeTotalCarrinho();
$total_carrinho = $produtoService->getValorTotalCarrinho();
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrinho de Compras</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image/png" href="../../../fotos/imgsite.jpg">
    <!-- Adiciona CSS para remover as setas dos inputs de tipo "number" -->
    <style>
        /* Oculta as setas para cima/baixo nos campos de número para navegadores baseados em WebKit (Chrome, Safari) */
        input[type="number"]::-webkit-outer-spin-button,
        input[type="number"]::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        /* Oculta as setas para cima/baixo nos campos de número para o Firefox */
        input[type="number"] {
            -moz-appearance: textfield;
        }
    </style>
</head>

<body>
    <?php include '../header/index.php'; ?>

    <main class="main-content">
        <div class="container">
            <div class="shopping-cart">
                <h1 class="cart-title">Carrinho de Compras</h1>
                <p class="items-count"><?php echo count($carrinho); ?> Itens</p>

                <!-- Seção para os títulos "Unitário" e "Subtotal" -->
                <div class="cart-labels">
                    <p class="label-unitario">Unitário</p>
                    <p class="label-subtotal">Subtotal</p>
                </div>
                <!-- Fim da nova seção -->

                <div class="cart-items">
                    <?php if (!empty($carrinho)) : ?>
                        <?php foreach ($carrinho as $item): ?>
                            <div class="cart-item">
                                <div class="product-details">
                                    <img src="<?php echo htmlspecialchars($item['imagem']); ?>" alt="<?php echo htmlspecialchars($item['nome']); ?>">
                                    <div class="product-info">
                                        <h3 class="product-name"><?php echo htmlspecialchars($item['nome']); ?></h3>
                                        <a href="../carrinho/remover_produto.php?id=<?php echo $item['id']; ?>" class="remove-link">Remover</a>
                                    </div>
                                </div>
                                <div class="item-controls">
                                    <div class="quantity-control">
                                        <button class="quantity-btn" data-action="decrement" data-id="<?php echo $item['id']; ?>">-</button>
                                        <!-- ATENÇÃO: removi o atributo 'readonly' aqui -->
                                        <input type="number" value="<?php echo $item['quantidade']; ?>" class="quantity-input" data-id="<?php echo $item['id']; ?>">
                                        <button class="quantity-btn" data-action="increment" data-id="<?php echo $item['id']; ?>">+</button>
                                    </div>
                                    <p class="item-price" data-id="<?php echo $item['id']; ?>">R$ <?php echo number_format($item['preco'], 2, ',', '.'); ?></p>
                                    <p class="item-total" data-id="<?php echo $item['id']; ?>">R$ <?php echo number_format($item['preco'] * $item['quantidade'], 2, ',', '.'); ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>Seu carrinho está vazio.</p>
                    <?php endif; ?>
                </div>

                <a href="../home/index.php" class="continue-shopping">
                    <i class="fas fa-arrow-left"></i> Continuar Comprando
                </a>
            </div>

            <div class="order-summary">
                <h2 class="summary-title">Resumo do Pedido</h2>
                <div class="summary-details">
                    <p class="summary-items-count"> ITENS : <?php echo count($carrinho); ?></p>
                    <div class="summary-row">
                        <span id="summary-items-count">QUANTIDADE TOTAL : <?php echo $num_itens; ?></span>
                        <span id="summary-total-price">R$ <?php echo number_format($total_carrinho, 2, ',', '.'); ?></span>
                    </div>
    
                    <div class="summary-row total-row">
                        <span>TOTAL</span>
                        <span id="final-total">R$ <?php echo number_format($total_carrinho, 2, ',', '.'); ?></span>
                    </div>
                    <div class="summary-details">
                        <button id="finalizar-pedido-btn" class="checkout-btn">Finalizar Pedido</button>
                        <div id="mensagem-erro-carrinho" style="color: red; margin-top: 20px; display: none; text-align: center;">O carrinho está vazio.</div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php include '../footer/index.php'; ?>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const cartItemsContainer = document.querySelector('.cart-items');
            const summaryItemsCount = document.getElementById('summary-items-count');
            const summaryTotalPrice = document.getElementById('summary-total-price');
            const finalTotal = document.getElementById('final-total');

            // Função para formatar o preço em moeda brasileira
            const formatPrice = (price) => {
                return `R$ ${parseFloat(price).toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
            };

            // Função para enviar a requisição AJAX ao servidor
            const updateServerCart = async (id, newQuantity) => {
                try {
                    const formData = new FormData();
                    formData.append('id_produto', id);
                    formData.append('quantidade', newQuantity);

                    const response = await fetch('atualizar_quantidade.php', {
                        method: 'POST',
                        body: formData
                    });

                    if (!response.ok) {
                        throw new Error('Erro na comunicação com o servidor.');
                    }

                    const data = await response.json();

                    if (data.success) {
                        // Atualiza os totais do resumo com os dados do servidor
                        summaryItemsCount.textContent = `ITENS ${data.total_items}`;
                        summaryTotalPrice.textContent = formatPrice(data.total_price);
                        finalTotal.textContent = formatPrice(data.total_price);
                    } else {
                        console.error('Erro ao atualizar o carrinho:', data.message);
                    }
                } catch (error) {
                    console.error('Falha na requisição:', error);
                }
            };

            // Adiciona um listener de evento para o container de itens
            cartItemsContainer.addEventListener('click', (event) => {
                // A delegação de eventos é mais eficiente que adicionar um listener para cada botão
                const target = event.target;
                const isButton = target.classList.contains('quantity-btn');

                if (isButton) {
                    const cartItem = target.closest('.cart-item');
                    const quantityInput = cartItem.querySelector('.quantity-input');
                    const idProduto = target.dataset.id;
                    let currentQuantity = parseInt(quantityInput.value, 10);

                    if (target.dataset.action === 'increment') {
                        currentQuantity++;
                    } else if (target.dataset.action === 'decrement' && currentQuantity > 1) {
                        currentQuantity--;
                    }

                    // Atualiza o valor do input na interface
                    quantityInput.value = currentQuantity;

                    // Atualiza o subtotal do item localmente para dar um feedback rápido ao usuário
                    const itemTotalElement = cartItem.querySelector('.item-total');
                    const itemPriceElement = cartItem.querySelector('.item-price');
                    const priceText = itemPriceElement.textContent.replace('R$', '').replace('.', '').replace(',', '.');
                    const price = parseFloat(priceText);
                    const newSubtotal = currentQuantity * price;
                    itemTotalElement.textContent = formatPrice(newSubtotal);

                    // Envia a requisição para o servidor
                    updateServerCart(idProduto, currentQuantity);
                }
            });

            cartItemsContainer.addEventListener('input', (event) => {
                // Lida com a mudança manual no input de texto
                const target = event.target;
                if (target.classList.contains('quantity-input')) {
                    const cartItem = target.closest('.cart-item');
                    const idProduto = target.dataset.id;
                    let newQuantity = parseInt(target.value, 10);

                    // Valida a quantidade
                    if (isNaN(newQuantity) || newQuantity < 1) {
                        newQuantity = 1;
                        target.value = 1; // Corrige o valor no input
                    }

                    // Atualiza o subtotal do item localmente
                    const itemTotalElement = cartItem.querySelector('.item-total');
                    const itemPriceElement = cartItem.querySelector('.item-price');
                    const priceText = itemPriceElement.textContent.replace('R$', '').replace('.', '').replace(',', '.');
                    const price = parseFloat(priceText);
                    const newSubtotal = newQuantity * price;
                    itemTotalElement.textContent = formatPrice(newSubtotal);

                    // Envia a requisição para o servidor
                    updateServerCart(idProduto, newQuantity);
                }
            });
        });


        document.addEventListener('DOMContentLoaded', () => {
            // ... (restante do seu código JS) ...

            const finalizarBtn = document.getElementById('finalizar-pedido-btn');
            const mensagemErro = document.getElementById('mensagem-erro-carrinho');

            if (finalizarBtn) {
                finalizarBtn.addEventListener('click', (event) => {
                    // Pega o número de itens do PHP usando o elemento HTML
                    const numItens = parseInt(document.querySelector('.items-count').textContent, 10);

                    if (numItens === 0) {
                        // Se o carrinho estiver vazio, impede o envio do formulário/link
                        event.preventDefault();
                        mensagemErro.style.display = 'block'; // Exibe a mensagem de erro
                    } else {
                        // Se o carrinho tem itens, redireciona o usuário
                        mensagemErro.style.display = 'none'; // Esconde a mensagem de erro, caso esteja visível
                        window.location.href = '../carrinho/finalizar_compra_gateway.php';
                    }
                });
            }
        });
    </script>


</body>

</html>