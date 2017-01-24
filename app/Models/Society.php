<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Society extends Model
{
    protected $table = 'societies';

    public function admin(){
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }
}
