<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Contracts\UserInterface;
use Exception;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;


class UserRepository implements UserInterface
{
    public Authenticatable $user;

    /**
     * @throws Exception
     */
    public function me(Request $request): UserRepository
    {
        $this->user = auth()->user();
        return $this;
    }

    /**
     * @throws Exception
     */
    public function update(array $data): UserRepository
    {
        foreach (array_keys($data) as $key) {
            if(! in_array($key, Schema::getColumnListing('users'))) {
                throw new Exception('Update failed. Invalid fields provided', 400);
            }
        }

        if(! auth()->user()->update($data)) {
            throw new Exception('Cannot update user.', 500);
        }

        $this->user = auth()->user();
        return $this;
    }
}
