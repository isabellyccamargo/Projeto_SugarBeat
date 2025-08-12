<?php
interface IClienteRepository
{
    public function getById($id);
    public function getAll();
    public function save($cliente);
    public function update($cliente);
    public function delete($id);
}
