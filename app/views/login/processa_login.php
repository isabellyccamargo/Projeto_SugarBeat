<?php

require_once '../../config/connection.php';
require_once '../../repositories/ClienteRepository.php';
require_once '../../services/ClienteService.php';
require_once '../../controllers/ClienteController.php';
require_once '../../models/Cliente.php'; 

// Verifique se a requisição é do tipo POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Verifique se os campos de email e senha foram enviados
    if (isset($_POST["email"]) && isset($_POST["password"])) {
        
        // Receba os dados do formulário com o array $_POST
        $email = $_POST["email"];
        $senha = $_POST["password"]; 

        // Conecte ao banco de dados e instancie os objetos
        $conexao = Connection::connect();
        $clienteRepository = new ClienteRepository($conexao);
        $clienteService = new ClienteService($clienteRepository);
        $clienteController = new ClienteController($clienteService);

        // --- INÍCIO DA MUDANÇA: Adicione o bloco try...catch ---
        try {
            // Chame a função do controller para buscar o cliente
            $cliente = $clienteController->getClienteByEmailAndSenha($email, $senha);

            // Se a linha acima não lançou exceção, o login foi um sucesso.
            session_start();
            $_SESSION['cliente_id'] = $cliente->getIdCliente();
            $_SESSION['cliente_email'] = $cliente->getEmail();
            
            echo "Login realizado com sucesso!";
            
            // Recomenda-se redirecionar o usuário
            // header("Location: /caminho/para/dashboard.php");
            exit();

        } catch (Exception $e) {
            // Se uma exceção foi lançada pelo Controller, o controle virá para este bloco.
            // A exceção já contém a mensagem de erro que você quer.
            
            // Você pode exibir uma mensagem genérica, ou a mensagem da exceção
            // dependendo de quão detalhados os erros podem ser.
            // echo "E-mail ou senha inválidos!";
            echo $e->getMessage();
            
            // Recomenda-se redirecionar com um parâmetro de erro
            // header("Location: /caminho/para/login.php?erro=1");
            exit();
        }
        // --- FIM DA MUDANÇA ---
    } else {
        // Campos ausentes, redirecione de volta com erro
        echo "O campo de e-mail e senha são obrigatórios!";
        // header("Location: /caminho/para/login.php?erro=2");
        exit();
    }
} else {
    // Requisição inválida, redirecione
    header("Location: /caminho/para/login.php");
    exit();
}
?>