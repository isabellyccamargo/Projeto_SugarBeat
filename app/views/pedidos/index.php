<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once '../../config/connection.php';
require_once '../../models/Produto.php';
require_once '../../repositories/ProdutoRepository.php';
require_once '../../services/ProdutoService.php';

$conexao = Connection::connect();
$produtoRepository = new ProdutoRepository($conexao);
$produtoService = new ProdutoService($produtoRepository);

// Use as variáveis preenchidas pelo ProdutoService
$carrinho = $produtoService->getCarrinho();
$num_itens = $produtoService->getQuantidadeTotalCarrinho();
$total_carrinho = $produtoService->getValorTotalCarrinho();

$itens_detalhes = [];

// Se o carrinho não estiver vazio, percorra os itens para obter os detalhes
if (!empty($carrinho)) {
    foreach ($carrinho as $id_produto => $item) {
        try {
            // A sua classe ProdutoService está retornando um array de itens
            // Então, em vez de buscar o produto novamente, use o que já está no array $item
            $itens_detalhes[] = [
                'produto' => new Produto($item['id'], $item['nome'], $item['preco'], $item['imagem']),
                'quantidade' => $item['quantidade'],
                'subtotal' => $item['preco'] * $item['quantidade'],
            ];
        } catch (Exception $e) {
            error_log("Erro ao buscar produto no carrinho: " . $e->getMessage());
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finalizar Pedido</title>
    <link rel="icon" type="image/png" href="../../../fotos/imgsite.jpg">
    <link rel="stylesheet" href="../pedidos/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Ancizar+Serif:ital,wght@0,300..900;1,300..900&family=Bitter:ital,wght@0,100..900;1,100..900&family=Caudex:ital,wght@0,400;0,700;1,400;1,700&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Marcellus&family=Merriweather:ital,opsz,wght@0,18..144,300..900;1,18..144,300..900&family=Noto+Serif:ital,wght@0,100..900;1,100..900&family=Padauk:wght@400;700&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>

    <?php include '../header/index.php'; ?>

    <div class="window-container conteudo-principal">
        <div class="window-header">
            <div class="window-control close"></div>
            <div class="window-control minimize"></div>
            <div class="window-control maximize"></div>
        </div>
        <div class="window-body">
            <h1 class="titulo">Finalizar Pedido</h1>

            <div class="finalizar-container">
                <div class="pedido-info">
                    <h2>Detalhes do Pedido</h2>
                    <hr>
                    <?php if (!empty($itens_detalhes)): ?>
                        <?php foreach ($itens_detalhes as $item): ?>
                            <div class="item-resumo">
                                <div>
                                    <span class="item-nome"><?php echo htmlspecialchars($item['produto']->getNome()); ?></span>
                                    <span class="item-quantidade">x<?php echo $item['quantidade']; ?></span>
                                </div>
                                <span class="item-preco">R$ <?php echo number_format($item['subtotal'], 2, ',', '.'); ?></span>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>Seu carrinho está vazio.</p>
                    <?php endif; ?>

                    <div class="resumo-total">
                        <p>Quantidade de Itens: <span><?php echo $num_itens; ?></span></p>
                        <p>Total a Pagar: <span class="total-valor">R$ <?php echo number_format($total_carrinho, 2, ',', '.'); ?></span></p>
                    </div>
                </div>

                <div class="pagamento-info">
                    <h2 class="forma">Forma de Pagamento</h2>
                    <hr>
                    <p class="selecione">Selecione uma opção de pagamento:</p>
                    <div class="opcoes-pagamento">
                        <div class="opcao">
                            <input type="radio" id="pix" name="metodo_pagamento" value="pix" checked>
                            <label for="pix">Pix</label>
                            <i class="fab fa-pix"></i>
                        </div>
                        <div class="opcao">
                            <input type="radio" id="cartao" name="metodo_pagamento" value="cartao">
                            <label for="cartao">Cartão de Crédito</label>
                            <i class="fas fa-credit-card"></i>
                        </div>
                        <div class="opcao">
                            <input type="radio" id="boleto" name="metodo_pagamento" value="boleto">
                            <label for="boleto">Boleto</label>
                            <i class="fas fa-barcode"></i>
                        </div>
                    </div>
                    <hr>
                    <button class="btn-finalizar">Confirmar Pagamento</button>
                </div>
            </div>
        </div>
    </div>

    <?php include '../footer/index.php'; ?>
    <script src="script.js"></script>

</body>

</html>