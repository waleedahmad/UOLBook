<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Posts extends Model
{
    protected $table = 'posts';

    public function user(){
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }
}
