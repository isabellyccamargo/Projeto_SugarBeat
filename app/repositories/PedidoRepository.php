<?php

require_once 'IPedidoRepository.php';

class PedidoRepository implements IPedidoRepository
{

    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getById($id)
    {
        echo "Buscando pedido com ID: $id no banco de dados...\n";
        $stmt = $this->db->prepare("SELECT * FROM pedido WHERE id_pedido = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $pedidodata = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($pedidodata) {
            // Cria um novo objeto Cliente com os dados do banco
            return new Cliente(
                $pedidodata['id_pedido'],
                $pedidodata['id_cliente'],
                $pedidodata['data_pedido'],
                $pedidodata['total'],
                $pedidodata['forma_de_pagamento'],
            );
        }

        return null;
    }

    public function getAll()
    {
        echo "Buscando todos os pedidos no banco de dados...\n";
        $stmt = $this->db->query("SELECT * FROM pedido");
        $pedidoData = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $pedidos = [];

        foreach ($pedidoData as $data) {
            $clientes[] = new Cliente(
                $data['id_pedido'],
                $data['id_cliente'],
                $data['data_pedido'],
                $data['total'],
                $data['forma_de_pagamento'],
            );
        }

        return $clientes;
    }

    public function save($pedido)
    {
        echo "Salvando pedido: " . $pedido->getIdCliente() . " no banco de dados...\n";
        $stmt = $this->db->prepare("INSERT INTO pedido (id_cliente, data_pedido, total, forma_de_pagamento) VALUES (:id_cliente, :data_pedido, :total, :forma_de_pagamento)");

        $stmt->bindValue(':id_cliente', $pedido->getIdCliente());
        $stmt->bindValue(':data_pedido', $pedido->getData());
        $stmt->bindValue(':total', $pedido->getTotal());
        $stmt->bindValue(':forma_de_pagamento', $pedido->getFormaDePagamento());

        $stmt->execute();
        
        $pedido->setIdPedido($this->db->lastInsertId());
        return $pedido;
    }

    public function update($pedido)
    {
        echo "Atualizando pedido com ID: " . $pedido->getIdPedido() . " no banco de dados...\n";
        $stmt = $this->db->prepare("UPDATE pedido SET total = :total, forma_de_pagamento = :forma_de_pagamento WHERE id_pedido = :id");

        $stmt->bindValue(':data_pedido', $pedido->getData());
        $stmt->bindValue(':total', $pedido->getTotal());
        $stmt->bindValue(':forma_de_pagamento', $pedido->getFormaDePagamento());
        return $stmt->execute();
    }
    
}
