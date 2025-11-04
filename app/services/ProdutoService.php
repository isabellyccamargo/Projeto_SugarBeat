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
        // pega o produto no banco de dados
        $produto = $this->produtoRepository->getById($idProduto);

        if (!$produto) {
            return ['success' => false, 'message' => 'Produto não encontrado.'];
        }

        // guarda o estoque em uma variável
        $estoque = $produto->getEstoque(); 

        if (!isset($_SESSION['carrinho'])) {
            $_SESSION['carrinho'] = [];
        }

        $encontrado = false;

        // percorre todos os itens que estão na sessão do carrinho
        foreach ($_SESSION['carrinho'] as $chave => $item) {
            // verifica se o produto já está no carrinho
            if ($item['id'] == $idProduto) {
                $novaQuantidade = $item['quantidade'] + 1;

                // nova quantidade é maior que estoque, se for ocorre mensagem de erro e não deixa adcionar
                if ($novaQuantidade > $estoque) {
                    return ['success' => false, 'message' => 'Estoque insuficiente para adicionar mais unidades.'];
                }

                // se não for, adciona nova quantidade
                $_SESSION['carrinho'][$chave]['quantidade'] = $novaQuantidade;
                $encontrado = true;
                break;
            }
        }

        // se não estiver no carrinho
        if (!$encontrado) {
            // verifica se tem pelo menos uma unidade em estoque
            if ($estoque < 1) {
                return ['success' => false, 'message' => 'Produto fora de estoque.'];
            }

            $novo_item = [
                'id' => $produto->getIdProduto(),
                'nome' => $produto->getNome(),
                'imagem' => $produto->getImagem(),
                'preco' => $produto->getPreco(),
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

        // pega produto no banco de dados
        $produto = $this->produtoRepository->getById($idProduto);
        if (!$produto) {
            return ['success' => false, 'message' => 'Produto não encontrado.'];
        }

        // armazena o estoque em uma variável
        $estoque = $produto->getEstoque(); 

        $encontrado = false;
        foreach ($_SESSION['carrinho'] as $chave => $item) {
            //verifica se o id que esta no carrinho é o msm que o usuario acabou de clicar para adcionar
            if ($item['id'] == $idProduto) {
                // garante que o valor da variavel é valido, inteiro e maior que 1
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

        if (!$encontrado) {
            return ['success' => false, 'message' => 'Produto não encontrado no carrinho.'];
        }

        return ['success' => true, 'message' => 'Quantidade atualizada com sucesso.'];
    }

     public function finalizarPedido()
    {
        // chama a função
        $verificacao = $this->verificarEstoqueCarrinho();

        // se verificação não for sucesso ele retorna o verificação que esta armazenado a mensagem de erro 
        if (!$verificacao['success']) {
            return $verificacao; 
        }
 
        // se for cai no pedido com sucesso pronto para finalizar
        return ['success' => true, 'message' => 'Pedido pronto para ser finalizado.'];
    }

    public function verificarEstoqueCarrinho()
    {
        // verifica se o carrinho existe e não está vazio
        if (!isset($_SESSION['carrinho']) || empty($_SESSION['carrinho'])) {
            return ['success' => false, 'message' => 'Carrinho vazio.'];
        }

        // percorre os produtos do carrinho 
        foreach ($_SESSION['carrinho'] as $item) {
            //confere novamente o banco 
            $produto = $this->produtoRepository->getById($item['id']);

            // caso o produto não seja encontrado mais retorna a mensagem
            if (!$produto) {
                return ['success' => false, 'message' => "Produto '{$item['nome']}' não encontrado."];
            }

            // estoque mais recente
            $estoqueAtual = $produto->getEstoque();

            // verifica quanto o cliente quer comprar se é maior ao tanto que tem disponivel
            if ($item['quantidade'] > $estoqueAtual) {
                return [
                    'success' => false,
                    'message' => "Estoque insuficiente para o produto '{$item['nome']}'. Disponível: {$estoqueAtual} unidade(s)."
                ];
            }
        }
        return ['success' => true, 'message' => 'Todos os produtos têm estoque suficiente.'];
    }

}
