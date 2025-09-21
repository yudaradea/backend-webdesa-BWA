<?php

namespace App\Interfaces;

interface SocialAssistanceRecipientRepositoryInterface
{
    public function getAll(
        ?string $search,
        ?int $limit,
        bool $execute
    );

    public function getAllPaginated(
        ?string $search,
        ?int $rowPerPage
    );

    public function getById(string $id);

    public function create(array $data);
}
