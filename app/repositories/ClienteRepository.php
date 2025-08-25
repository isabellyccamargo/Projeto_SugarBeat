<?php

require_once 'IClienteRepository.php';

class ClienteRepository implements IClienteRepository
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM cliente WHERE id_cliente = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $clienteData = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$clienteData) {
            return new Cliente();
        }

        return new Cliente(
            $clienteData['id_cliente'],
            $clienteData['nome'],
            $clienteData['cpf'],
            $clienteData['email'],
            $clienteData['senha'],
            $clienteData['cidade'],
            $clienteData['bairro'],
            $clienteData['rua'],
            $clienteData['numero_da_casa'],
            $clienteData['data_cadastro']
        );
    }

    public function getClienteByEmail($email): Cliente
    {
        $stmt = $this->db->prepare("SELECT * FROM cliente WHERE email = :email");
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        $clienteData = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$clienteData) {
            return new Cliente();
        }

        return new Cliente(
            $clienteData['id_cliente'],
            $clienteData['nome'],
            $clienteData['cpf'],
            $clienteData['email'],
            $clienteData['senha'],
            $clienteData['cidade'],
            $clienteData['bairro'],
            $clienteData['rua'],
            $clienteData['numero_da_casa'],
            $clienteData['data_cadastro']
        );
    }

    public function save($cliente): Cliente
    {
        echo "Salvando cliente: " . $cliente->getNome() . " no banco de dados...\n";
        $stmt = $this->db->prepare("INSERT INTO cliente (nome, cpf, email, senha, cidade, bairro, rua, numero_da_casa) VALUES (:nome, :cpf, :email, :senha, :cidade, :bairro, :rua, :numero_da_casa)");
        $stmt->bindValue(':nome', $cliente->getNome());
        $stmt->bindValue(':cpf', $cliente->getCpf());
        $stmt->bindValue(':email', $cliente->getEmail());
        $stmt->bindValue(':senha', $cliente->getSenha());
        $stmt->bindValue(':cidade', $cliente->getCidade());
        $stmt->bindValue(':bairro', $cliente->getBairro());
        $stmt->bindValue(':rua', $cliente->getRua());
        $stmt->bindValue(':numero_da_casa', $cliente->getNumeroDaCasa());
        $stmt->execute();

        $cliente->setIdCliente($this->db->lastInsertId());

        return $cliente;
    }

    public function update($cliente): Cliente
    {
        echo "Atualizando cliente com ID: " . $cliente->getIdCliente() . " no banco de dados...\n";
        $stmt = $this->db->prepare("UPDATE cliente SET nome = :nome, cpf = :cpf, email = :email, cidade = :cidade, bairro = :bairro, rua = :rua, numero_da_casa = :numero_da_casa WHERE id_cliente = :id");
        $stmt->bindValue(':id', $cliente->getIdCliente());
        $stmt->bindValue(':nome', $cliente->getNome());
        $stmt->bindValue(':cpf', $cliente->getCpf());
        $stmt->bindValue(':email', $cliente->getEmail());
        $stmt->bindValue(':cidade', $cliente->getCidade());
        $stmt->bindValue(':bairro', $cliente->getBairro());
        $stmt->bindValue(':rua', $cliente->getRua());
        $stmt->bindValue(':numero_da_casa', $cliente->getNumeroDaCasa());

        $stmt->execute();

        return $cliente;
    }
}
