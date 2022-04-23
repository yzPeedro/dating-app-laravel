<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Contracts\AuthInterface;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class AuthRepository implements AuthInterface
{
    public array $tokens;

    public User $user;

    /**
     * @throws Exception
     */
    public function login(array $data): AuthRepository
    {
        if(! $this->userExists($data['email'])) {
            throw new Exception('Email provided not found.', 401);
        }

        if(! $this->validateLogin($data['email'], $data['password'])) {
            throw new Exception('Wrong credential provided.', 401);
        }

        $this->user->createToken($this->user->email);

        $this->tokens = !is_array($this->user->tokens) ?
            [$this->user->tokens] : $this->user->tokens;

        return $this;
    }

    private function userExists(string $email): bool
    {
        if(! User::where('email', $email)->first()) {
            return false;
        }

        return true;
    }

    private function validateLogin(string $email, string $password): bool
    {
        $user = User::where('email', $email)->first();

        if(! Hash::check($password, $user->password)) {
            return false;
        }

        $this->user = $user;

        return true;
    }
}
