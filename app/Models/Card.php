<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;

class Card extends Model
{
    use HasFactory;

    protected $guarded = [];
}
