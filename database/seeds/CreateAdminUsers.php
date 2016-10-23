<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CreateAdminUsers extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new User();
        $user->first_name = "Waleed";
        $user->last_name = "Ahmad";
        $user->email = "waleedgplus@gmail.com";
        $user->password = Hash::make('binarystar');
        $user->verified = 1;
        $user->gender = 'male';
        $user->type = 'admin';
        $user->image_uri = '/assets/img/default_image.png';
        $user->save();

        $user = new User();
        $user->first_name = "Waqas";
        $user->last_name = "Ahmad";
        $user->email = "waqasaajkal@gmail.com";
        $user->password = Hash::make('binarystar');
        $user->verified = 1;
        $user->gender = 'male';
        $user->type = 'admin';
        $user->image_uri = '/assets/img/default_image.png';
        $user->save();

        $user = new User();
        $user->first_name = "Gulfam";
        $user->last_name = "Munawar";
        $user->email = "gulfammunawar313@gmail.com";
        $user->password = Hash::make('binarystar');
        $user->verified = 1;
        $user->gender = 'male';
        $user->type = 'admin';
        $user->image_uri = '/assets/img/default_image.png';

        $user->save();
    }
}
