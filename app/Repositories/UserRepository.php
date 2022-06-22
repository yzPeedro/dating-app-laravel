<?php

namespace App\Repositories;

use App\Models\Connect;
use App\Models\User;
use App\Repositories\Contracts\UserInterface;
use Exception;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;


class UserRepository implements UserInterface
{
    public Authenticatable $user;

    public array $feed;

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

    public function feed(int $limit): UserRepository
    {
        $this->feed = auth()->user()->feed($limit);
        return $this;
    }

    /**
     * @throws Exception
     */
    public function match(array $data): bool
    {
        $userLiked = User::find($data['liked_id']);
        $userLikes = auth()->user();

        if(! $userLiked) {
            throw new Exception('User id not found', 400);
        }

        if(! $userLikes->liked()->where('liked_id', $userLiked->id)->first()) {
            $userLikes->liked()
                ->create([
                    'id' => Str::uuid(),
                    'liked_id' => $userLiked->id,
                    'likes_id' => $userLikes->id
                ]);
        }

        if(! $likes = $userLiked->liked()->where('liked_id', $userLikes->id)->first()) {
            return false;
        }

        $likes->update(['match' => true]);

        $userLikes->liked()
            ->where('liked_id', $userLiked->id)
            ->update(['match' => true]);

        return true;
    }

    /**
     * @throws Exception
     */
    public function matches(): array
    {
        $matches = [];
        $user = auth()->user();

        try {
            $founded = $user->liked()
                ->where('match', true)
                ->where('liked_id', '<>', $user->id)
                ->get();

            if(! $founded) {
                return [];
            }

            foreach($founded as $item) {
                $matches[] = User::find($item->liked_id);
            }

            return $matches;
        } catch (Exception $ex) {
            throw new Exception('Internal Server Error.', 500);
        }
    }

    /**
     * @throws Exception
     */
    public function unmatch(string $liked_id): void
    {
        try {
            $user = auth()->user();

            Connect::where('liked_id', $user->id)
                ->where('likes_id', $liked_id)
                ->where('match', true)
                ->delete();

            Connect::where('liked_id', $liked_id)
                ->where('likes_id', $user->id)
                ->where('match', true)
                ->delete();

        } catch (Exception $exception) {
            throw new Exception('Internal Server Error.', 500);
        }
    }

    /**
     * @throws Exception
     */
    public function getUser(string $id): User
    {
        if(! User::find($id)) {
            throw new Exception('User not found', 400);
        }

        return User::find($id);
    }
}
