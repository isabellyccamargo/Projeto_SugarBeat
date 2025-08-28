<?php
session_start();

require_once '../../config/connection.php';
require_once '../../models/Produto.php'; // Adicione se o modelo for necessário para algo futuro
require_once '../../repositories/ProdutoRepository.php';
require_once '../../services/ProdutoService.php';

header('Content-Type: application/json');

$response = ['success' => false, 'message' => '', 'total_items' => 0, 'total_price' => 0];

// Verifica se os dados necessários foram enviados via POST
if (isset($_POST['id_produto']) && isset($_POST['quantidade'])) {
    try {
        $conexao = Connection::connect();
        $produtoRepository = new ProdutoRepository($conexao);
        $produtoService = new ProdutoService($produtoRepository);

        $idProduto = $_POST['id_produto'];
        $novaQuantidade = $_POST['quantidade'];

        // Chama a nova função do ProdutoService para atualizar a quantidade
        $resultado = $produtoService->atualizarQuantidadeCarrinho($idProduto, $novaQuantidade);

        if ($resultado['success']) {
            $response['success'] = true;
            $response['message'] = $resultado['message'];
            // Recalcula e envia os totais atualizados
            $response['total_items'] = $produtoService->getQuantidadeTotalCarrinho();
            $response['total_price'] = $produtoService->getValorTotalCarrinho();
        } else {
            $response['message'] = $resultado['message'];
        }
    } catch (Exception $e) {
        $response['message'] = 'Erro interno do servidor: ' . $e->getMessage();
    }
} else {
    $response['message'] = 'Dados inválidos recebidos.';
}

echo json_encode($response);
exit();