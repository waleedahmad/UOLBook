<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use App\Models\Notification;

class Society extends Model
{
    protected $table = 'societies';

    public function admin(){
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }

    public function purgeUploads(){
        $posts = Posts::where('society_id','=', $this->id);

        foreach($posts->get() as $post){
            $this->deletePostNotifications($post);
            if($post->type === 'photo')
            {
                Storage::disk('public')->delete($post->photo->image_uri);
            }
            if($post->type === 'video'){
                Storage::disk('public')->delete($post->video->video_uri);
            }
        }

        $posts->delete();
    }

    protected function deletePostNotifications($post){
        Notification::where('type', '=', 'comment')->where('target','=', $post->id)->delete();
        Notification::where('type', '=', 'like')->where('target','=', $post->id)->delete();
    }
}
