<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Inclua todos os arquivos necessários
require_once '../../config/connection.php';
require_once '../../models/Pedido.php';
require_once '../../models/ItemPedido.php'; // Adicionei este
require_once '../../repositories/PedidoRepository.php';
require_once '../../repositories/ItemPedidoRepository.php';
require_once '../../services/PedidoService.php';
require_once '../../controllers/PedidoController.php';

// Verifique se a requisição é do tipo POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conexao = Connection::connect();
    $pedidoRepository = new PedidoRepository($conexao);
    $itemPedidoRepository = new ItemPedidoRepository($conexao);
    $pedidoService = new PedidoService($pedidoRepository, $itemPedidoRepository);
    $pedidoController = new PedidoController($pedidoService);

    try {
        // Agora, chamamos o método post() do controller
        $pedidoController->post();
        // Redireciona o usuário para uma página de sucesso
        header('Location: ../../views/pedidos/index.php?status=sucesso');
        exit();
    } catch (Exception $e) {
        // Em caso de erro, você pode redirecionar para uma página de erro
        echo "Erro ao salvar o pedido: " . urlencode($e->getMessage());
        // header('Location: ../views/erro/pedido_falhou.php?mensagem=' . urlencode($e->getMessage()));
        // exit();
    }
} else {
    // Se não for um POST, redirecione para a página do carrinho
    header('Location: ../views/carrinho/index.php');
    exit();
}
