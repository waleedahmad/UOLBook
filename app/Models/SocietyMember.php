<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SocietyMember extends Model
{
    protected $table = 'society_members';

    public function user(){
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }
}
