<?php

Route::group(['middleware'  =>  ['auth', 'isVerified']], function(){
    Route::get('/', 'FeedController@index');
    Route::get('/teacher/addClass', 'TeacherController@showAddClassForm');
    Route::post('/teacher/saveClass', 'TeacherController@saveClass');
    Route::get('/teacher/class/{id}', 'TeacherController@showClass');


    Route::get('/admin', 'AdminController@getIndex');
    Route::get('/admin/users', 'AdminController@getUsers');
    Route::get('/admin/messages', 'AdminController@getMessages');
    Route::post('/admin/approve', 'AdminController@approveUser');
    Route::get('/admin/{type}', 'AdminController@getFilteredRequests');

    Route::get('/profile/{id}', 'ProfileController@getUserProfile');

});

Route::group(['middleware'   =>  ['auth']], function(){
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