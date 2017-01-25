<?php

namespace App\Http\Controllers;

use App\Models\Posts;
use App\Models\Society;
use App\Models\SocietyMember;
use App\Models\SocietyRequest;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Validator;
use App\Http\Requests;
use Illuminate\Support\Facades\Auth;

class SocietyController extends Controller
{
    /**
     * Show societies
     * @return \Illuminate\View\View
     */
    public function showAllSocieties()
    {
        $societies = Society::where('user_id', '=', Auth::user()->id)->get();
        $members = Society::whereIn('id', $this->getUserSocieties())->get();
        $suggestions = Society::whereNotIn('id', $this->getUserSocieties())->where('user_id', '!=', Auth::user()->id)->get();
        return view('societies')
            ->with('societies', $societies)
            ->with('suggestions', $suggestions)
            ->with('members', $members);
    }

    /**
     * User societies
     * @return mixed
     */
    public function getUserSocieties()
    {
        return SocietyMember::where('user_id', '=', Auth::user()->id)->pluck('society_id');
    }

    /**
     * Create society form
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getSocietyForm()
    {
        return view('societies.create_society');
    }

    /**
     * Create society
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function createSociety(Request $request)
    {

        $rules = [
            'society_name' => 'required',
            'society_type' => 'required'
        ];

        if ($request->hasFile('display_image')) {
            $rules['display_image'] = 'required|file|mimes:jpeg,bmp,png,jpg';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->passes()) {
            $society = new Society();
            $society->name = $request->society_name;
            $society->type = $request->society_type;
            $society->verified = false;
            $society->image_uri = $this->storageImageAndReturnImageURI($request);
            $society->user_id = Auth::user()->id;

            if ($society->save()) {
                return redirect('/societies/all');
            }

        } else {
            return redirect('/societies/create')->withErrors($validator)->withInput();
        }
    }

    /**
     * Storage soceity header image and return URI
     * @param $request
     * @return string
     */
    private function storageImageAndReturnImageURI($request)
    {
        if ($request->hasFile('display_image')) {
            $file = $request->file('display_image');
            $ext = $file->extension();
            $path = '/society/' . str_random(10) . '.' . $ext;

            if (Storage::disk('public')->put($path, File::get($file))) {
                return $path;
            }
        } else {
            return '/default/img/society_icon.png';
        }
    }

    /**
     * Show society
     * @param $id
     * @return \Illuminate\View\View
     */
    public function getSociety($id)
    {
        $society = Society::where('id', '=', $id)->first();
        $posts = Posts::where('society_id', '=', $id)->paginate(10);
        return view('societies.feed')
            ->with('society', $society)
            ->with('posts', $posts)
            ->with('isVerified', $this->societyIsVerified($id))
            ->with('isMember', $this->isSocietyMember($id))
            ->with('requestPending', $this->societyRequestPending($id));

    }

    /**
     * Show society settings
     * @param $id
     * @return \Illuminate\View\View
     */
    public function getSocietySettings($id)
    {
        $society = Society::where('id', '=', $id)->first();
        return view('societies.settings')
            ->with('society', $society)
            ->with('isVerified', $this->societyIsVerified($id))
            ->with('isMember', $this->isSocietyMember($id))
            ->with('requestPending', $this->societyRequestPending($id));;
    }

    /**
     * Show society requests
     * @param $id
     * @return \Illuminate\View\View
     */
    public function getSocietyRequests($id)
    {
        $society = Society::where('id', '=', $id)->first();
        $requests = SocietyRequest::where('society_id', '=', $id)->get();
        return view('societies.requests')
            ->with('society', $society)
            ->with('isVerified', $this->societyIsVerified($id))
            ->with('isMember', $this->isSocietyMember($id))
            ->with('requestPending', $this->societyRequestPending($id))
            ->with('requests', $requests);
    }

    /**
     * Show society members
     * @param $id
     * @return \Illuminate\View\View
     */
    public function societyMembers($id)
    {
        $society = Society::where('id', '=', $id)->first();
        $members = SocietyMember::where('society_id', '=', $id)->get();
        return view('societies.members')
            ->with('society', $society)
            ->with('isVerified', $this->societyIsVerified($id))
            ->with('isMember', $this->isSocietyMember($id))
            ->with('requestPending', $this->societyRequestPending($id))
            ->with('members', $members);
    }

    /**
     * Check if society is verified
     * @param $id
     * @return mixed
     */
    public function societyIsVerified($id)
    {
        return Society::where('id', '=', $id)->first()->verified;
    }

    /**
     * Check if user is society member
     * @param $id
     * @return mixed
     */
    private function isSocietyMember($id)
    {
        return SocietyMember::where('society_id', '=', $id)->where('user_id', '=', Auth::user()->id)->count();
    }

    /**
     * Check if user soceity join request is pending
     * @param $id
     * @return mixed
     */
    private function societyRequestPending($id)
    {
        return SocietyRequest::where('user_id', '=', AUth::user()->id)->where('society_id', '=', $id)->count();
    }

    /**
     * Create join request
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function joinRequest(Request $request)
    {

        $society_request = new SocietyRequest();
        $society_request->user_id = Auth::user()->id;
        $society_request->society_id = $request->id;

        if ($society_request->save()) {
            return response()->json([
                'created' => true
            ]);
        }
    }

    /**
     * Cancel join request
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function cancelJoinRequests(Request $request)
    {
        $society_request = SocietyRequest::where('user_id', '=', Auth::user()->id)->where('society_id', '=', $request->id);

        if ($society_request->delete()) {
            return response()->json([
                'canceled' => true
            ]);
        }
    }

    /**
     * Approve join request
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function acceptJoinRequests(Request $request)
    {
        $soc_request = SocietyRequest::where('id', '=', $request->id)->first();

        $member = new SocietyMember();
        $member->user_id = $soc_request->user_id;
        $member->society_id = $soc_request->society_id;

        if ($member->save() && $soc_request->delete()) {
            return response()->json([
                'accepted' => true
            ]);
        }
    }

    /**
     *  Disapprove join request
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function disApproveJoinRequest(Request $request)
    {
        $soc_request = SocietyRequest::where('id', '=', $request->id)->first();

        if ($soc_request->delete()) {
            return response()->json([
                'disapproved' => true
            ]);
        }
    }

    /**
     * Remove society member
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeUser(Request $request)
    {
        $soc_request = SocietyMember::where('id', '=', $request->id)->first();

        if ($soc_request->delete()) {
            return response()->json([
                'removed' => true
            ]);
        }
    }

}
