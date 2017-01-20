<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassJoin extends Model
{
    protected $table = 'join_requests';

    public function user(){
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }
}
