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

        // Chame a função do controller para buscar o cliente
        $cliente = $clienteController->getClienteByEmailAndSenha($email, $senha);

        // Verifique o resultado
        if ($cliente) {
            // Login bem-sucedido!
            // Aqui você deve iniciar uma sessão e redirecionar o usuário
            // para uma página segura.
            session_start();
            $_SESSION['cliente_id'] = $cliente->getIdCliente();
            $_SESSION['cliente_email'] = $cliente->getEmail();
            //header("Location: /caminho/para/dashboard.php");
            echo "Login realizado com sucesso!";
            exit();
        } else {
            // Login falhou, redirecione de volta para a página de login
            // com uma mensagem de erro.
            //header("Location: /caminho/para/login.php?erro=1");
            
            echo "E-mail ou senha inválidos!";
            exit();
        }
    } else {
        // Campos ausentes, redirecione de volta com erro
        //header("Location: /caminho/para/login.php?erro=2");
        
        echo "O campo de e-mail e senha são obrigatórios!";
        exit();
    }
} else {
    // Requisição inválida, redirecione
    header("Location: /caminho/para/login.php");
    exit();
}
?>