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
        $pedidoData = filter_input(INPUT_POST, 'pedido', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY) ?? [];
        $itensData = filter_input(INPUT_POST, 'itens', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY) ?? [];

        // O id_cliente agora vem do formulário, que é preenchido pela sessão
        $id_cliente = $pedidoData['id_cliente'] ?? null;
        $forma_de_pagamento = $pedidoData['forma_de_pagamento'] ?? null;
        $descricao_pedido = $pedidoData['descricao_pedido'] ?? null;

        if (empty($id_cliente) || empty($forma_de_pagamento) || empty($itensData)) {
            // Se algum dado crucial estiver faltando, lance uma exceção
            throw new Exception("Dados de pedido incompletos.");
        }

        $pedido = new Pedido(
            null,
            $id_cliente,
            date('Y-m-d H:i:s'), // data e hora atuais
            0, // O total será calculado no serviço
            $forma_de_pagamento,
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
            $novoPedido = $this->pedidoService->criarNovoPedido($pedido, $itens);
            http_response_code(201);
        } catch (Exception $e) {
            throw $e;
            http_response_code(400);
            
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
            $data['forma_de_pagamento'] ?? null
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
