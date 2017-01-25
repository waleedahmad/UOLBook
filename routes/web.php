<?php

Route::group(['middleware' => ['auth', 'isNotVerified', 'isAdmin', 'isTeacher', 'navbarMiddleware', 'sideBarMiddleware']], function () {

    /**
     * Feed Routes
     */
    Route::get('/', 'FeedController@index');
    Route::get('/post/{id}', 'FeedController@viewPost');

    /**
     * Post CRUD Routes
     */
    Route::post('/posts/text/create', 'FeedController@createTextPost');
    Route::post('/posts/file/create', 'FeedController@createFilePost');
    Route::post('/posts/delete', 'FeedController@deletePost');
    Route::post('/posts/edit', 'FeedController@editPost');
    Route::post('/likes/like', 'FeedController@likePost');
    Route::post('/likes/unlike', 'FeedController@unlikePost');

    /**
     * Comments CRUD Routes
     */
    Route::post('/comment/create', 'FeedController@createComment');
    Route::post('/comment/delete', 'FeedController@deleteComment');
    Route::post('/comment/update', 'FeedController@updateComment');

    /**
     * Profile Routes
     */
    Route::get('/profile/{id}', 'ProfileController@getUserProfile');
    Route::get('/profile/{id}/friends', 'ProfileController@getFriends');

    /**
     * Friend Requests Routes
     */
    Route::post('/user/friend/request', 'ProfileController@addFriendRequest');
    Route::post('/user/request/remove', 'ProfileController@removeFriendRequest');
    Route::post('/user/friend/remove', 'ProfileController@removeFriend');
    Route::post('/user/friend/request/remove', 'ProfileController@deleteFriendRequest');
    Route::post('/user/friend/request/accept', 'ProfileController@acceptFriendRequest');

    /**
     * Notification Routes
     */
    Route::post('/notification/read', 'NotificationController@readNotification');

    /**
     * Society, Classes Links
     */

    Route::get('/classes/all', 'ClassController@showAllClasses');
    Route::get('/societies/all', 'SocietyController@showAllSocieties');
    Route::get('/societies/create', 'SocietyController@getSocietyForm');
    Route::post('/societies/create', 'SocietyController@createSociety');
    Route::post('/society/request/join', 'SocietyController@joinRequest');
    Route::post('/society/request/cancel', 'SocietyController@cancelJoinRequests');
    Route::post('/society/request/accept', 'SocietyController@acceptJoinRequests');
    Route::post('/society/request/disapprove', 'SocietyController@disApproveJoinRequest');
    Route::post('/society/member/remove', 'SocietyController@removeUser');

    Route::get('/society/{id}', 'SocietyController@getSociety');
    Route::get('/society/{id}/settings', 'SocietyController@getSocietySettings');
    Route::get('/society/{id}/requests', 'SocietyController@getSocietyRequests');
    Route::get('/society/{id}/members', 'SocietyController@societyMembers');
});

Route::group(['middleware'  =>  ['auth', 'isNotVerified', 'isAdmin', 'isNotTeacher']], function(){
    Route::get('/dashboard', 'TeacherController@getTeacherDashboard');
    Route::get('/addClass', 'TeacherController@showAddClassForm');
    Route::post('/saveClass', 'TeacherController@saveClass');
    Route::get('/class/{id}/requests', 'TeacherController@showJoinRequests');
    Route::get('/class/{id}/students', 'TeacherController@showClassStudents');
    Route::post('/class/request/accept', 'TeacherController@acceptJoinRequest');
    Route::post('/class/request/disapprove', 'TeacherController@disapproveJoinRequest');
    Route::post('/class/remove/student', 'TeacherController@removeStudent');
    Route::post('/class/upload', 'TeacherController@uploadMaterial');
    Route::post('/class/upload/remove', 'TeacherController@removeUploadMaterial');
    Route::post('/class/announcement', 'TeacherController@makeClassAnnouncement');
    Route::post('/class/announcement/remove', 'TeacherController@removeClassAnnouncement');
});

Route::group(['middleware'  =>  ['auth', 'isNotVerified', 'classesMiddleware', 'sideBarMiddleware', 'navbarMiddleware']], function(){
    Route::get('/class/{id}', 'TeacherController@showClass');
    Route::get('/class/{id}/announcements', 'TeacherController@showClassAnnouncements');
    Route::get('/class/{id}/material', 'TeacherController@showClassUploads');

    Route::post('/class/{id}/discussion/post', 'TeacherController@postDiscussion');
    Route::delete('/class/{id}/discussion/delete', 'TeacherController@deleteDiscussion');
    Route::get('/class/{id}/discussion/{d_id}/edit', 'TeacherController@editDiscussion');
    Route::post('/class/{id}/discussion/update', 'TeacherController@updateDiscussion');

    Route::get('/class/{id}/discussions/{d_id}', 'TeacherController@getDiscussion');
    Route::post('/class/{id}/discussion/reply', 'TeacherController@postDiscussionReply');
    Route::delete('/class/{id}/discussion/reply/delete', 'TeacherController@deleteDiscussionReply');
    Route::put('/class/{id}/discussion/reply/edit', 'TeacherController@updateDiscussionReply');

    Route::post('/class/request/join', 'TeacherController@joinRequest');
    Route::post('/class/request/cancel', 'TeacherController@cancelJoinRequest');
    Route::post('/class/leave', 'TeacherController@leaveClass');

});

Route::group(['middleware' => ['auth', 'isNotAdmin']], function () {

    /**
     * Admin Routes
     */
    Route::get('/admin', 'AdminController@getIndex');
    Route::get('/admin/users', 'AdminController@getUsers');
    Route::get('/admin/societies', 'AdminController@getAllSocieties');
    Route::get('/admin/classes', 'AdminController@getAllClasses');
    Route::get('/admin/society/requests', 'AdminController@getSocietyRequests');
    Route::post('/admin/society/approve', 'AdminController@approveSociety');
    Route::post('/admin/society/disapprove', 'AdminController@disApproveSociety');
    Route::post('/admin/approve', 'AdminController@approveUser');
    Route::post('/admin/disapprove', 'AdminController@disapproveUser');
    Route::get('/admin/{type}', 'AdminController@getFilteredRequests');
    Route::delete('/admin/class/delete', 'AdminController@deleteClass');
    Route::delete('/admin/user/delete', 'AdminController@deleteUser');

});

Route::group(['middleware' => ['auth', 'isVerified']], function () {

    /**
     * Verification Routes
     */
    Route::get('/verify/student', 'AuthController@getStudentVerification');
    Route::get('/verify/teacher', 'AuthController@getTeacherVerification');
    Route::post('/verify/student', 'AuthController@verifyStudent');
    Route::post('/verify/teacher', 'AuthController@verifyTeacher');
});

Route::group(['middleware' => ['auth', 'isNotVerified', 'navbarMiddleware']], function () {
    Route::get('/user/settings', 'ProfileController@getProfileSettings');
    Route::post('/user/settings/update', 'ProfileController@updateProfileSettings');
    Route::post('/user/settings/update/password', 'ProfileController@updatePassword');
    Route::post('/user/settings/update/picture', 'ProfileController@updatePicture');
});



Route::group(['middleware' => ['guest']], function () {

    /**
     * Authentication Routes
     */
    Route::get('/login', 'AuthController@getLogin');
    Route::get('/register', 'AuthController@getRegister');

    Route::post('/login', 'AuthController@postLogin');
    Route::post('/register', 'AuthController@postRegister');
});

Route::get('/logout', 'AuthController@logout');