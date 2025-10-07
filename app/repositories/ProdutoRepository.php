<?php

require_once 'IProdutoRepository.php';

class ProdutoRepository implements IProdutoRepository
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getById($id)
    {
        $stmt = $this->db->prepare("SELECT pro.id_produto, pro.nome, pro.preco, pro.imagem, cat.nome_categoria " .
                                 "  FROM produto pro " .
                                 " INNER JOIN categoria cat on cat.id_categoria = pro.id_categoria WHERE pro.id_produto = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $produtoData = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($produtoData) {
            return new Produto(
                $produtoData['id_produto'],
                $produtoData['nome'],
                $produtoData['preco'],
                $produtoData['imagem'],
                $produtoData['nome_categoria']
            );
        }
    }

    public function getAll()
    {
        $stmt = $this->db->query("SELECT pro.id_produto, pro.nome, pro.preco, pro.imagem, cat.nome_categoria " .
                                 "  FROM produto pro " .
                                 " INNER JOIN categoria cat on cat.id_categoria = pro.id_categoria;");
        $produtosData = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $produtos = [];

        foreach ($produtosData as $data) {
            $produtos[] = new Produto(
                $data['id_produto'],
                $data['nome'],
                $data['preco'],
                $data['imagem'],
                $data['nome_categoria']
            );
        }
        return $produtos;
    }

    public function save($produto)
    {
        echo "Salvando produto: " . $produto->getNome() . " no banco de dados...\n";
        $stmt = $this->db->prepare("INSERT INTO produto (nome, preco, imagem, id_categoria) VALUES (:nome, :preco, :imagem, :id_categoria)");
        $stmt->bindValue(':nome', $produto->getNome());
        $stmt->bindValue(':preco', $produto->getPreco());
        $stmt->bindValue(':imagem', $produto->getImagem());
        $stmt->bindValue(':id_categoria', $produto->getIdCategoria());
        $stmt->execute();
        $produto->setIdProduto($this->db->lastInsertId());
        return $produto;
    }

    public function update($produto)
    {
        echo "Atualizando produto com ID: " . $produto->getIdProduto() . " no banco de dados...\n";
        $stmt = $this->db->prepare("UPDATE produto SET nome = :nome, preco = :preco, imagem = :imagem, id_categoria = :id_categoria  WHERE id_produto = :id");
        $stmt->bindValue(':id', $produto->getIdProduto(), PDO::PARAM_INT);
        $stmt->bindValue(':nome', $produto->getNome());
        $stmt->bindValue(':preco', $produto->getPreco());
        $stmt->bindValue(':imagem', $produto->getImagem());
        $stmt->bindValue(':id_categoria', $produto->getIdCategoria());
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
