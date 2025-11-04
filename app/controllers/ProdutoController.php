<?php

class ProdutoController {
    private $produtoService;

    public function __construct(ProdutoService $produtoService) {
        $this->produtoService = $produtoService;
    }

    public function get($id = null): mixed {
        if ($id) {
            try {
                return $this->produtoService->getProduto($id);
            } catch (Exception $e) {
                http_response_code(404);
                return json_encode(['error' => $e->getMessage()]);
            }
        } else {
            return $this->produtoService->listarProdutos();
        }
    }

}