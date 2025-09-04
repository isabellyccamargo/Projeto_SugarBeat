<?php

$conexao = Connection::connect();
$produtoRepository = new ProdutoRepository($conexao);
$produtoService = new ProdutoService($produtoRepository);
$produtoController = new ProdutoController($produtoService);

// Chama o método que retorna os produtos e armazena na variável $produtos
$produtos = $produtoController->get();

include '../home/index.php';
?>