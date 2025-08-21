<?php
// Inicie a sessão para ter acesso aos dados do usuário
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$cliente = null;

// inclui sempre
require_once '../../config/connection.php';
require_once '../../models/Cliente.php';
require_once '../../repositories/IClienteRepository.php';
require_once '../../repositories/ClienteRepository.php';
require_once '../../services/ClienteService.php';
require_once '../../controllers/ClienteController.php';

$conexao = Connection::connect();
$clienteRepository = new ClienteRepository($conexao);
$clienteService = new ClienteService($clienteRepository);
$controller = new ClienteController($clienteService);

// 1. Se enviou formulário, decide se é cadastro ou atualização
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['id_cliente'])) {
        $controller->put($_POST['id_cliente']);
    } else {
        $controller->post();
    }
}

// 2. Se já tem cliente logado, busca os dados
if (isset($_SESSION['cliente_id'])) {
    try {
        $cliente = $clienteService->getCliente($_SESSION['cliente_id']);
    } catch (Exception $e) {
        error_log("Erro ao buscar dados do cliente: " . $e->getMessage());
    }
}

if(!empty($_POST['senha'])) {
    // Atualiza a senha (hash antes!)
    $senhaHash = password_hash($_POST['senha'], PASSWORD_DEFAULT);
    $stmt = $db->prepare("UPDATE cliente SET senha = ? WHERE id = ?");
    $stmt->execute([$senhaHash, $id]);
} 
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="icon" type="image/png" href="../../../fotos/imgsite.jpg">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Ancizar+Serif:ital,wght@0,300..900;1,300..900&family=Bitter:ital,wght@0,100..900;1,100..900&family=Caudex:ital,wght@0,400;0,700;1,400;1,700&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Marcellus&family=Merriweather:ital,opsz,wght@0,18..144,300..900;1,18..144,300..900&family=Noto+Serif:ital,wght@0,100..900;1,100..900&family=Padauk:wght@400;700&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style.css" />
</head>

<body>

    <?php
    include '../header/index.php';
    ?>

    <div class="window-container">
        <div class="window-header">
            <div class="window-control close"></div>
            <div class="window-control minimize"></div>
            <div class="window-control maximize"></div>
        </div>
        <div class="form-container">
            <form action="../cadastro/index.php" method="POSt">
                <?php if ($cliente): ?>
                    <input type="hidden" name="id_cliente" value="<?php echo $cliente->getIdCliente(); ?>">
                <?php endif; ?>
                <div class="form-group">
                    <label for="nome">Nome</label>
                    <input type="text" id="nome" name="nome" value="<?php echo $cliente ? htmlspecialchars($cliente->getNome()) : ''; ?>" required />
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="cpf">CPF</label>
                        <input type="text" id="cpf" name="cpf" maxlength="14" value="<?php echo $cliente ? htmlspecialchars($cliente->getCpf()) : ''; ?>" required />
                    </div>
                    <div class="form-group">
                        <label for="rua">Rua</label>
                        <input type="text" id="rua" name="rua" maxlength="120" value="<?php echo $cliente ? htmlspecialchars($cliente->getRua()) : ''; ?>" required />
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="bairro">Bairro</label>
                        <input type="text" id="bairro" name="bairro" maxlength="50" value="<?php echo $cliente ? htmlspecialchars($cliente->getBairro()) : ''; ?>" required />
                    </div>
                    <div class="form-group" style="max-width: 120px;">
                        <label for="numero_da_casa">Número</label>
                        <input type="text" id="numero_da_casa" name="numero_da_casa" maxlength="10" value="<?php echo $cliente ? htmlspecialchars($cliente->getNumeroDaCasa()) : ''; ?>" required />
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group" style="flex: 2;">
                        <label for="cidade">Cidade</label>
                        <input type="text" id="cidade" name="cidade" value="<?php echo $cliente ? htmlspecialchars($cliente->getCidade()) : ''; ?>" required />
                    </div>

                </div>


                <div class="form-row">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" value="<?php echo $cliente ? htmlspecialchars($cliente->getEmail()) : ''; ?>" required />
                    </div>
                    <div class="form-group">
                        <label for="senha">Senha</label>
                        <input type="password" id="senha" name="senha" required />
                        <div class="show-password">
                            <input type="checkbox" id="show-password-checkbox" />
                            <label for="show-password-checkbox">Mostrar senha</label>
                        </div>
                    </div>
                </div>

                <button type="submit" class="cadastrar-btn"> <?php echo $cliente ? 'Atualizar Dados' : 'Cadastrar'; ?> </button>
            </form>
        </div>
    </div>

    <?php
    include '../footer/index.php';
    ?>

    <script>
        // Mostrar/ocultar senha
        const checkbox = document.getElementById('show-password-checkbox');
        const senhaInput = document.getElementById('senha');

        checkbox.addEventListener('change', () => {
            senhaInput.type = checkbox.checked ? 'text' : 'password';
        });
    </script>
</body>

</html>