<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Validator;
use App\Models\FriendRequests;
use App\Models\Friends;
use App\Models\Posts;
use App\Models\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * Show user profile
     * @param $id
     * @return \Illuminate\View\View
     */
    public function getUserProfile($id)
    {
        $user = User::where('id', '=', $id)->first();
        $posts = Posts::where('user_id', '=', $id)->orderBy('id', 'DESC')->paginate(5);

        $show_profile = $this->profileViewerIsYourFriend($id) || Auth::user()->id === intval($id);
        $request_pending = ($show_profile) ? false : $this->friendRequestPending($id);
        $friends = $this->getAllFriends($id);

        return view('profile.profile')
            ->with('user', $user)
            ->with('posts', $posts)
            ->with('show_profile', $show_profile)
            ->with('request_pending', $request_pending)
            ->with('friends', $friends);
    }

    /**
     * Get user friends
     * @param $id
     * @return \Illuminate\View\View
     */
    public function getFriends($id)
    {
        $user = User::where('id', '=', $id)->first();
        $show_profile = $this->profileViewerIsYourFriend($id) || Auth::user()->id === intval($id);
        $request_pending = ($show_profile) ? false : $this->friendRequestPending($id);
        $friends = $this->getAllFriends($id);

        return view('profile.profile_friends')
            ->with('user', $user)
            ->with('show_profile', $show_profile)
            ->with('request_pending', $request_pending)
            ->with('friends', $friends);
    }

    /**
     * Check if profile visiting user if friend
     * @param $id
     * @return mixed
     */
    public function profileViewerIsYourFriend($id)
    {
        return Friends::where('user', '=', $id)->where('friend', '=', Auth::user()->id)->count();
    }

    /**
     * Check if friend request is pending
     * @param $id
     * @return mixed
     */
    public function friendRequestPending($id)
    {
        return FriendRequests::where('from', '=', Auth::user()->id)->where('to', '=', $id)->count();
    }

    /**
     * Get all friends
     * @param $id
     * @return mixed
     */
    public function getAllFriends($id)
    {
        return Friends::where('user', '=', $id)->get();
    }

    /**
     * Create a new friend request
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addFriendRequest(Request $request)
    {
        $from = Auth::user()->id;
        $for = $request->user_id;

        $friend_request = new FriendRequests();
        $friend_request->from = $from;
        $friend_request->to = $for;

        if ($friend_request->save()) {
            return response()->json([
                'created' => 'true'
            ]);
        }
    }

    /**
     * Remove existing friend request
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeFriendRequest(Request $request)
    {
        $from = Auth::user()->id;
        $for = $request->user_id;

        $friend_request = FriendRequests::where('from', '=', $from)->where('to', '=', $for);

        if ($friend_request->delete()) {
            return response()->json([
                'removed' => 'true'
            ]);
        }
    }

    /**
     * Delete friend request
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteFriendRequest(Request $request)
    {
        $friend_request = FriendRequests::where('id', '=', $request->request_id);

        if ($friend_request->delete()) {
            return response()->json([
                'removed' => 'true'
            ]);
        }
    }

    /**
     * Accept friend request
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function acceptFriendRequest(Request $request)
    {
        $friend_request = FriendRequests::where('id', '=', $request->request_id)->first();

        if ($this->createFriendConnection($friend_request->from, $friend_request->to)) {
            if ($friend_request->delete()) {
                return response()->json([
                    'accepted' => 'true'
                ]);
            }
        }
    }

    /**
     * Remove friend
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeFriend(Request $request)
    {
        $connection_1 = Friends::where('user', '=', $request->user_id)->where('friend', '=', Auth::user()->id);
        $connection_2 = Friends::where('user', '=', Auth::user()->id)->where('friend', '=', $request->user_id);

        if ($connection_1->delete() && $connection_2->delete()) {
            return response()->json([
                'removed' => 'true'
            ]);
        }
    }

    /**
     * Create a new friends connection
     * @param $from
     * @param $for
     * @return bool
     */
    public function createFriendConnection($from, $for)
    {

        $connection_1 = new Friends();
        $connection_1->user = $from;
        $connection_1->friend = $for;

        $connection_2 = new Friends();
        $connection_2->user = $for;
        $connection_2->friend = $from;

        if ($connection_1->save() && $connection_2->save()) {
            return true;
        }

        return false;
    }

    /**
     * Show settings
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getProfileSettings()
    {
        return view('profile.profile_settings');
    }

    /**
     * Update profile settings
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function updateProfileSettings(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'firstName' => 'required',
            'lastName' => 'required',
            'gender' => 'required'
        ]);

        if ($validator->passes()) {
            if (User::where('id', '=', Auth::user()->id)->update([
                'first_name' => $request->firstName,
                'last_name' => $request->lastName,
                'gender' => $request->gender
            ])
            ) {
                $request->session()->flash('message', 'Profile settings updated');
                return redirect('/user/settings');
            }
        } else {
            return redirect('/user/settings#profile')->withErrors($validator)->withInput();
        }
    }

    /**
     * Update user password
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'old_password' => 'required',
            'password' => 'required',
        ]);

        if ($validator->passes()) {
            if ($this->isValidOldPassword($request->old_password)) {
                if (User::where('id', '=', Auth::user()->id)->update([
                    'password' => bcrypt($request->password)
                ])
                ) {
                    $request->session()->flash('password_message', 'Password Updated');
                    return redirect('/user/settings#password');
                }
            }
            $request->session()->flash('password_message', 'Invalid Old password');
            return redirect('/user/settings#password');

        } else {
            return redirect('/user/settings#password')->withErrors($validator)->withInput();
        }
    }

    /**
     * Check if older password is valid
     * @param $password
     * @return mixed
     */
    public function isValidOldPassword($password)
    {
        return Hash::check($password, Auth::user()->password);
    }

    /**
     * Update profile picture
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function updatePicture(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'profile_pic' => 'required|file|mimes:jpeg,bmp,png,jpg'
        ]);

        if ($validator->passes()) {
            if ($this->userHaveDefaultProfileImage()) {
                return $this->uploadProfilePic($request);
            }else{
                if($this->deleteCurrentProfilePic(Auth::user()->image_uri)){
                    return $this->uploadProfilePic($request);
                }
            }
        } else {
            return redirect('/user/settings')->withErrors($validator)->withInput();
        }
    }

    /**
     * Upload profile picture
     * @param $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    protected function uploadProfilePic($request){
        $file = $request->file('profile_pic');
        $ext = $file->extension();
        $path = '/profile_pic/'.str_random(10).'.'.$ext;

        if(Storage::disk('public')->put($path,  File::get($file))){
            $user_update = User::where('id','=', Auth::user()->id)->update([
                'image_uri'  =>  $path
            ]);

            if($user_update){
                $request->session()->flash('picture_message', 'Profile photo updated');
                return redirect('/user/settings');
            }
        }
    }

    /**
     * Delete profile picture
     * @param $image_uri
     * @return bool
     */
    protected function deleteCurrentProfilePic($image_uri){
        $delete = Storage::disk('public')->delete($image_uri);

        $path = (Auth::user()->gender === 'male') ? '/default/img/default_img_male.jpg' : '/default/img/default_img_female';

        $user_update = User::where('id','=', Auth::user()->id)->update([
            'image_uri' => $path
        ]);

        return ($delete && $user_update);
    }

    /**
     * Check if current profile picture is default
     * @return bool
     */
    protected function userHaveDefaultProfileImage()
    {
        return Auth::user()->image_uri === '/default/img/default_img_male.jpg' || Auth::user()->image_uri === '/default/img/default_img_female.jpg';
    }
}