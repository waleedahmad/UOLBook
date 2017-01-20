<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Classes extends Model
{
    protected $table = 'classes';

    public function instructor(){
        return $this->hasOne('App\Models\User', 'id', 'teacher_id');
    }
}
