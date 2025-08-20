<?php
class ClienteController
{
    private $clienteService;

    public function __construct(ClienteService $clienteService)
    {
        $this->clienteService = $clienteService;
    }

    public function get($id = null)
    {
        if ($id) {
            try {
                $cliente = $this->clienteService->getCliente($id);
                //header('Content-Type: application/json');
                echo json_encode($cliente);
            } catch (Exception $e) {
                http_response_code(404);
                echo json_encode(['error' => $e->getMessage()]);
            }
        } else {
            $clientes = $this->clienteService->listarClientes();
            header('Content-Type: application/json');
            echo json_encode($clientes);
        }
    }

    public function getClienteByEmailAndSenha($email, $senha): Cliente
    {
        if (empty($email) || empty($senha)) {
            throw new Exception("Nome e email são obrigatórios.");
        }

        try {
            return $this->clienteService->getClienteByEmailAndSenha($email, $senha);
            //echo json_encode($cliente);
        } catch (Exception $e) {
         throw new Exception("Cliente não encontrado.");
            //return http_response_code(404);
            //echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function post()
    {
        $data = $_POST;
        $cliente = new Cliente(
            null,
            $data['nome'] ?? null,
            $data['cpf'] ?? null,
            $data['email'] ?? null,
            $data['senha'] ?? null,
            $data['cidade'] ?? null,
            $data['bairro'] ?? null,
            $data['rua'] ?? null,
            $data['numero_da_casa'] ?? null
        );
        try {
            $novoCliente = $this->clienteService->criarNovoCliente($cliente);
            http_response_code(201);
            echo json_encode($novoCliente);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function put($id)
    {
       $data = $_POST;
        $cliente = new Cliente(
            $id,
            $data['nome'] ?? null,
            $data['cpf'] ?? null,
            $data['email'] ?? null,
            $data['senha'] ?? null,
            $data['cidade'] ?? null,
            $data['bairro'] ?? null,
            $data['rua'] ?? null,
            $data['numero_da_casa'] ?? null
        );
        try {
            $this->clienteService->atualizarCliente($cliente);
            http_response_code(200);
            echo json_encode(['message' => 'Cliente atualizado com sucesso.']);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
}
