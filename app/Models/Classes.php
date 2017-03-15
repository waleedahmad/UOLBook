<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Classes extends Model
{
    protected $table = 'classes';

    public function instructor(){
        return $this->hasOne('App\Models\User', 'id', 'teacher_id');
    }

    public function purgeUploads(){
        $uploads = FileUploads::where('class_id','=', $this->id)->get();

        foreach($uploads as $upload){
            Storage::disk('public')->delete($upload->file_uri);
        }
    }

    public function course(){
        return $this->hasOne('App\Models\Course', 'id', 'course_id');
    }
}
