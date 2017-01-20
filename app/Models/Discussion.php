<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Discussion extends Model
{
    protected $table = 'discussions';

    public function user(){
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }

    public function replies(){
        return $this->hasMany('App\Models\DiscussionReply', 'discussions_id', 'id');
    }
}
