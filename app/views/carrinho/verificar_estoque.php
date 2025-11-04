<?php
session_start();

require_once '../../config/connection.php';
require_once '../../models/Produto.php';
require_once '../../repositories/ProdutoRepository.php';
require_once '../../services/ProdutoService.php';

header('Content-Type: application/json');

try {
    $conexao = Connection::connect();
    $produtoRepository = new ProdutoRepository($conexao);
    $produtoService = new ProdutoService($produtoRepository);

    $carrinho = $produtoService->getCarrinho();

    foreach ($carrinho as $item) {
        $produto = $produtoRepository->getById($item['id']);
        if (!$produto) {
            echo json_encode(['success' => false, 'message' => "Produto '{$item['nome']}' não encontrado."]);
            exit;
        }

        if ($item['quantidade'] > $produto->getEstoque()) {
            echo json_encode([
                'success' => false,
                'message' => "Estoque insuficiente para o produto '{$item['nome']}'. Restam apenas {$produto->getEstoque()} unidade(s)."
            ]);
            exit;
        }
    }

    echo json_encode(['success' => true]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Erro: ' . $e->getMessage()]);
}
