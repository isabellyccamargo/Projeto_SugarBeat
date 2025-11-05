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

    public function adicionarAoCarrinho($idProduto)
    {
        $produto = $this->produtoRepository->getById($idProduto);
        if (!$produto) return ['success' => false, 'message' => 'Produto não encontrado.'];

        $estoque = $produto->getEstoque();

        if (!isset($_SESSION['carrinho'])) $_SESSION['carrinho'] = [];

        foreach ($_SESSION['carrinho'] as $chave => $item) {
            //verifica se o produto já está no carrinho
            if ($item['id'] == $idProduto) {
                $novaQuantidade = $item['quantidade'] + 1;

                if ($novaQuantidade > $estoque) {
                    return [
                        'success' => false, 'message' => 'Estoque insuficiente para adicionar mais unidades.',
                    ];
                    
                }

                $_SESSION['carrinho'][$chave]['quantidade'] = $novaQuantidade;
                return ['success' => true, 'message' => 'Quantidade atualizada.'];
            }
        }

        $_SESSION['carrinho'][] = [
            'id' => $produto->getIdProduto(),
            'nome' => $produto->getNome(),
            'imagem' => $produto->getImagem(),
            'preco' => $produto->getPreco(),
            'quantidade' => 1
        ];

        return ['success' => true, 'message' => 'Produto adicionado com sucesso!'];
    }

    public function atualizarQuantidadeCarrinho($idProduto, $novaQuantidade)
    {
        $produto = $this->produtoRepository->getById($idProduto);
        if (!$produto) {
            return ['success' => false, 'message' => 'Produto não encontrado.'];
        }

        $estoque = $produto->getEstoque();
        foreach ($_SESSION['carrinho'] as $chave => $item) {
            //verifica se o produto já está no carrinho
            if ($item['id'] == $idProduto) {
                $novaQuantidade = max(1, (int)$novaQuantidade);

                // veririfca se o cliente não esta tentando ultrapassar o estoque
                if ($novaQuantidade > $estoque) {
                    return [
                        'success' => false,
                        'message' => "Estoque insuficiente para o produto '{$produto->getNome()}'. Restam apenas {$estoque} unidade(s)."
                    ];
                }

                // se não estiver, adciona nova quantidade
                $_SESSION['carrinho'][$chave]['quantidade'] = $novaQuantidade;
                $encontrado = true;
                break;
            }
        }

        return ['success' => true, 'message' => 'Quantidade atualizada com sucesso.'];
    }

    public function verificarEstoqueCarrinho()
    {
        // percorre os produtos do carrinho 
        foreach ($_SESSION['carrinho'] as $item) {
            $produto = $this->produtoRepository->getById($item['id']);

            if (!$produto) {
                return ['success' => false, 'message' => "Produto '{$item['nome']}' não encontrado."];
            }

            // estoque mais recente
            $estoqueAtual = $produto->getEstoque();
            if ($item['quantidade'] > $estoqueAtual) {
                return [
                    'success' => false,
                    'message' => "Estoque insuficiente para o produto '{$item['nome']}'. Disponível: {$estoqueAtual} unidade(s)."
                ];
            }
        }
        return ['success' => true];
    }
}
