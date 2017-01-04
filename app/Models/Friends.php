<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Friends extends Model
{
    protected $table = 'friends';

    public function connection(){
        return $this->hasOne('App\Models\User', 'id', 'friend');
    }
}
