<?php

Route::group(['middleware' => ['auth', 'isNotVerified', 'isAdmin', 'isTeacher', 'navbarMiddleware', 'sideBarMiddleware']], function () {

    /**
     * Feed Routes
     */
    Route::get('/', 'FeedController@index');
    Route::get('/search', 'FeedController@search');
    Route::get('/post/{id}', 'FeedController@viewPost');

    Route::get('/notifications/all', 'NotificationController@getNotifications');

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
     * Society Routes
     */

    Route::get('/classes/all', 'ClassController@showAllClasses');
    Route::get('/teacher/{id}/classes', 'ClassController@getTeacherClasses');
    Route::get('/teachers/all', 'ClassController@showAllTeachers');
    Route::get('/societies/all', 'SocietyController@showAllSocieties');
    Route::get('/societies/create', 'SocietyController@getSocietyForm');
    Route::post('/societies/create', 'SocietyController@createSociety');
    Route::post('/society/request/join', 'SocietyController@joinRequest');
    Route::post('/society/request/cancel', 'SocietyController@cancelJoinRequests');
    Route::post('/society/request/accept', 'SocietyController@acceptJoinRequests');
    Route::post('/society/request/disapprove', 'SocietyController@disApproveJoinRequest');
    Route::post('/society/member/remove', 'SocietyController@removeUser');
    Route::post('/society/member/leave', 'SocietyController@leaveSociety');

    Route::get('/society/{id}', 'SocietyController@getSociety');
    Route::get('/society/{id}/settings', 'SocietyController@getSocietySettings');
    Route::post('/society/settings/update', 'SocietyController@updateSocietySettings');
    Route::post('/society/settings/cover/update', 'SocietyController@updateSocietyCover');
    Route::post('/society/delete', 'SocietyController@deleteSociety');
    Route::get('/society/{id}/requests', 'SocietyController@getSocietyRequests');
    Route::get('/society/{id}/members', 'SocietyController@societyMembers');

    /**
     * Messages Routes
     */

    Route::get('/messages/all', 'MessagesController@getMessages');
    Route::get('/messages/conversation/exist', 'MessagesController@checkConversation');
    Route::get('/messages/conversation/create', 'MessagesController@createConversation');
    Route::delete('/messages/conversation/', 'MessagesController@deleteConversation');
    Route::get('/message/new/{id}', 'MessagesController@newConversation');
    Route::post('/message/new', 'MessagesController@newMessage');
    Route::get('/user/friends', 'MessagesController@getUserFriends');
    Route::get('/message/{id}', 'MessagesController@renderConversation');
});

Route::group(['middleware'  =>  ['auth', 'isNotVerified', 'isAdmin', 'isNotTeacher']], function(){
    Route::get('/dashboard', 'TeacherController@getTeacherDashboard');
    Route::get('/keys', 'TeacherController@getClassKeys');
    Route::get('/course/sections', 'TeacherController@getCourseSections');
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
    Route::post('/course/find', 'TeacherController@findCourse');
    Route::get('/course/count', 'TeacherController@getStudentCourseCount');

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
    Route::post('/admin/society/delete', 'SocietyController@deleteSociety');

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
    Route::post('/user/settings/update/cover', 'ProfileController@updateCover');

});



Route::group(['middleware' => ['guest']], function () {

    /**
     * Authentication Routes
     */
    Route::get('/login', 'AuthController@getLogin');
    Route::get('/register', 'AuthController@getRegister');

    Route::post('/login', 'AuthController@postLogin');
    Route::post('/register', 'AuthController@postRegister');

    Route::get('/recover/password', 'AuthController@getRecoveryForm');
    Route::post('/recover/password', 'AuthController@recoverPassword');
    Route::get('/reset/password/{token}/{email}', 'AuthController@resetPasswordForm');
    Route::post('/reset/password', 'AuthController@resetPassword');
});

Route::get('/logout', 'AuthController@logout');