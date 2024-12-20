<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Model;
use MongoDB\Laravel\Eloquent\Model;

class CardList extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function cards() {
        return $this->hasMany(Card::class, 'listId');
    }
}
