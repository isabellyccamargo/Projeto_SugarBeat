<?php

class ClienteService
{
    private $clienteRepository;

    public function __construct(IClienteRepository $clienteRepository)
    {
        $this->clienteRepository = $clienteRepository;
    }

    public function getCliente($id)
    {
        return $this->clienteRepository->getById($id);
    }

    public function getClienteByEmail(string $email): ?Cliente
    {
        return $this->clienteRepository->getClienteByEmail($email);
    }

    public function criarNovoCliente(Cliente $cliente)
    {
        if (empty($cliente->getNome()) || empty($cliente->getEmail())) {
            throw new Exception("Nome e e-mail são obrigatórios.");
        }

        return $this->clienteRepository->save($cliente);
    }

    public function atualizarCliente(Cliente $cliente)
    {
        return $this->clienteRepository->update($cliente);
    }
}
