Hi {{$user->first_name}} {{$user->last_name}}!
<br>
You've made a password recovery request. Please click on this link to recover
you accounts password.
<br>
<a href="{{URL::to('/reset/password/'.$token.'/'.$user->email)}}">Recover Password</a>