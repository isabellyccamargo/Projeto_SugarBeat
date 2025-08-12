<?php

class ClienteRepository implements IClienteRepository {
    // A propriedade $db simula a conexão com o banco de dados.
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getById($id) {
        // Lógica de busca no banco de dados
        echo "Buscando cliente com ID: $id\n";
        return new Cliente($id, 'Fulano de Tal', '12345678900', 'fulano@exemplo.com');
    }

    public function getAll() {
        // Lógica de busca de todos os clientes no banco de dados
        echo "Buscando todos os clientes\n";
        return [new Cliente(1, 'Fulano de Tal'), new Cliente(2, 'Ciclano de Oliveira')];
    }

    public function save($cliente) {
        // Lógica de inserção no banco de dados
        echo "Salvando cliente: " . $cliente->getNome() . "\n";
        // Retorna o objeto cliente com o ID gerado pelo banco de dados
        $cliente->setIdCliente(rand(100, 999));
        return $cliente;
    }

    public function update($cliente) {
        // Lógica de atualização no banco de dados
        echo "Atualizando cliente com ID: " . $cliente->getIdCliente() . "\n";
        return true;
    }

    public function delete($id) {
        // Lógica de exclusão no banco de dados
        echo "Deletando cliente com ID: $id\n";
        return true;
    }
}

?>