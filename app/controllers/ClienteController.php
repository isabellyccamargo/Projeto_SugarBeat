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
        } else {
            $clientes = $this->clienteService->listarClientes();
            header('Content-Type: application/json');
            echo json_encode($clientes);
        }
    }

    // public function getClienteByEmailAndSenha($email, $senha): Cliente
    // {
    //     if (empty($email) || empty($senha)) {
    //         throw new Exception("Nome e email são obrigatórios.");
    //     }

    //     try {
    //         return $this->clienteService->getClienteByEmailAndSenha($email, $senha);
    //     } catch (Exception $e) {
    //         throw new Exception("Cliente não encontrado.");
    //     }
    // }

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
    $data = $_POST;

    // Buscar o cliente atual para não perder a senha existente
    $clienteAtual = $this->clienteService->getCliente($id);

    // Se o usuário digitou uma senha, gerar hash; senão, manter a antiga
    $senhaHash = !empty($data['senha']) ? password_hash($data['senha'], PASSWORD_DEFAULT) : $clienteAtual->getSenha();

    $cliente = new Cliente(
        $id,
        $data['nome'] ?? $clienteAtual->getNome(),
        $data['cpf'] ?? $clienteAtual->getCpf(),
        $data['email'] ?? $clienteAtual->getEmail(),
        $senhaHash,
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
