<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SocietyRequest extends Model
{
    protected $table = 'member_requests';

    public function user(){
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }
}
