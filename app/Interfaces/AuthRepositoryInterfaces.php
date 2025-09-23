<?php

namespace App\Interfaces;

interface AuthRepositoryInterfaces
{
    public function login(array $data);
    public function logout();
    public function me();
}
