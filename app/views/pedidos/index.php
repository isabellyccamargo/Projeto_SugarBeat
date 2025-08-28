<?php
session_start();

// Verifique novamente se o cliente está logado, por segurança
if (!isset($_SESSION['id_cliente_logado'])) {
    header("Location: ../login/index.php?redirect_to=" . urlencode($_SERVER['REQUEST_URI']));
    exit();
}

// Inclua os arquivos necessários para acessar o carrinho e outros serviços
require_once '../../config/connection.php';
require_once '../../repositories/ProdutoRepository.php';
require_once '../../services/ProdutoService.php';

$conexao = Connection::connect();
$produtoRepository = new ProdutoRepository($conexao);
$produtoService = new ProdutoService($produtoRepository);

// Pega o carrinho da sessão. O carrinho está aqui, intacto!
$carrinho = $produtoService->getCarrinho();
$total_carrinho = $produtoService->getValorTotalCarrinho();
$num_itens = $produtoService->getQuantidadeTotalCarrinho();

// Se o carrinho estiver vazio por algum motivo, redirecione o usuário de volta
if (empty($carrinho)) {
    header("Location: ../carrinho/index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Finalizar Pedido</title>
</head>
<body>
    <h1>Resumo do seu Pedido</h1>
    
    <p>Total de itens: <?php echo $num_itens; ?></p>
    <p>Valor total: R$ <?php echo number_format($total_carrinho, 2, ',', '.'); ?></p>

    <?php foreach ($carrinho as $item): ?>
        <p><?php echo htmlspecialchars($item['nome']); ?> - Quantidade: <?php echo $item['quantidade']; ?></p>
    <?php endforeach; ?>

    <h2>Selecione a forma de pagamento</h2>
    <form action="processar_pagamento.php" method="POST">
        <button type="submit">Concluir Pagamento</button>
    </form>
</body>
</html>