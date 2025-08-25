<?php
interface IClienteRepository
{
    public function getById($id);
    public function save($cliente);
    public function update($cliente);
     public function getClienteByEmail($email);
}
