<?php

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
                $pedido = $this->pedidoService->getPedidoComItens($id);
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

        // Assumindo que a requisição POST envia os dados do pedido e os itens
        $pedidoData = $_POST['pedido'] ?? [];
        $itensData = $_POST['itens'] ?? [];

        //echo "<pre>";
        print_r('Dados do Pedido:' .  $pedidoData->getIdCliente());
        //echo "</pre>";

        $pedido = new Pedido(
            null,
            $pedidoData['id_cliente'] ?? null,
            $pedidoData['data_pedido'] ?? null,
            0, // O total será calculado no serviço
            $pedidoData['forma_de_pagamento'] ?? null,
            $pedidoData['descricao_pedido'] ?? null
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
            $novoPedido = $this->pedidoService->criarNovoPedido($pedido, $itens);
            http_response_code(201);
            echo json_encode($novoPedido);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function put($id)
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $pedido = new Pedido(
            $id,
            $data['id_cliente'] ?? null,
            $data['data_pedido'] ?? null,
            $data['total'] ?? null,
            $data['forma_de_pagamento'] ?? null,
            $data['descricao_pedido'] ?? null
        );
        try {
            $this->pedidoService->atualizarPedido($pedido);
            http_response_code(200);
            echo json_encode(['message' => 'Pedido atualizado com sucesso.']);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
}
