<?php

require_once '../../models/ItemPedido.php';

class PedidoController
{
    private $pedidoService;

    public function __construct(PedidoService $pedidoService)
    {
        $this->pedidoService = $pedidoService;
    }

    public function get($id = null)
    {
        if ($id) {
            try {
                return $this->pedidoService->getPedidoComItens($id);
                header('Content-Type: application/json');
                echo json_encode($pedido);
            } catch (Exception $e) {
                http_response_code(404);
                echo json_encode(['error' => $e->getMessage()]);
            }
        } else {
            $pedidos = $this->pedidoService->listarPedidos();
            header('Content-Type: application/json');
            echo json_encode($pedidos);
        }
    }

    public function post()
    {

        $pedidoData = filter_input(INPUT_POST, 'pedido', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY) ?? [];
        $itensData = filter_input(INPUT_POST, 'itens', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY) ?? [];

        $id_cliente = $pedidoData['id_cliente'] ?? null;
        $preference_id = $pedidoData['preference_id'] ?? null;
        $descricao_pedido = $pedidoData['descricao_pedido'] ?? null;

        if (empty($id_cliente) || empty($preference_id) || empty($itensData)) {
            throw new Exception("Dados de pedido incompletos.");
        }

        $pedido = new Pedido(
            null,
            $id_cliente,
            date('Y-m-d H:i:s'),
            0,
            $preference_id,
            $descricao_pedido
        );

        $itens = [];
        foreach ($itensData as $itemData) {
            $itens[] = new ItemPedido(
                null,
                null,
                $itemData['id_produto'] ?? null,
                $itemData['quantidade'] ?? null,
                $itemData['preco_unitario'] ?? null,
                $itemData['sub_total'] ?? null
            );
        }

        try {
            return $this->pedidoService->criarNovoPedido($pedido, $itens);
            http_response_code(201);
        } catch (Exception $e) {
            throw $e;
            http_response_code(400);
            
        }
    }

     public function getPedidosPorCliente($clienteId) {
        return $this->pedidoService->getPedidosPorCliente($clienteId);
    }
}
