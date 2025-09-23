<?php

namespace App\Interfaces;

interface ProfileImageRepositoryInterfaces
{

    public function getAll(
        ?int $limit,
        bool $execute
    );
    public function getAllPaginated(
        ?int $rowPerPage
    );
    public function getById(string $id);
    public function create(array $data);
    public function update(string $id, array $data);
    public function delete(string $id);
}
