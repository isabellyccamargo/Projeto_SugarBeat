<?php

class PedidoService
{
    private $pedidoRepository;
    private $itemPedidoRepository;

    public function __construct(IPedidoRepository $pedidoRepository, IItemPedidoRepository $itemPedidoRepository)
    {
        $this->pedidoRepository = $pedidoRepository;
        $this->itemPedidoRepository = $itemPedidoRepository;
    }

    public function criarNovoPedido(Pedido $pedido, array $itens)
    {
        // Exemplo de regra de negócio: calcular o total do pedido
        $total = 0;
        foreach ($itens as $item) {
            $total += $item->getSubTotal();
        }
        $pedido->setTotal($total);

        $novoPedido = $this->pedidoRepository->save($pedido);

        foreach ($itens as $item) {
            $item->setIdPedido($novoPedido->getIdPedido());
            $this->itemPedidoRepository->save($item);
        }

        return $novoPedido;
    }

    public function getPedidoComItens($id)
    {
        $pedido = $this->pedidoRepository->getById($id);
        $itens = $this->itemPedidoRepository->getByPedidoId($id);
        return ['pedido' => $pedido, 'itens' => $itens];
    }

    public function listarPedidos()
    {
        return $this->pedidoRepository->getAll();
    }

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

    public function getPedidosPorCliente($clienteId) {
        return $this->pedidoRepository->getPedidosPorCliente($clienteId);
    }
    
}
