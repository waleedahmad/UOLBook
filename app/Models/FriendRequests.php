<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FriendRequests extends Model
{
    protected $table = 'friend_requests';

    public function fromUser(){
        return $this->hasOne('App\Models\User', 'id', 'from');
    }

    public function forUser(){
        return $this->hasOne('App\Models\User', 'id', 'to');
    }
}
