<?php
/**
 * index.php
 * Este é o Front Controller da aplicação. Todas as requisições
 * são direcionadas para este arquivo, que é responsável por
 * carregar as classes e rotear a requisição para o
 * controlador e método corretos.
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

// Obtém o caminho da URL e remove a barra inicial se existir
$requestUri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
// Divide o caminho em partes
$segments = explode('/', $requestUri);

// Define o mapeamento de URLs para os controladores
$routes = [
    'clientes' => 'ClienteController',
    'produtos' => 'ProdutoController',
    'pedidos' => 'PedidoController'
];

$controllerName = 'ClienteController'; // Controlador padrão
$method = 'get'; // Método padrão
$param = null; // Parâmetro padrão

// Verifica se a URL corresponde a uma rota definida
if (isset($segments[0]) && !empty($segments[0])) {
    $route = strtolower($segments[0]);
    if (array_key_exists($route, $routes)) {
        $controllerName = $routes[$route];
    } else {
        http_response_code(404);
        die("Rota não encontrada.");
    }
}

// Verifica se existe um ID ou outro parâmetro na URL
if (isset($segments[1]) && !empty($segments[1])) {
    $param = $segments[1];
}

// Inicializa a injeção de dependência
$dbConnection = Connection::connect();
$clienteRepository = new ClienteRepository($dbConnection);
$produtoRepository = new ProdutoRepository($dbConnection);
$pedidoRepository = new PedidoRepository($dbConnection);
$itemPedidoRepository = new ItemPedidoRepository($dbConnection);

$clienteService = new ClienteService($clienteRepository);
$produtoService = new ProdutoService($produtoRepository);
$pedidoService = new PedidoService($pedidoRepository, $itemPedidoRepository);

// Mapeia o nome do controlador para a instância da classe
$controller = null;
switch ($controllerName) {
    case 'ClienteController':
        $controller = new ClienteController($clienteService);
        break;
    case 'ProdutoController':
        $controller = new ProdutoController($produtoService);
        break;
    case 'PedidoController':
        $controller = new PedidoController($pedidoService);
        break;
    default:
        http_response_code(404);
        die("Controlador não encontrado.");
}

// Chama o método do controlador com o parâmetro
if ($controller && method_exists($controller, $method)) {
    $controller->$method($param);
} else {
    http_response_code(404);
    die("Método não encontrado no controlador.");
}

?>
