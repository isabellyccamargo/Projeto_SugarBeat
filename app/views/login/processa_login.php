<?php

// ... (código de inclusão de arquivos, como já está) ...
require_once '../../config/connection.php';
require_once '../../repositories/ClienteRepository.php';
require_once '../../services/ClienteService.php';
require_once '../../controllers/ClienteController.php';
require_once '../../models/Cliente.php'; 

// Verifique se a requisição é do tipo POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Verifique se os campos de email e senha foram enviados
    if (isset($_POST["email"]) && isset($_POST["password"])) {
        
        $email = $_POST["email"];
        $senha = $_POST["password"]; 

        try {
            $conexao = Connection::connect();
            $clienteRepository = new ClienteRepository($conexao);
            $clienteService = new ClienteService($clienteRepository);
            $clienteController = new ClienteController($clienteService);

            $cliente = $clienteController->getClienteByEmailAndSenha($email, $senha);

            // Inicie a sessão e armazene os dados do cliente
            session_start();
            $_SESSION['cliente_id'] = $cliente->getIdCliente();
            $_SESSION['cliente_email'] = $cliente->getEmail();
            
            // Redireciona para a página de "Meus Dados" com o parâmetro para carregar os dados
            header("Location: ../cadastro/index.php?editar=true");
            exit();

        } catch (Exception $e) {
            // Em caso de erro, redirecione para a página de login com uma mensagem
            // Você pode usar um parâmetro na URL para exibir a mensagem na página de login
            header("Location: indexlogin.php?erro=" . urlencode("E-mail ou senha inválidos."));
            exit();
        }
    } else {
        header("Location: indexlogin.php?erro=" . urlencode("O campo de e-mail e senha são obrigatórios!"));
        exit();
    }
} else {
    header("Location: indexlogin.php");
    exit();
}   