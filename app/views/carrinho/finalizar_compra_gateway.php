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

$verificacao = $produtoService->verificarEstoqueCarrinho();

// Se o cliente estiver logado
if (isset($_SESSION['cliente_id'])) {
    if (!$verificacao['success']) {
        // Estoque insuficiente → volta pro carrinho
        $_SESSION['erro_carrinho'] = $verificacao['message'];
        header("Location: ../carrinho/index.php");
        exit();
    } else {
        // Tudo certo → vai pros pedidos
        header("Location: ../pedidos/index.php");
        exit();
    }
} else {
    // Não logado → redireciona pro login
    header("Location: ../login/?origem=carrinho");
    exit();
}
