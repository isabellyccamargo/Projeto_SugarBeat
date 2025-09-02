<?php
// Carrega pedidos via controller
$pedidosJson = file_get_contents("http://localhost/SugarBeat/Projeto_SugarBeat/app/controllers/PedidoController.php");
$pedidos = json_decode($pedidosJson, true);
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Pedidos Realizados</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

<?php include '../header/index.php'; ?>

    <div class="container">
        <h1>Pedidos Realizados</h1>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Data</th>
                    <th>Total</th>
                    <th>Pagamento</th>
                    <th>Descrição</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($pedidos)) : ?>
                    <?php foreach ($pedidos as $pedido): ?>
                        <tr>
                            <td><?= htmlspecialchars($pedido['id_pedido']) ?></td>
                            <td><?= htmlspecialchars($pedido['data_pedido']) ?></td>
                            <td>R$ <?= number_format($pedido['total'], 2, ',', '.') ?></td>
                            <td><?= htmlspecialchars($pedido['forma_de_pagamento']) ?></td>
                            <td><?= htmlspecialchars($pedido['descricao_pedido']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6">-- Nenhum pedido encontrado --</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

     <?php include '../footer/index.php'; ?>
</body>

</html>