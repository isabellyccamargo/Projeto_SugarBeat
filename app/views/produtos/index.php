<?php
// Inclua os arquivos de classe
//require_once '../../config/connection.php';
//require_once 'caminho/para/ProdutoService.php';
//require_once 'caminho/para/ProdutoController.php';

// Instancie os objetos
$conexao = Connection::connect();
$produtoRepository = new ProdutoRepository($conexao);
$produtoService = new ProdutoService($produtoRepository);
$produtoController = new ProdutoController($produtoService);

// Chame o método que retorna os produtos e armazene na variável $produtos
$produtos = $produtoController->get();

// Inclua o arquivo HTML que irá usar a variável $produtos
include '../../../index.php';
?>