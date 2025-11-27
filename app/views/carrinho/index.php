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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
                        <a class="checkout-btn" id="finalizar-pedido-btn">Finalizar Pedido</a>
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
            let qtdAnterior = 0;


            const formatPrice = valor => `R$ ${parseFloat(valor).toLocaleString('pt-BR', { minimumFractionDigits: 2 })}`;


            async function updateServerCart(id, input) {
                const formData = new FormData();
                formData.append('id_produto', id);
                formData.append('quantidade', input.value);

                try {
                    const res = await fetch('atualizar_quantidade.php', {
                        method: 'POST',
                        body: formData
                    });
                    const data = await res.json();

                    const response = {
                        tipo: data.success ? 'success' : 'error',
                        titulo: 'Carrinho',
                        mensagem: data.message
                    };

                    if (!data.success) {
                        mostrarMensagem(response.tipo, response.titulo, response.mensagem);
                        input.value = qtdAnterior;
                        return;
                    }

                    summaryItemsCount.textContent = `ITENS ${data.total_items}`;
                    summaryTotalPrice.textContent = finalTotal.textContent = formatPrice(data.total_price);
                } catch (e) {
                    console.error(e.message);
                    mostrarMensagem('error', 'Erro', 'Não foi possível atualizar o carrinho no servidor.');
                    input.value = qtdAnterior; // Reverte para a quantidade anterior em caso de erro de comunicação
                }
            }


            function handleQuantityChange(id, input) {
                let qtd = Math.max(1, parseInt(input.value) || 1);
                input.value = qtd;

                const item = input.closest('.cart-item');
                // Pega o preço unitário e remove R$, espaços e troca vírgula por ponto
                const precoElement = item.querySelector('.item-price');
                const precoText = precoElement ? precoElement.textContent : 'R$ 0,00';
                const preco = parseFloat(precoText.replace(/[R$\s.]/g, '').replace(',', '.'));

                const itemTotalElement = item.querySelector('.item-total');
                if (itemTotalElement) {
                    itemTotalElement.textContent = formatPrice(preco * qtd);
                }

                updateServerCart(id, input);
            }

            // NOVA FUNÇÃO: Lida com a lógica de checkout de forma independente (sem precisar de evento de clique)
            async function handleCheckout() {
                // Garante que o número de itens seja lido corretamente
                const itemsCountElement = document.querySelector('.items-count');
                const numItensText = itemsCountElement ? itemsCountElement.textContent.trim().split(' ')[0] : '0';
                const numItens = parseInt(numItensText) || 0;

                if (numItens === 0) {
                    mensagemErro.style.display = 'block';
                    return;
                }

                mensagemErro.style.display = 'none';

                try {
                    const res = await fetch('finalizar_compra_gateway.php', {
                        method: 'POST'
                    });

                    const data = await res.json();

                    // 1. Caso de Cliente NÃO LOGADO (O processa.login.php fará o redirect final para pedidos)
                    if (data.redirect && data.redirect.includes('login')) {
                        window.location.href = data.redirect;
                        return;
                    }

                    if (!data.success) {
                        mostrarMensagem('error', 'Erro no Estoque', data.message);
                        return;
                    }

                    // 2. Caso de sucesso e redirecionamento final (cliente logado, estoque ok)
                    if (data.success && data.redirect) {
                        window.location.href = data.redirect;
                        return;
                    }
                } catch (error) {
                    console.error('Erro ao finalizar pedido:', error);
                    mostrarMensagem('error', 'Erro de Comunicação', 'Não foi possível verificar o estoque. Tente novamente.');
                }
            }

            cartItemsContainer.addEventListener('click', e => {
                if (!e.target.classList.contains('quantity-btn')) return;

                const cartItem = e.target.closest('.cart-item');
                if (!cartItem) return;

                const input = cartItem.querySelector('.quantity-input');
                const id = e.target.dataset.id;
                const action = e.target.dataset.action;

                if (!input || !id) return;

                qtdAnterior = input.value;

                input.value = action === 'increment' ? +input.value + 1 : Math.max(1, +input.value - 1);
                handleQuantityChange(id, input);
            });

            cartItemsContainer.addEventListener('input', e => {
                if (e.target.classList.contains('quantity-input')) {
                    handleQuantityChange(e.target.dataset.id, e.target);
                }
            });

            // O Listener do botão agora apenas chama a função handleCheckout
            finalizarBtn?.addEventListener('click', (e) => {
                e.preventDefault();
                handleCheckout();
            });

            cartItemsContainer.addEventListener('focusin', e => {
                if (e.target.classList.contains('quantity-input')) {
                    qtdAnterior = e.target.value;
                }
            });

            // O BLOCO AUTO-CHECKOUT FOI REMOVIDO POIS O processa.login.php AGORA FAZ O REDIRECIONAMENTO DIRETO APÓS O LOGIN.
        });
    </script>

    <script>
        function mostrarMensagem(tipo, titulo, mensagem) {
            const cores = {
                success: '#2f3e1d',
                error: '#a94442',
                warning: '#8a6d3b',
                info: '#31708f'
            };

            Swal.fire({
                icon: tipo,
                title: titulo,
                html: mensagem,
                confirmButtonColor: cores[tipo] || '#2f3e1d',
                background: '#fdfae5',
                color: '#2f3e1d',
                heightAuto: false
            });
        }
    </script>

</body>

</html>