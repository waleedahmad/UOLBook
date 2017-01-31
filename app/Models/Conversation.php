<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    protected $table = 'conversations';

    public function you(){
        return $this->hasOne('App\Models\User', 'id', 'from');
    }

    public function friend(){
        return $this->hasOne('App\Models\User', 'id', 'to');
    }

    public function getFirstMessage(){
        return $this->hasMany('App\Models\Message', 'conversation_id', 'id')->orderBy('id', 'DESC')->take(1);
    }

    public function getMessages(){
        return $this->hasMany('App\Models\Message', 'conversation_id', 'id')->orderBy('id', 'ASC')->get();
    }
}
