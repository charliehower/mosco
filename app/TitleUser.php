<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TitleUser extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'user_id',
        'title_id',
        'title',
//        'rank',
//        'score',
        'time',
    ];
     /**
     * 属于该头衔的用户。
     */
    public function users()
    {
        return $this->belongsToMany('App\User');
    }
}
