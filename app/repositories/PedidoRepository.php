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
                $pedidodata['preference_id'],
            );
        }

        return null;
    }

    public function getPedidosPorCliente($id_cliente)
    {
        // echo "Buscando pedidos para o cliente ID: $id_cliente...\n";

        $pedidos = [];

        $stmt = $this->db->prepare("SELECT * FROM pedido WHERE id_cliente = :cliente_id ORDER BY id_pedido DESC");
        $stmt->bindValue(':cliente_id', $id_cliente, PDO::PARAM_INT);
        $stmt->execute();
        $pedidodata = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($pedidodata) {
            foreach ($pedidodata as $data) {
                $pedidos[] = new Pedido(
                    $data['id_pedido'],
                    $data['id_cliente'],
                    $data['data_pedido'],
                    $data['total'],
                    $data['preference_id']
                );
            }
        }

        return $pedidos;
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
                $data['preference_id'],
            );
        }

        return $clientes;
    }

    public function save($pedido)
    {
        echo "Salvando pedido: " . $pedido->getIdCliente() . " no banco de dados...\n";
        $stmt = $this->db->prepare("INSERT INTO pedido (id_cliente, data_pedido, total, preference_id) VALUES (:id_cliente, :data_pedido, :total, :preference_id)");

        $stmt->bindValue(':id_cliente', $pedido->getIdCliente());
        $stmt->bindValue(':data_pedido', $pedido->getData());
        $stmt->bindValue(':total', $pedido->getTotal());
        $stmt->bindValue(':preference_id', $pedido->getPreference_id());

        $stmt->execute();

        $pedido->setIdPedido($this->db->lastInsertId());
        return $pedido;
    }
}
