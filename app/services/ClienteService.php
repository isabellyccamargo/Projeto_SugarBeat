<?php

class ClienteService {
    private $clienteRepository;

    public function __construct(IClienteRepository $clienteRepository) {
        $this->clienteRepository = $clienteRepository;
    }

    public function criarNovoCliente(Cliente $cliente) {
        // Exemplo de regra de negócio: validação de CPF ou email
        if (empty($cliente->getNome()) || empty($cliente->getEmail())) {
            throw new Exception("Nome e email são obrigatórios.");
        }
        return $this->clienteRepository->save($cliente);
    }

    public function getCliente($id) {
        return $this->clienteRepository->getById($id);
    }
    
    public function getClienteByEmailAndSenha($email, $senha): Cliente{
        return $this->clienteRepository->getClienteByEmailAndSenha($email, $senha);
    }

    public function listarClientes() {
        return $this->clienteRepository->getAll();
    }

    public function atualizarCliente(Cliente $cliente) {
        return $this->clienteRepository->update($cliente);
    }
}

?>