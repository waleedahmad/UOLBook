<?php

namespace App\Http\Controllers;

use App\Models\ClassAnnouncements;
use App\Models\Classes;
use App\Models\ClassJoin;
use App\Models\Discussion;
use App\Models\DiscussionReply;
use App\Models\FileUploads;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Validator;

use App\Http\Requests;

class TeacherController extends Controller
{
    /**
     * Show teachers dashboard
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getTeacherDashboard(){
        $classes = Classes::where('teacher_id', '=', Auth::user()->id)->get();
        return view('teachers.dashboard')->with('classes', $classes);
    }

    /**
     * Get All Teacher Classes
     * @return mixed
     */
    protected function getAllClasses(){
        return Classes::where('teacher_id', '=', Auth::user()->id)->get();
    }

    /**
     * Show add class form
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showAddClassForm(){
        $classes = Classes::where('teacher_id', '=', Auth::user()->id)->get();
        return view('teachers.add_class')->with('classes', $classes);
    }

    /**
     * Show class
     * @param $id
     * @return \Illuminate\View\View
     */
    public function showClass($id){
        $discussions = Discussion::where('class_id', '=', $id)->orderBy('id', 'DESC')->paginate(10);
        $class = Classes::where('id','=',$id)->first();
        return view('teachers.dashboard')
            ->with('classes', $this->getAllClasses())
            ->with('class', $class)
            ->with('is_student', $this->userIsAStudent($id))
            ->with('request_pending', $this->joinRequestPending($id))
            ->with('discussions', $discussions);
    }

    public function getDiscussion($id, $d_id){
        $discussion = Discussion::where('class_id','=',$id)->where('id', '=', $d_id)->first();
        $class = Classes::where('id','=',$id)->first();
        return view('teachers.discussion')
            ->with('classes', $this->getAllClasses())
            ->with('class', $class)
            ->with('is_student', $this->userIsAStudent($id))
            ->with('request_pending', $this->joinRequestPending($id))
            ->with('discussion', $discussion)
            ->with('d_id', $d_id);
    }

    public function showClassAnnouncements($id){
        $class = Classes::where('id','=',$id)->first();
        $announcements = ClassAnnouncements::where('class_id', '=', $id)->orderBy('id', 'DESC')->get();
        return view('teachers.announcements')
            ->with('classes', $this->getAllClasses())
            ->with('class', $class)
            ->with('is_student', $this->userIsAStudent($id))
            ->with('request_pending', $this->joinRequestPending($id))
            ->with('announcements', $announcements);
    }

    /**
     * Show Student Join Requests
     * @param $id
     * @return \Illuminate\View\View
     */
    public function showJoinRequests($id){
        $class = Classes::where('id','=',$id)->first();
        $requests = ClassJoin::where('class_id','=', $id)->get();
        return view('teachers.join_requests')
            ->with('classes', $this->getAllClasses())
            ->with('class', $class)
            ->with('requests', $requests);
    }

    /**
     * Show Class Uploads
     * @param $id
     * @return \Illuminate\View\View
     */
    public function showClassUploads($id){
        $class = Classes::where('id','=',$id)->first();
        $uploads = FileUploads::where('class_id', '=', $id)->orderBy('id', 'DESC')->get();
        return view('teachers.uploads')
            ->with('classes', $this->getAllClasses())
            ->with('class', $class)
            ->with('is_student', $this->userIsAStudent($id))
            ->with('request_pending', $this->joinRequestPending($id))
            ->with('uploads', $uploads);
    }

    /**
     * Show Class Students
     * @param $id
     * @return \Illuminate\View\View
     */
    public function showClassStudents($id){
        $students = Student::where('class_id', '=', $id)->get();
        $class = Classes::where('id','=',$id)->first();
        return view('teachers.students')
            ->with('classes', $this->getAllClasses())
            ->with('class', $class)
            ->with('students', $students);
    }

    /**
     * Add new Class
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function  saveClass(Request $request){
        $validator = Validator::make($request->all(), [
            'subject_code'  =>  'required',
            'subject_name'  =>  'required',
            'subject_semester'  => 'required'
        ]);

        if($validator->passes()){
            $subject_name = $request->subject_name;
            $subject_code = $request->subject_code;
            $subject_semester = $request->subject_semester;

            $class = new Classes();

            $class->subject_name = $subject_name;
            $class->subject_code = $subject_code;
            $class->subject_semester = $subject_semester;
            $class->teacher_id = Auth::user()->id;

            if($class->save()){
                return redirect('/class/'.$class->id);
            }
        }else{
            return redirect('/teacher/addClass')->withErrors($validator);
        }
    }

    /**
     * Make Class Announcements
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function makeClassAnnouncement(Request $request){
        $class_id = $request->id;

        $validator = Validator::make($request->all(), [
            'announcement_title' => 'required',
            'announcement'   =>  'required'
        ]);

        if ($validator->passes()) {
            $announcement = new ClassAnnouncements();
            $announcement->title = $request->announcement_title;
            $announcement->announcement = $request->announcement;
            $announcement->class_id = $class_id;
            $announcement->user_id = Auth::user()->id;

            if($announcement->save()){
                $request->session()->flash('message', 'Announcement created');
                return redirect('/class/'.$class_id.'/announcements');
            }
        } else {
            return redirect('/class/'.$class_id.'/announcements')->withErrors($validator)->withInput();
        }
    }

    /**
     * Remove Class Announcements
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeClassAnnouncement(Request $request){
        $id = $request->id;

        $announcement = ClassAnnouncements::where('id','=', $id);

        if($announcement->delete()){
            return response()->json([
                'deleted'   => true
            ]);
        }
    }

    /**
     * Post Class Discussions
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function postDiscussion(Request $request){
        $class_id = $request->id;

        $validator = Validator::make($request->all(), [
            'discussion_title' => 'required',
            'discussion'   =>  'required'
        ]);

        if ($validator->passes()) {

            $dicussion = new Discussion();
            $dicussion->title = $request->discussion_title;
            $dicussion->body = $request->discussion;
            $dicussion->user_id = Auth::user()->id;
            $dicussion->class_id = $class_id;

            if($dicussion->save()){
                $request->session()->flash('message', 'Discussion Posted');
                return redirect('/class/'.$class_id);
            }
        } else {
            return redirect('/class/'.$class_id.'')->withErrors($validator)->withInput();
        }
    }

    /**
     * Delete discussion
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteDiscussion(Request $request){
        $id = $request->id;
        if(Discussion::where('id', '=', $id)->delete()){
            return response()->json([
                'deleted'   =>  true
            ]);
        }
    }

    /**
     * Edit discussion
     * @param $id
     * @param $d_id
     * @return \Illuminate\View\View
     */
    public function editDiscussion($id, $d_id){
        $discussion = Discussion::where('class_id','=',$id)->where('id', '=', $d_id)->first();
        $class = Classes::where('id','=',$id)->first();
        return view('teachers.edit_discussion')
            ->with('classes', $this->getAllClasses())
            ->with('class', $class)
            ->with('is_student', $this->userIsAStudent($id))
            ->with('request_pending', $this->joinRequestPending($id))
            ->with('discussion', $discussion)
            ->with('d_id', $d_id);
    }

    /**
     * Update discussion
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function updateDiscussion($id, Request $request){
        $discussion_id = $request->discussion_id;
        $title = $request->discussion_title;
        $body = $request->discussion;

        $validator = Validator::make($request->all(), [
            'discussion_title' => 'required',
            'discussion'   =>  'required'
        ]);

        if ($validator->passes()) {


            $discussion = Discussion::where('id','=', $discussion_id);

            if($discussion->update([
                'title' =>  $title,
                'body'  =>  $body
            ])){
                return redirect('/class/'.$id.'/discussions/'.$discussion_id);
            }
        } else {
            return redirect('/class/'.$id.'/discussion/'.$discussion_id.'/edit')->withErrors($validator)->withInput();
        }
    }


    /**
     * Post discussion reply
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function postDiscussionReply(Request $request){
        $discussion_id = $request->discussion_id;
        $user_id = Auth::user()->id;
        $reply = $request->reply;
        $class_id = $request->class_id;

        $validator = Validator::make($request->all(), [
            'reply'   =>  'required'
        ]);

        if ($validator->passes()) {

            $discussion_reply = new DiscussionReply();
            $discussion_reply->user_id = $user_id;
            $discussion_reply->discussions_id = $discussion_id;
            $discussion_reply->reply  = $reply;

            if($discussion_reply->save()){
                return redirect('/class/'.$class_id.'/discussions/'.$discussion_id);
            }
        } else {
            return redirect('/class/'.$class_id.'/discussions/'.$discussion_id)->withErrors($validator)->withInput();
        }
    }

    /**
     * Delete discussion reply
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteDiscussionReply(Request $request){
        $id = $request->id;

        if(DiscussionReply::where('id','=', $id)->delete()){
            return response()->json([
                'deleted'   =>  true
            ]);
        }
    }

    /**
     * Update discussion reply
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateDiscussionReply(Request $request){
        $id = $request->id;
        $text = $request->text;

        $reply = DiscussionReply::where('id','=', $id);

        if($reply->update([
            'reply' =>  $text
        ])){
            return response()->json([
                'updated'   =>  true
            ]);
        }
    }

    /**
     * Upload Course Material
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function uploadMaterial(Request $request){
        $class_id = $request->id;

        $validator = Validator::make($request->all(), [
            'material_file' => 'required|file',
            'caption'   =>  'required'
        ]);

        if ($validator->passes()) {
            $teacher_id = Auth::user()->id;
            $caption = $request->caption;
            $file = $request->file('material_file');
            $ext = $file->extension();
            $path = '/uploads/'.str_random(10).'.'.$ext;

            if(Storage::disk('public')->put($path,  File::get($file))){
                $upload = new FileUploads();
                $upload->class_id = $class_id;
                $upload->user_id = $teacher_id;
                $upload->file_uri = $path;
                $upload->caption = $caption;

                if($upload->save()){
                    $request->session()->flash('material_message', 'File Uploaded');
                    return redirect('/class/'.$class_id.'/material');
                }
            }
        } else {
            return redirect('/class/'.$class_id.'/material')->withErrors($validator)->withInput();
        }
    }

    /**
     * Remove Course Material
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeUploadMaterial(Request $request){
        $upload = FileUploads::where('id','=',$request->id)->first();

        $delete = Storage::disk('public')->delete($upload->file_uri);
        if($delete){
            if($upload->delete()){
                return response()->json([
                    'deleted'   => true
                ]);
            }
        }
    }

    /**
     * Accept Join Requests
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function acceptJoinRequest(Request $request){
        $id = $request->id;

        $join_request = ClassJoin::where('id','=', $id)->first();

        $new_students = new Student();
        $new_students->user_id = $join_request->user_id;
        $new_students->class_id = $join_request->class_id;

        if($new_students->save()){
            if($join_request->delete()){
                return response()->json([
                    'accepted'  => true
                ]);
            }
        }
    }

    /**
     * Disapprove Join Requests
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function disapproveJoinRequest(Request $request){
        $id = $request->id;
        $join_request = ClassJoin::where('id','=', $id);

        if($join_request->delete()){
            return response()->json([
                'disapproved'  => true
            ]);
        }
    }

    /**
     * Remove Class Students
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeStudent(Request $request){
        $id = $request->id;
        $student = Student::where('id','=', $id);

        if($student->delete()){
            return response()->json([
                'removed'   =>  true,
            ]);
        }
    }

    /**
     * Request Class Join
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function joinRequest(Request $request){
        $id = $request->id;
        $user_id = Auth::user()->id;

        $join_request = new ClassJoin();
        $join_request->user_id = $user_id;
        $join_request->class_id = $id;

        if($join_request->save()){
            return response()->json([
                'created'   =>  true,
            ]);
        }
    }

    /**
     * Cancel Class Join Request
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function cancelJoinRequest(Request $request){
        $id = $request->id;
        $user_id = Auth::user()->id;

        $join_request = ClassJoin::where('user_id', $user_id)->where('class_id', $id);

        if($join_request->delete()){
            return response()->json([
                'deleted'   =>  true,
            ]);
        }
    }

    /**
     * Check if visiting student is enrolled
     * @param $class_id
     * @return mixed
     */
    protected function userIsAStudent($class_id){
        return Student::where('user_id','=', Auth::user()->id)->where('class_id','=', $class_id)->count();
    }

    /**
     * Check if Join Request is Pending
     * @param $class_id
     * @return mixed
     */
    protected function joinRequestPending($class_id){
        return ClassJoin::where('user_id','=', Auth::user()->id)->where('class_id','=',$class_id)->count();
    }

}
