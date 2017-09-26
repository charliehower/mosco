<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Title extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'id',
        'name',
        'rank',
        'dy'
    ];
     /**
     * 属于该头衔的用户。
     */
    public function users()
    {
        return $this->belongsToMany('App\User')->withPivot(['title','rank','score','time']);
    }
}