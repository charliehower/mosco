<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    protected $fillable = [
        'user_id',
        'nav_id',
        'finish',
        'cnt',
        'award'
    ];
}
