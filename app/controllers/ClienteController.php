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

    public function getClienteByEmail(string $email): ?Cliente
    {
        return $this->clienteService->getClienteByEmail($email);
    }

    public function post()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
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

                // ADICIONADO: Define as variáveis de sessão para o login do cliente
                if ($novoCliente) {
                    $_SESSION['cliente_id'] = $novoCliente->getIdCliente();
                    $_SESSION['cliente_nome'] = $novoCliente->getNome();
                    $_SESSION['cliente_email'] = $novoCliente->getEmail();
                }

                $clienteIdFormatado = str_pad($novoCliente->getIdCliente(), 5, '0', STR_PAD_LEFT);

                // Define a mensagem de sucesso na sessão
                $_SESSION['alert_message'] = [
                    'type' => 'success',
                    'title' => 'Sucesso!',
                    'text' => 'Cliente cadastrado com sucesso.  <br><br>' . '<span style="font-weight:bold; font-size:20px;">#' . $clienteIdFormatado . '</span>'
                ];

                // Redireciona para o index para que a mensagem seja exibida
                header("Location: ../cadastro/index.php");
                exit();
            } catch (Exception $e) {
                // Define a mensagem de erro na sessão
                $_SESSION['alert_message'] = [
                    'type' => 'error',
                    'title' => 'Erro!',
                    'text' => 'Erro ao cadastrar cliente: ' . $e->getMessage()
                ];

                // Redireciona para o index para que a mensagem seja exibida
                header("Location: ../cadastro/index.php");
                exit();
            }
        }
    }

    public function put($id)
    {
        try {
            // Garante que o ID do cliente está vindo do formulário e não da URL
            if (!isset($_POST['id_cliente'])) {
                throw new Exception("ID do cliente não fornecido.");
            }

            $clienteAtual = $this->clienteService->getCliente($id);

            // Cria um novo objeto Cliente com os dados do POST
            $cliente = new Cliente(
                $clienteAtual->getIdCliente(),
                $_POST['nome'] ?? $clienteAtual->getNome(),
                $_POST['cpf'] ?? $clienteAtual->getCpf(),
                $_POST['email'] ?? $clienteAtual->getEmail(),
                $clienteAtual->getSenha(), // Mantém a senha atual
                $_POST['cidade'] ?? $clienteAtual->getCidade(),
                $_POST['bairro'] ?? $clienteAtual->getBairro(),
                $_POST['rua'] ?? $clienteAtual->getRua(),
                $_POST['numero_da_casa'] ?? $clienteAtual->getNumeroDaCasa()
            );

            $this->clienteService->atualizarCliente($cliente);

            $clienteIdFormatado = str_pad($clienteAtual->getIdCliente(), 5, '0', STR_PAD_LEFT);

            // Define a mensagem de sucesso na sessão
            $_SESSION['alert_message'] = [
                'type' => 'success',
                'title' => 'Sucesso!',
                'text' => 'Dados atualizados com sucesso. <br><br>' . '<span style="font-weight:bold; font-size:20px;">#' . $clienteIdFormatado . '</span>'
            ];
        } catch (Exception $e) {
            // Define a mensagem de erro na sessão
            $_SESSION['alert_message'] = [
                'type' => 'error',
                'title' => 'Erro!',
                'text' => 'Erro ao atualizar dados: ' . $e->getMessage()
            ];
        } finally {
            // Redireciona para o index, independentemente do resultado
            header("Location: ../cadastro/index.php");
            exit();
        }
    }
}
