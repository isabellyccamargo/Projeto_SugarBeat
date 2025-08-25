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
                echo json_encode($cliente);
            } catch (Exception $e) {
                http_response_code(404);
                echo json_encode(['error' => $e->getMessage()]);
            }
        }
    }

    public function getClienteByEmail(string $email): ? Cliente
    {
        return $this->clienteService->getClienteByEmail($email);
    }

    public function post()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $cliente = new Cliente();
            $cliente->setNome($_POST['nome']);
            $cliente->setCpf($_POST['cpf']);
            $cliente->setEmail($_POST['email']);
            $cliente->setSenha(password_hash($_POST['senha'], PASSWORD_DEFAULT));
            $cliente->setCidade($_POST['cidade']);
            $cliente->setBairro($_POST['bairro']);
            $cliente->setRua($_POST['rua']);
            $cliente->setNumeroDaCasa($_POST['numero_da_casa']);

            $novoCliente = $this->clienteService->criarNovoCliente($cliente);

            if ($novoCliente) {
                $_SESSION['cliente_id'] = $novoCliente->getIdCliente();
                header("Location: index.php");
                exit;
            } else {
                echo "Erro ao cadastrar cliente.";
            }
        }
    }

    public function put($id)
    {
        // Buscar o cliente atual e altera todas as informações, menos o id e a senha.
        $clienteAtual = $this->clienteService->getCliente($id);

        $data = $_POST;

        $cliente = new Cliente(
            $data['nome'] ?? $clienteAtual->getNome(),
            $data['cpf'] ?? $clienteAtual->getCpf(),
            $data['email'] ?? $clienteAtual->getEmail(),
            $data['cidade'] ?? $clienteAtual->getCidade(),
            $data['bairro'] ?? $clienteAtual->getBairro(),
            $data['rua'] ?? $clienteAtual->getRua(),
            $data['numero_da_casa'] ?? $clienteAtual->getNumeroDaCasa()
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
