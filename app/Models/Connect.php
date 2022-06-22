<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Connect extends Model
{
    use HasFactory;

    protected $table = 'matches';

    public $incrementing = false;

    protected $fillable = [
        'id', 'likes_id', 'liked_id', 'match'
    ];
}
