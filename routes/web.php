<?php

Route::group(['middleware'  =>  ['auth', 'isVerified']], function(){
    Route::get('/', 'HomeController@index');
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