<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public $incrementing = false;

    protected $fillable = [
        'id', 'name', 'email', 'password', 'bio',
        'interests', 'locale', 'phone', 'age',
        'sex', 'sex_interest', 'active'
    ];

    protected $hidden = ['password'];

    public function liked(): HasMany
    {
        // likes given
        return $this->hasMany(Match::class, 'likes_id', 'id');
    }

    public function feed(int $limit): array
    {
        $user = auth()->user();
        $feed = [];

        if($user->sex_interest == 'all') {
            $feed[] = User::where('interests', 'like', "%$user->interests%")
                ->where('sex_interest', $user->sex)
                ->where('id', '<>', $user->id)
                ->where('active', true)
                ->limit($limit)
                ->get();
        }

        if($user->sex_interest == 'female' || $user->sex_interest == 'male') {
            $feed[] = User::where('interests', 'like', "%$user->interests%")
                ->where('sex_interest', 'all')
                ->orWhere('sex_interest', $user->sex)
                ->where('id', '<>', $user->id)
                ->where('active', true)
                ->limit($limit)
                ->get();
        }

        $noMatched = $user->liked()
            ->where('liked_id', $user->id)
            ->where('match', false)
            ->get();

        foreach($noMatched as $item) {
            $feed[] = User::find($item->likes_id);
        }

        return $feed;
    }
}
