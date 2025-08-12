<?php

class ItemPedidoRepository implements IItemPedidoRepository
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getById($id)
    {
        echo "Buscando item de pedido com ID: $id no banco de dados...\n";
        $stmt = $this->db->prepare("SELECT * FROM item_pedido WHERE id = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $itemPedidoData = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($itemPedidoData) {
            return new ItemPedido(
                $itemPedidoData['id'],
                $itemPedidoData['id_pedido'],
                $itemPedidoData['id_produto'],
                $itemPedidoData['quantidade'],
                $itemPedidoData['preco_unitario'],
                $itemPedidoData['sub_total']
            );
        }

        return null;
    }

    public function getByPedidoId($pedidoId)
    {
        echo "Buscando itens do pedido com ID: $pedidoId no banco de dados...\n";
        $stmt = $this->db->prepare("SELECT * FROM item_pedido WHERE id_pedido = :pedidoId");
        $stmt->bindValue(':pedidoId', $pedidoId, PDO::PARAM_INT);
        $stmt->execute();
        $itensPedidoData = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $itensPedido = [];

        foreach ($itensPedidoData as $data) {
            $itensPedido[] = new ItemPedido(
                $data['id'],
                $data['id_pedido'],
                $data['id_produto'],
                $data['quantidade'],
                $data['preco_unitario'],
                $data['sub_total']
            );
        }

        return $itensPedido;
    }

    public function save($itemPedido)
    {
        echo "Salvando item de pedido: " . $itemPedido->getId() . " no banco de dados...\n";
        $stmt = $this->db->prepare("INSERT INTO item_pedido (id_pedido, id_produto, quantidade, preco_unitario, sub_total) VALUES (:id_pedido, :id_produto, :quantidade, :preco_unitario, :sub_total)");
        $stmt->bindValue(':id_pedido', $itemPedido->getIdPedido());
        $stmt->bindValue(':id_produto', $itemPedido->getIdProduto());
        $stmt->bindValue(':quantidade', $itemPedido->getQuantidade());
        $stmt->bindValue(':preco_unitario', $itemPedido->getPrecoUnitario());
        $stmt->bindValue(':sub_total', $itemPedido->getSubTotal());
        $stmt->execute();
        $itemPedido->setId($this->db->lastInsertId());
        return $itemPedido;
    }

    public function update($itemPedido)
    {
        echo "Atualizando item de pedido com ID: " . $itemPedido->getId() . " no banco de dados...\n";
        $stmt = $this->db->prepare("UPDATE item_pedido SET id_pedido = :id_pedido, id_produto = :id_produto, quantidade = :quantidade, preco_unitario = :preco_unitario, sub_total = :sub_total WHERE id = :id");
        $stmt->bindValue(':id', $itemPedido->getId(), PDO::PARAM_INT);
        $stmt->bindValue(':id_pedido', $itemPedido->getIdPedido());
        $stmt->bindValue(':id_produto', $itemPedido->getIdProduto());
        $stmt->bindValue(':quantidade', $itemPedido->getQuantidade());
        $stmt->bindValue(':preco_unitario', $itemPedido->getPrecoUnitario());
        $stmt->bindValue(':sub_total', $itemPedido->getSubTotal());
        return $stmt->execute();
    }

    public function delete($id)
    {
        echo "Deletando item de pedido com ID: $id no banco de dados...\n";
        $stmt = $this->db->prepare("DELETE FROM item_pedido WHERE id = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
