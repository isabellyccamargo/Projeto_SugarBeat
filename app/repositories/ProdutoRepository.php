<?php

class ProdutoRepository implements IProdutoRepository
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getById($id)
    {
        echo "Buscando produto com ID: $id no banco de dados...\n";
        $stmt = $this->db->prepare("SELECT * FROM produto WHERE id_produto = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $produtoData = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($produtoData) {
            return new Produto(
                $produtoData['id_produto'],
                $produtoData['nome'],
                $produtoData['preco'],
                $produtoData['imagem']
            );
        }
    }

    public function getAll()
    {
        echo "Buscando todos os produtos no banco de dados...\n";
        $stmt = $this->db->query("SELECT * FROM produto");
        $produtosData = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $produtos = [];

        foreach ($produtosData as $data) {
            $produtos[] = new Produto(
                $data['id_produto'],
                $data['nome'],
                $data['preco'],
                $data['imagem']
            );
        }
    }

    public function save($produto)
    {
        echo "Salvando produto: " . $produto->getNome() . " no banco de dados...\n";
        $stmt = $this->db->prepare("INSERT INTO produto (nome, preco, imagem) VALUES (:nome, :preco, :imagem)");
        $stmt->bindValue(':nome', $produto->getNome());
        $stmt->bindValue(':preco', $produto->getPreco());
        $stmt->bindValue(':imagem', $produto->getImagem());
        $stmt->execute();
        $produto->setIdProduto($this->db->lastInsertId());
        return $produto;
    }

    public function update($produto)
    {
        echo "Atualizando produto com ID: " . $produto->getIdProduto() . " no banco de dados...\n";
        $stmt = $this->db->prepare("UPDATE produto SET nome = :nome, preco = :preco, imagem = :imagem WHERE id_produto = :id");
        $stmt->bindValue(':id', $produto->getIdProduto(), PDO::PARAM_INT);
        $stmt->bindValue(':nome', $produto->getNome());
        $stmt->bindValue(':preco', $produto->getPreco());
        $stmt->bindValue(':imagem', $produto->getImagem());
        return $stmt->execute();
    }

    public function delete($id)
    {
        echo "Deletando produto com ID: $id no banco de dados...\n";
        $stmt = $this->db->prepare("DELETE FROM produto WHERE id_produto = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
