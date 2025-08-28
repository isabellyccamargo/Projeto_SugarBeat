<?php

class ProdutoService
{
    private $produtoRepository;

    public function __construct(IProdutoRepository $produtoRepository)
    {
        $this->produtoRepository = $produtoRepository;
    }

    public function criarNovoProduto(Produto $produto)
    {
        if (empty($produto->getNome()) || $produto->getPreco() <= 0) {
            throw new Exception("Nome e preço válidos são obrigatórios.");
        }
        return $this->produtoRepository->save($produto);
    }

    public function getProduto($id)
    {
        return $this->produtoRepository->getById($id);
    }

    public function listarProdutos()
    {
        return $this->produtoRepository->getAll();
    }

    public function atualizarProduto(Produto $produto)
    {
        return $this->produtoRepository->update($produto);
    }

    public function deletarProduto($id)
    {
        return $this->produtoRepository->delete($id);
    }

    public function adicionarAoCarrinho($idProduto)
    {
        $produto = $this->produtoRepository->getById($idProduto);

        //verifica se a variável não existe, se não existir retorna a mensagem
        if (!$produto) {
            return ['success' => false, 'message' => 'Produto não encontrado.'];
        }

        // verifica se o carrinho não foi criado
        if (!isset($_SESSION['carrinho'])) {
            $_SESSION['carrinho'] = [];
        }

        $encontrado = false;
        foreach ($_SESSION['carrinho'] as $chave => $item) {
            //verifica se o id do produto que esat no carrinho é igual ao qual queremos adcionar
            if ($item['id'] == $idProduto) {
                //se ja estiver, aumenta a quantidade
                $_SESSION['carrinho'][$chave]['quantidade']++;
                // marca como true indicando que não precisamos adcionar um produto novo
                $encontrado = true;
                break;
            }
        }

        // verifica se não existe, se o produto não foi encontrado no foreach anterior
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

    // Retorna todos os itens do carrinho
    public function getCarrinho()
    {
        return isset($_SESSION['carrinho']) ? $_SESSION['carrinho'] : [];
    }

    // Retorna a quantidade total de itens (somando as quantidades)
    public function getQuantidadeTotalCarrinho()
    {
        $total = 0;
        //verifica se existe o carrinho na sessão e isset retorna true
        if (isset($_SESSION['carrinho'])) {
            foreach ($_SESSION['carrinho'] as $item) {
                $total += $item['quantidade'];
            }
        }
        return $total;
    }

    // Retorna o valor total do carrinho
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

    // Remove item pelo ID
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
                // Garante que a quantidade é um número e é no mínimo 1
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
