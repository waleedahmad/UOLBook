<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;

class Posts extends Model
{
    protected $table = 'posts';

    public function user(){
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }

    public function video(){
        return $this->hasOne('App\Models\Video', 'post_id', 'id');
    }

    public function photo(){
        return $this->hasOne('App\Models\Photo', 'post_id', 'id');
    }

    public function comments(){
        return $this->hasMany('App\Models\Comment', 'post_id', 'id')->orderBy('id', 'DESC');
    }

    public function firstTwoComments(){
        return $this->hasMany('App\Models\Comment', 'post_id', 'id')->orderBy('id', 'DESC')->take(2);
    }

    public function getLikesCount(){
        return Like::where('post_id', '=', $this->id)->count();
    }

    public function isLikedByAuthUser(){
        return Like::where('user_id','=', Auth::user()->id)->where('post_id', '=', $this->id)->count();
    }

    public function societyName(){
        return Society::where('id','=', $this->society_id)->First()->name;
    }
}
