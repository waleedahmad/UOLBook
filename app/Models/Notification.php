<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = 'notifications';

    public function fromUser(){
        return $this->hasOne('App\Models\User', 'id', 'from');
    }

    public function forUser(){
        return $this->hasOne('App\Models\User', 'id', 'to');
    }
}
