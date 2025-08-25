<?php

class ClienteService
{
    private $clienteRepository;

    public function __construct(IClienteRepository $clienteRepository)
    {
        $this->clienteRepository = $clienteRepository;
    }

    public function getCliente($id): ?Cliente
    {
        return $this->clienteRepository->getById($id);
    }

    public function getClienteByEmail(string $email): ?Cliente
    {
        return $this->clienteRepository->getClienteByEmail($email);
    }

    public function criarNovoCliente(Cliente $cliente): Cliente
    {
        $cliente->setCpf($this->removerMascaraCpf($cliente->getCpf()));
        return $this->clienteRepository->save($cliente);
    }

    public function atualizarCliente(Cliente $cliente): Cliente
    {
        $cliente->setCpf($this->removerMascaraCpf($cliente->getCpf()));
        return $this->clienteRepository->update($cliente);
    }

    private function removerMascaraCpf(string $texto): string
    {
        return str_replace(['.', '-'], '', $texto);
    }
}
