<?php
/**
 * main.php
 * Script para testes na linha de comando (CLI).
 * Ele utiliza o autoloader para carregar as classes e
 * simula a execução de um controlador.
 */

// --- AUTOLOADER ---
// A função spl_autoload_register permite registrar um autoloader,
// que será executado automaticamente pelo PHP quando uma classe
// for instanciada pela primeira vez.
spl_autoload_register(function ($className) {
    // Lista de diretórios onde as classes podem ser encontradas
    $directories = [
        'app/controllers/',
        'app/models/',
        'app/services/',
        'app/repositories/',
        'app/interfaces/',
        'app/config/'
    ];

    // Mapeamento especial para o arquivo database.php
    if ($className === 'Connection') {
        $filePath = 'app/config/database.php';
        if (file_exists($filePath)) {
            require_once $filePath;
            return;
        }
    }

    // Procura a classe em cada um dos diretórios
    foreach ($directories as $directory) {
        $filePath = $directory . $className . '.php';
        if (file_exists($filePath)) {
            require_once $filePath;
            return;
        }
    }
});
// --- FIM DO AUTOLOADER ---

function main()
{
    // Simulação de injeção de dependência e inicialização
    $dbConnection = Connection::connect();

    $clienteRepository = new ClienteRepository($dbConnection);
    // $produtoRepository = new ProdutoRepository($dbConnection);
    // $pedidoRepository = new PedidoRepository($dbConnection);
    // $itemPedidoRepository = new ItemPedidoRepository($dbConnection);

    $clienteService = new ClienteService($clienteRepository);
    // $produtoService = new ProdutoService($produtoRepository);
    // $pedidoService = new PedidoService($pedidoRepository, $itemPedidoRepository);

    $clienteController = new ClienteController($clienteService);
    // $produtoController = new ProdutoController($produtoService);
    // $pedidoController = new PedidoController($pedidoService);

    // Simulação de uma requisição GET para um cliente específico
    echo "--- Testando a busca por um cliente com ID 1 ---\n";
    $clienteController->get(1);

    // Simulação de uma requisição GET para todos os clientes
    echo "\n--- Testando a busca por todos os clientes ---\n";
    $clienteController->get();
}

main();