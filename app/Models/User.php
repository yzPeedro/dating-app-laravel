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
}
