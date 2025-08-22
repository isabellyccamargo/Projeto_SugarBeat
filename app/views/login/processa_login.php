<?php
// processa.login.php
require_once '../../config/connection.php';
require_once '../../repositories/ClienteRepository.php';
require_once '../../services/ClienteService.php';
require_once '../../models/Cliente.php'; 

// Verifique se a requisição é do tipo POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Verifique se os campos de email e senha foram enviados
    if (isset($_POST["email"]) && isset($_POST["password"])) {
        
        $email = $_POST["email"];
        $senha_digitada = $_POST["password"]; 

        try {
            $conexao = Connection::connect();
            $clienteRepository = new ClienteRepository($conexao);
            $clienteService = new ClienteService($clienteRepository);

            // 1. Busque o cliente pelo email
            $cliente = $clienteService->getClienteByEmail($email);

            // 2. Verifique se o cliente existe e se a senha está correta
            if ($cliente && password_verify($senha_digitada, $cliente->getSenha())) {
                // Login bem-sucedido!
                session_start();
                $_SESSION['cliente_id'] = $cliente->getIdCliente();
                $_SESSION['cliente_email'] = $cliente->getEmail();
                $_SESSION['cliente_nome'] = $cliente->getNome();
                
                header("Location: ../cadastro/index.php?editar=true");
                exit();
            } else {
                // Se o cliente não foi encontrado ou a senha está incorreta
                header("Location: index.php?erro=" . urlencode("E-mail ou senha inválidos."));
                exit();
            }

        } catch (Exception $e) {
            // Em caso de erro, redirecione para a página de login com uma mensagem
            error_log("Erro de login: " . $e->getMessage());
            header("Location: index.php?erro=" . urlencode("Ocorreu um erro no servidor."));
            exit();
        }
    } else {
        header("Location: index.php?erro=" . urlencode("O campo de e-mail e senha são obrigatórios!"));
        exit();
    }
} else {
    header("Location: index.php");
    exit();
}