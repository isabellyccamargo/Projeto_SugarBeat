<?php

require_once 'IClienteRepository.php';

class ClienteRepository implements IClienteRepository
{
    // A propriedade $db simula a conexão com o banco de dados.
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getById($id)
    {
        // echo "Buscando cliente com ID: $id no banco de dados...\n";
        $stmt = $this->db->prepare("SELECT * FROM cliente WHERE id_cliente = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $clienteData = $stmt->fetch(PDO::FETCH_ASSOC);

        return $clienteData ? 
            new Cliente(
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
            ) : null ;
    }

    public function getClienteByEmailAndSenha($email, $senha): Cliente
{
    echo "Validando login do usuário \n";
    $stmt = $this->db->prepare("SELECT * FROM cliente WHERE email = :email and senha = :senha");
    $stmt->bindValue(':email', $email, PDO::PARAM_STR);
    $stmt->bindValue(':senha', $senha, PDO::PARAM_STR);
    $stmt->execute();
    $clienteData = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$clienteData) {
        // Se os dados não forem encontrados, lance uma exceção.
        throw new Exception("E-mail ou senha inválidos.");
    }

    // Se os dados forem encontrados, retorne o objeto Cliente.
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

    public function getAll()
    {
      echo "Buscando todos os clientes no banco de dados...\n";
        $stmt = $this->db->query("SELECT * FROM cliente");
        $clientesData = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $clientes = [];

        foreach ($clientesData as $data) {
            $clientes[] = new Cliente(
                $data['id_cliente'],
                $data['nome'],
                $data['cpf'],
                $data['email'],
                $data['senha'],
                $data['cidade'],
                $data['bairro'],
                $data['rua'],
                $data['numero_da_casa'],
                $data['data_cadastro']
            );
        }

        return $clientes;
    }

    public function save($cliente)
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

    public function update($cliente)
    {
      echo "Atualizando cliente com ID: " . $cliente->getIdCliente() . " no banco de dados...\n";
        $stmt = $this->db->prepare("UPDATE cliente SET nome = :nome, cpf = :cpf, email = :email, senha = :senha, cidade = :cidade, bairro = :bairro, rua = :rua, numero_da_casa = :numero_da_casa WHERE id_cliente = :id");
        $stmt->bindValue(':id', $cliente->getIdCliente(), PDO::PARAM_INT);
        $stmt->bindValue(':nome', $cliente->getNome());
        $stmt->bindValue(':cpf', $cliente->getCpf());
        $stmt->bindValue(':email', $cliente->getEmail());
        $stmt->bindValue(':senha', $cliente->getSenha());
        $stmt->bindValue(':cidade', $cliente->getCidade());
        $stmt->bindValue(':bairro', $cliente->getBairro());
        $stmt->bindValue(':rua', $cliente->getRua());
        $stmt->bindValue(':numero_da_casa', $cliente->getNumeroDaCasa());
        return $stmt->execute();
    }

}
