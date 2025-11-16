<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once '../../config/connection.php';
require_once '../../models/Produto.php';
require_once '../../repositories/ProdutoRepository.php';
require_once '../../services/ProdutoService.php';
$conexao = Connection::connect();
$produtoRepository = new ProdutoRepository($conexao);
$produtoService = new ProdutoService($produtoRepository);

// Se o cliente NÃO estiver logado, retornar imediatamente
if (!isset($_SESSION['cliente_id'])) {
    echo json_encode(['success' => false, 'message' => 'Usuário não logado.', 'redirect' => '../login/?origem=carrinho']);
    exit();
}

$verificacao = $produtoService->verificarEstoqueCarrinho();

if (!$verificacao['success']) {
    // 2. Estoque insuficiente: retorna a mensagem de erro
    echo json_encode([
        'success' => false, 
        'message' => $verificacao['message']
    ]);
    exit();
}

echo json_encode(['success' => true, 'message' => 'Estoque verificado com sucesso.', 'redirect' => '../pedidos/index.php']);
exit();
?>