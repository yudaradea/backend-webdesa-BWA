<?php

namespace App\Interfaces;

interface ProfileRepositoryInterfaces
{
    public function getProfile();
    public function getById(string $id);
    public function create(array $data);
    public function update(string $id, array $data);
    public function delete(string $id);
}
