<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verifica se o cliente está logado
if (!isset($_SESSION['cliente_id'])) {
    header('Location: ../login/index.php');
    exit();
}

require_once '../../config/connection.php';
require_once '../../models/Pedido.php';
require_once '../../models/ItemPedido.php';
require_once '../../repositories/PedidoRepository.php';
require_once '../../repositories/ItemPedidoRepository.php';
require_once '../../services/PedidoService.php';
require_once '../../controllers/PedidoController.php';

// Pega o ID do cliente logado
$clienteId = $_SESSION['cliente_id'];

// Instancia as classes
$conexao = Connection::connect();
$pedidoRepository = new PedidoRepository($conexao);
$itemPedidoRepository = new ItemPedidoRepository($conexao);
$pedidoService = new PedidoService($pedidoRepository, $itemPedidoRepository);
$pedidoController = new PedidoController($pedidoService);

// Chama o método do controller para buscar os pedidos do cliente
$pedidos = $pedidoController->getPedidosPorCliente($clienteId);
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedidos Realizados</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image/png" href="../../../fotos/imgsite.jpg"> 
</head>

<body>
    <?php include '../header/index.php'; ?>

    <div class="container">
        <h1>Pedidos Realizados</h1>

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Data</th>
                        <th>Quantidade</th>
                        <th>Total</th>
                        <th>Pagamento</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($pedidos)) : ?>
                        <?php foreach ($pedidos as $pedido): ?>
                            <tr onclick="window.location='detalhe_pedido.php?id=<?= htmlspecialchars($pedido->getIdPedido()) ?>';" style="cursor: pointer;">
                                <td data-label="ID"><?= htmlspecialchars($pedido->getIdPedido()) ?></td>
                                <td data-label="Data"><?= htmlspecialchars($pedido->getData()) ?></td>
                                <td data-label="Quantidade">
                                    <a href="detalhe_pedido.php?id=<?= htmlspecialchars($pedido->getIdPedido()) ?>" class="link-detalhe">Ver Itens</a>
                                </td>
                                <td data-label="Total">R$ <?= number_format($pedido->getTotal(), 2, ',', '.')?></td>
                                <td data-label="Pagamento"><?= htmlspecialchars($pedido->getPreference_id() ?? 'Não Informado') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5">-- Nenhum pedido encontrado --</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php include '../footer/index.php'; ?>
</body>
</html>

<!-- hello dear -->