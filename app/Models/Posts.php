<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Posts extends Model
{
    protected $table = 'posts';

    public function user(){
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }

    public function video(){
        return $this->hasOne('App\Models\Video', 'post_id', 'id');
    }

    public function photo(){
        return $this->hasOne('App\Models\Photo', 'post_id', 'id');
    }
}
