<?php

class ProdutoController {
    private $produtoService;

    public function __construct(ProdutoService $produtoService) {
        $this->produtoService = $produtoService;
    }

    public function get($id = null) {
        if ($id) {
            try {
                $produto = $this->produtoService->getProduto($id);
                header('Content-Type: application/json');
                echo json_encode($produto);
            } catch (Exception $e) {
                http_response_code(404);
                echo json_encode(['error' => $e->getMessage()]);
            }
        } else {
            $produtos = $this->produtoService->listarProdutos();
            header('Content-Type: application/json');
            echo json_encode($produtos);
        }
    }

    public function post() {
        $data = json_decode(file_get_contents('php://input'), true);
        $produto = new Produto(
            null,
            $data['nome'] ?? null,
            $data['preco'] ?? null,
            $data['imagem'] ?? null
        );
        try {
            $novoProduto = $this->produtoService->criarNovoProduto($produto);
            http_response_code(201);
            echo json_encode($novoProduto);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function put($id) {
        $data = json_decode(file_get_contents('php://input'), true);
        $produto = new Produto(
            $id,
            $data['nome'] ?? null,
            $data['preco'] ?? null,
            $data['imagem'] ?? null
        );
        try {
            $this->produtoService->atualizarProduto($produto);
            http_response_code(200);
            echo json_encode(['message' => 'Produto atualizado com sucesso.']);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function delete($id) {
        try {
            $this->produtoService->deletarProduto($id);
            http_response_code(200);
            echo json_encode(['message' => 'Produto deletado com sucesso.']);
        } catch (Exception $e) {
            http_response_code(404);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
}