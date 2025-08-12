<?php 

class ProdutoRepository implements IProdutoRepository {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getById($id) {
        echo "Buscando produto com ID: $id\n";
        return new Produto($id, 'Produto X', 10.50, 'imagem_x.jpg');
    }

    public function getAll() {
        echo "Buscando todos os produtos\n";
        return [new Produto(1, 'Produto A'), new Produto(2, 'Produto B')];
    }

    public function save($produto) {
        echo "Salvando produto: " . $produto->getNome() . "\n";
        $produto->setIdProduto(rand(100, 999));
        return $produto;
    }

    public function update($produto) {
        echo "Atualizando produto com ID: " . $produto->getIdProduto() . "\n";
        return true;
    }

    public function delete($id) {
        echo "Deletando produto com ID: $id\n";
        return true;
    }
}