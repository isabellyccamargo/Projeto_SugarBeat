<?php
session_start();

require_once '../../config/connection.php';
require_once '../../models/Produto.php';
require_once '../../repositories/ProdutoRepository.php';
require_once '../../services/ProdutoService.php';

// verifica se vem o valor id da url
if (isset($_GET['id'])) {
    // força o valor id que veio da url em inteira e guarda em $idProduto (ajuda a evitar erros)
    $idProduto = (int) $_GET['id'];

    $conexao = Connection::connect();
    $produtoRepository = new ProdutoRepository($conexao);
    $produtoService = new ProdutoService($produtoRepository);

    $resultado = $produtoService->removerDoCarrinho($idProduto);

    // Após remover, redireciona de volta para o carrinho
    header("Location: ../carrinho/index.php");
    exit;
} else {
    echo "ID do produto não informado.";
}
