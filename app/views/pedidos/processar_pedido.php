<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Inclua todos os arquivos necessários
require_once '../../config/connection.php';
require_once '../../models/Pedido.php';
require_once '../../models/ItemPedido.php';
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
        // Chame o método post() do controller
        $pedido = $pedidoController->post();

        // Formata o ID do pedido com 5 dígitos, preenchendo com zeros à esquerda
        $pedidoIdFormatado = str_pad($pedido->getIdPedido(), 5, '0', STR_PAD_LEFT);

        // Se a operação for bem-sucedida, configure a mensagem de sucesso na sessão
        $_SESSION['alert_message'] = [
            'type' => 'success',
            'title' => 'Sucesso!',
            'text' => 'Seu pedido foi finalizado com sucesso. Agradecemos a preferência!<br><br>' . '<span style="font-weight:bold; font-size:20px;">#' . $pedidoIdFormatado . '</span>'
        ];

        // Redirecione de volta para a página do carrinho para mostrar a mensagem
        header('Location: ../../views/pedidos/index.php');
        exit();

    } catch (Exception $e) {
        // Em caso de erro, configure a mensagem de erro na sessão
        $_SESSION['alert_message'] = [
            'type' => 'error',
            'title' => 'Erro!',
            'text' => 'Não foi possível processar o seu pedido. Por favor, tente novamente.'
        ];

        // Redirecione de volta para a página do carrinho para mostrar a mensagem
        header('Location: ../../views/pedidos/index.php');
        exit();
    }
} else {
    // Se não for um POST, redirecione para a página do carrinho
    header('Location: ../views/carrinho/index.php');
    exit();
}