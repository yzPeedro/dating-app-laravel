<?php

namespace App\Repositories;

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

    public Collection $feed;

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
        $user = auth()->user();

        // TODO: add locale proximity parameter
        // TODO: add likes, matchs and messages

        if($user->sex_interest == 'all') {
            $this->feed = User::where('interests', 'like', "%$user->interests%")
                ->where('sex_interest', $user->sex)
                ->where('id', '<>', $user->id)
                ->where('active', true)
                ->limit($limit)
                ->get();
        }

        if($user->sex_interest == 'female' || $user->sex_interest == 'male') {
            $this->feed = User::where('interests', 'like', "%$user->interests%")
                ->where('sex_interest', 'all')
                ->orWhere('sex_interest', $user->sex)
                ->where('id', '<>', $user->id)
                ->where('active', true)
                ->limit($limit)
                ->get();
        }

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
}
