<?php
session_start();

// Você pode precisar incluir outros arquivos para verificar o status do login, como o ClienteService
// Exemplo: require_once '../../services/ClienteService.php';

// Supondo que a verificação de login seja um simples 'isset' em uma variável de sessão.
// Se você usa uma classe de serviço, a lógica pode ser 'ClienteService::isLoggedIn()'
if (isset($_SESSION['cliente_id'])) {
    // Se o cliente está logado, redireciona para a página de finalização do pedido
    header("Location: ../pedido/index.php");
    exit();
} else {
    // Se o cliente NÃO está logado, redireciona para a página de login
    // Usamos um parâmetro de URL para que a página de login saiba para onde redirecionar depois
    header("Location: ../login/index.php?redirect_to=" . urlencode($_SERVER['REQUEST_URI']));
    exit();
}
?>