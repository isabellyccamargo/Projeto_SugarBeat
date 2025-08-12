<?php

class ProdutoService {
    private $produtoRepository;

    public function __construct(IProdutoRepository $produtoRepository) {
        $this->produtoRepository = $produtoRepository;
    }

    public function criarNovoProduto(Produto $produto) {
        if (empty($produto->getNome()) || $produto->getPreco() <= 0) {
            throw new Exception("Nome e preço válidos são obrigatórios.");
        }
        return $this->produtoRepository->save($produto);
    }

    public function getProduto($id) {
        return $this->produtoRepository->getById($id);
    }

    public function listarProdutos() {
        return $this->produtoRepository->getAll();
    }

    public function atualizarProduto(Produto $produto) {
        return $this->produtoRepository->update($produto);
    }

    public function deletarProduto($id) {
        return $this->produtoRepository->delete($id);
    }
}