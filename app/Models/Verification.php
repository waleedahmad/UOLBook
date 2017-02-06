<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Verification extends Model
{
    protected $table = 'verification_requests';

    public function user(){
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }
}
