<?php

interface IProdutoRepository {
    public function getById($id);
    public function getAll();
}