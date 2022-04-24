<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Match extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $fillable = [
        'id', 'likes_id', 'liked_id', 'match'
    ];
}
