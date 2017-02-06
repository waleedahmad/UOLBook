<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Student;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Purge users uploads
     */
    public function purgeUploads(){
        $uploads = FileUploads::where('user_id','=', $this->id)->get();
        $societies = Society::where('user_id','=', $this->id)->get();

        $posts = Posts::where('user_id', '=', $this->id)->get();

        foreach($uploads as $upload){
            Storage::disk('public')->delete($upload->file_uri);
        }

        foreach($societies as $society){
            Storage::disk('public')->delete($society->image_uri);
        }

        foreach($posts as $post){
            if($post->type === 'photo')
            {
                Storage::disk('public')->delete($post->photo->image_uri);
            }
            if($post->type === 'video'){
                Storage::disk('public')->delete($post->video->video_uri);
            }
        }

        if(!$this->hasDefaultProfilePic(basename($this->image_uri))){
            Storage::disk('public')->delete($this->image_uri);
        }

        if(!$this->hasDefaultProfileCover($this->cover_uri)){
            Storage::disk('public')->delete($this->cover_uri);
        }

        Storage::disk('public')->delete($this->card_uri);
    }

    public function enrolledClasses(){
        return $this->hasMany('App\Models\Classes', 'teacher_id', 'id')->whereIn('id', $this->getClassIDs());
    }

    public function otherClasses(){
        return $this->hasMany('App\Models\Classes', 'teacher_id', 'id')->whereNotIn('id', $this->getClassIDs());
    }

    protected function getClassIDs(){
        return Student::where('user_id', '=', Auth::user()->id)->pluck('class_id')->toArray();
    }

    private function hasDefaultProfilePic($image_uri)
    {
        return ($image_uri === 'default_img_male.jpg' || $image_uri === 'default_img_female.jpg' );
    }

    protected function hasDefaultProfileCover($cover_uri)
    {
        return $cover_uri === 'default/img/profile_header.jpg';
    }
}
