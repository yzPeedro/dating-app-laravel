<?php

namespace App\Repositories\Contracts;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

interface UserInterface
{
    public function me(Request $request): UserRepository;

    public function update(array $data): UserRepository;

    public function feed(int $limit): UserRepository;

    public function match(array $data): bool;

    public function matches(): array;

    public function unmatch(string $liked_id): void;

    public function getUser(string $id): User;
}
