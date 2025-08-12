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

    public function atualizarPedido(Pedido $pedido)
    {
        return $this->pedidoRepository->update($pedido);
    }

}
