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
        $stmt = $this->db->prepare("SELECT pro.id_produto, pro.nome, pro.ativo, pro.preco, pro.imagem, pro.estoque, cat.nome_categoria " .
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
                null,
                $produtoData['nome_categoria'],
                $produtoData['estoque'],
                $produtoData['ativo']
            );
        }
    }

    public function getAll()
    {
        $stmt = $this->db->query("SELECT pro.id_produto, pro.nome, pro.ativo, pro.preco, pro.imagem, pro.estoque, cat.nome_categoria " .
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
                null,
                $data['nome_categoria'],
                $data['estoque'],
                $data['ativo']
            );
        }
        return $produtos;
    }


}
