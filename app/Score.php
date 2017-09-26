<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Score extends Model
{
    protected $fillable = [
        'candiate',
        'score',
        'user_id',
        'nav'
    ];
     /**
     * 属于该打分的用户。
     */
    public function users()
    {
        return $this->belongsToMany('App\User');
    }

}