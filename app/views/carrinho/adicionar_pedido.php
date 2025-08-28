<?php
session_start();

require_once '../../models/Produto.php';
require_once '../../config/connection.php';
require_once '../../repositories/ProdutoRepository.php';
require_once '../../services/ProdutoService.php';

header('Content-Type: application/json');


$response = ['success' => false, 'message' => '', 'total_items' => 0];

// cliente clicou em adcionar, o id veio do POST
if (isset($_POST['id_produto'])) {
    try {
        $conexao = Connection::connect();
        $produtoRepository = new ProdutoRepository($conexao);
        $produtoService = new ProdutoService($produtoRepository);

        // chama a regra de negocio do  ProdutoService
        $resultado = $produtoService->adicionarAoCarrinho($_POST['id_produto']);

        // se o seriço disser que deu certo entra no if
        if ($resultado['success']) {
            $response['success'] = true;
            $response['message'] = $resultado['message'];
            //Atualiza a quantidade de itens
            $response['total_items'] = count($_SESSION['carrinho'] ?? []);
        } else {
            $response['message'] = $resultado['message'];
        }
    } catch (Exception $e) {
        $response['message'] = 'Erro interno do servidor: ' . $e->getMessage();
    }
} else {
    $response['message'] = 'ID do produto não especificado.';
}

echo json_encode($response);
exit();
