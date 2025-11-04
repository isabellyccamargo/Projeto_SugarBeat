<?php

class ProdutoService
{
    private $produtoRepository;

    public function __construct(IProdutoRepository $produtoRepository)
    {
        $this->produtoRepository = $produtoRepository;
    }

    public function getProduto($id)
    {
        return $this->produtoRepository->getById($id);
    }

    public function listarProdutos()
    {
        return $this->produtoRepository->getAll();
    }

    public function adicionarAoCarrinho($idProduto)
    {
        $produto = $this->produtoRepository->getById($idProduto);

        if (!$produto) {
            return ['success' => false, 'message' => 'Produto não encontrado.'];
        }

        if (!isset($_SESSION['carrinho'] )) {
            $_SESSION['carrinho'] = [];
        }

        $encontrado = false;
        foreach ($_SESSION['carrinho'] as $chave => $item) {
            if ($item['id'] == $idProduto) {
                $_SESSION['carrinho'][$chave]['quantidade']++;
                $encontrado = true;
                break;
            }
        }
        if (!$encontrado) {
            $novo_item = [
                'id'        => $produto->getIdProduto(),
                'nome'      => $produto->getNome(),
                'imagem'    => $produto->getImagem(),
                'preco'     => $produto->getPreco(),
                'quantidade' => 1
            ];
            $_SESSION['carrinho'][] = $novo_item;
        }

        return ['success' => true, 'message' => 'Produto adicionado com sucesso!'];
    }

    public function getCarrinho()
    {
        return isset($_SESSION['carrinho']) ? $_SESSION['carrinho'] : [];
    }

    public function getQuantidadeTotalCarrinho()
    {
        $total = 0;
        if (isset($_SESSION['carrinho'])) {
            foreach ($_SESSION['carrinho'] as $item) {
                $total += $item['quantidade'];
            }
        }
        return $total;
    }

    public function getValorTotalCarrinho()
    {
        $total = 0;
        if (isset($_SESSION['carrinho'])) {
            foreach ($_SESSION['carrinho'] as $item) {
                $total += $item['preco'] * $item['quantidade'];
            }
        }
        return $total;
    }

    public function removerDoCarrinho($idProduto)
    {
        if (isset($_SESSION['carrinho'])) {
            foreach ($_SESSION['carrinho'] as $chave => $item) {
                if ($item['id'] == $idProduto) {
                    unset($_SESSION['carrinho'][$chave]);
                    $_SESSION['carrinho'] = array_values($_SESSION['carrinho']); 
                    return ['success' => true, 'message' => 'Produto removido do carrinho.'];
                }
            }
        }
        return ['success' => false, 'message' => 'Produto não encontrado no carrinho.'];
    }

     public function atualizarQuantidadeCarrinho($idProduto, $novaQuantidade)
    {
        if (!isset($_SESSION['carrinho'])) {
            return ['success' => false, 'message' => 'Carrinho não encontrado.'];
        }

        $encontrado = false;
        foreach ($_SESSION['carrinho'] as $chave => $item) {
            if ($item['id'] == $idProduto) {
                $novaQuantidade = max(1, (int)$novaQuantidade);
                $_SESSION['carrinho'][$chave]['quantidade'] = $novaQuantidade;
                $encontrado = true;
                break;
            }
        }

        if (!$encontrado) {
            return ['success' => false, 'message' => 'Produto não encontrado no carrinho.'];
        }

        return ['success' => true, 'message' => 'Quantidade atualizada com sucesso.'];
    }
}
