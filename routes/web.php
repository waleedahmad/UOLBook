<?php

Route::group(['middleware'  =>  ['auth']], function(){
    Route::get('/', 'HomeController@index');

    Route::get('/logout', 'AuthController@logout');
});

Route::group(['middleware'  =>  ['guest']], function(){
    Route::get('/login', 'AuthController@getLogin');
    Route::get('/register', 'AuthController@getRegister');

    Route::post('/login', 'AuthController@postLogin');
    Route::post('/register', 'AuthController@postRegister');
});