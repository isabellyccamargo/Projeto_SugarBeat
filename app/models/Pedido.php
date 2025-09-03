<?php

class Pedido implements JsonSerializable
{

    private $id_pedido;
    private $id_cliente;
    private $data;
    private $total;
    private $forma_de_pagamento;

    public function __construct(
        $id_pedido,
        $id_cliente,
        $data,
        $total,
        $forma_de_pagamento,
    ) {
        $this->id_pedido = $id_pedido;
        $this->id_cliente = $id_cliente;
        $this->data = $data;
        $this->total = $total;
        $this->forma_de_pagamento = $forma_de_pagamento;
    }

    public function getIdPedido()
    {
        return $this->id_pedido;
    }
    public function setIdPedido($id_pedido): void
    {
        $this->id_pedido = $id_pedido;
    }

    public function getIdCliente()
    {
        return $this->id_cliente;
    }
    public function setIdCliente($id_cliente): void
    {
        $this->id_cliente = $id_cliente;
    }

    public function getData()
    {
        return $this->data;
    }
    public function setData($data): void
    {
        $this->data = $data;
    }

    public function getTotal()
    {
        return $this->total;
    }
     public function setTotal($total): void
    {
        $this->total = $total;
    }

    public function getFormaDePagamento()
    {
        return $this->forma_de_pagamento;
    }
    public function setFormaDePagamento($forma_de_pagamento): void
    {
        $this->forma_de_pagamento = $forma_de_pagamento;
    }

    public function jsonSerialize(): mixed
    {

         return [
            'id_pedido' => $this->id_pedido,
            'id_cliente' => $this->id_cliente,
            'data_pedido' => $this->data,
            'total' => $this->total,
            'forma_de_pagamento' => $this->forma_de_pagamento
        ];

    }
}
