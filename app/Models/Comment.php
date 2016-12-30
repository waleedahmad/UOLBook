<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $table = 'comments';

    public function user(){
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }
}
