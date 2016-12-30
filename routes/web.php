<?php

Route::group(['middleware'  =>  ['auth', 'isNotVerified' , 'isAdmin']], function(){
    Route::get('/', 'FeedController@index');
    Route::get('/post/{id}', 'FeedController@viewPost');
    Route::get('/teacher/addClass', 'TeacherController@showAddClassForm');
    Route::post('/teacher/saveClass', 'TeacherController@saveClass');
    Route::get('/teacher/class/{id}', 'TeacherController@showClass');

    Route::get('/profile/{id}', 'ProfileController@getUserProfile');
    Route::post('/posts/text/create', 'FeedController@createTextPost');
    Route::post('/posts/file/create', 'FeedController@createFilePost');
    Route::post('/comment/create', 'FeedController@createComment');

});

Route::group(['middleware'   =>  ['auth', 'isNotAdmin']], function(){
    Route::get('/admin', 'AdminController@getIndex');
    Route::get('/admin/users', 'AdminController@getUsers');
    Route::get('/admin/messages', 'AdminController@getMessages');
    Route::get('/admin/societies', 'AdminController@getAllSocieties');
    Route::get('/admin/society/requests', 'AdminController@getSocietyRequests');
    Route::post('/admin/approve', 'AdminController@approveUser');
    Route::get('/admin/{type}', 'AdminController@getFilteredRequests');
});

Route::group(['middleware'   =>  ['auth', 'isVerified']], function(){
    Route::get('/verify/student', 'AuthController@getStudentVerification');
    Route::get('/verify/teacher', 'AuthController@getTeacherVerification');

    Route::post('/verify/student', 'AuthController@verifyStudent');
    Route::post('/verify/teacher', 'AuthController@verifyTeacher');
});

Route::group(['middleware'  =>  ['guest']], function(){
    Route::get('/login', 'AuthController@getLogin');
    Route::get('/register', 'AuthController@getRegister');

    Route::post('/login', 'AuthController@postLogin');
    Route::post('/register', 'AuthController@postRegister');
});

Route::get('/logout', 'AuthController@logout');