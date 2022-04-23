<?php

namespace App\Repositories\Contracts;

use App\Repositories\AuthRepository;

interface AuthInterface
{
    public function login(array $data): AuthRepository;

    public function register(array $data): AuthRepository;
}
