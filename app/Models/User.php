<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Storage;

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

        Storage::disk('public')->delete($this->card_uri);
    }

    private function hasDefaultProfilePic($image_uri)
    {
        return ($image_uri === 'default_img_male.jpg' || $image_uri === 'default_img_female.jpg' );
    }
}
