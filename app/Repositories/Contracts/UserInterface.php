<?php

namespace App\Repositories\Contracts;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;

interface UserInterface
{
    public function me(Request $request): UserRepository;

    public function update(array $data): UserRepository;

    public function feed(int $limit): UserRepository;

    public function match(array $data): bool;
}
