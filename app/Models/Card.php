<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;
use MongoDB\Laravel\Relations\EmbedsMany;

class Card extends Model
{
    use HasFactory;

    protected $guarded = [];

    // public function comments(): EmbedsMany
    // {
    //     return $this->embedsMany(Comment::class);
    // }
}
