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
    <link rel="icon" type="image/png" href="../../../../fotos/imgsite.jpg">
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
                        <a href="finalizar_compra_gateway.php" class="checkout-btn" id="finalizar-pedido-btn">Finalizar Pedido</a>
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
            const finalizarBtn = document.getElementById('finalizar-pedido-btn');
            const mensagemErro = document.getElementById('mensagem-erro-carrinho');

            // Função para formatar valores em moeda
            const formatPrice = valor => `R$ ${parseFloat(valor).toLocaleString('pt-BR', { minimumFractionDigits: 2 })}`;

            // Atualiza carrinho no servidor (AJAX)
            async function updateServerCart(id, quantidade) {
                const formData = new FormData();
                formData.append('id_produto', id);
                formData.append('quantidade', quantidade);

                try {
                    const res = await fetch('atualizar_quantidade.php', {
                        method: 'POST',
                        body: formData
                    });
                    const data = await res.json();

                    if (!data.success) {
                        alert(data.message);
                        return location.reload();
                    }

                    summaryItemsCount.textContent = `ITENS ${data.total_items}`;
                    summaryTotalPrice.textContent = finalTotal.textContent = formatPrice(data.total_price);
                } catch {
                    alert('Erro ao atualizar o carrinho.');
                }
            }

            // Atualiza subtotal localmente e envia ao servidor
            function handleQuantityChange(id, input) {
                let qtd = Math.max(1, parseInt(input.value) || 1);
                input.value = qtd;

                const item = input.closest('.cart-item');
                const preco = parseFloat(item.querySelector('.item-price').textContent.replace(/[R$\s.]/g, '').replace(',', '.'));
                item.querySelector('.item-total').textContent = formatPrice(preco * qtd);

                updateServerCart(id, qtd);
            }

            // Incrementa/decrementa quantidades
            cartItemsContainer.addEventListener('click', e => {
                if (!e.target.classList.contains('quantity-btn')) return;

                const input = e.target.closest('.cart-item').querySelector('.quantity-input');
                const id = e.target.dataset.id;
                const action = e.target.dataset.action;

                input.value = action === 'increment' ? +input.value + 1 : Math.max(1, +input.value - 1);
                handleQuantityChange(id, input);
            });

            // Digitação manual na quantidade
            cartItemsContainer.addEventListener('input', e => {
                if (e.target.classList.contains('quantity-input')) {
                    handleQuantityChange(e.target.dataset.id, e.target);
                }
            });

            // Impede finalizar se o carrinho estiver vazio
            finalizarBtn?.addEventListener('click', e => {
                const numItens = parseInt(document.querySelector('.items-count').textContent) || 0;
                if (numItens === 0) {
                    e.preventDefault();
                    mensagemErro.style.display = 'block';
                } else {
                    mensagemErro.style.display = 'none';
                }
            });
        });
    </script>

</body>

</html>