<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/


/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::group(['middleware' => 'web'], function () {
    Route::get('login', function () {
        return view('login');
    });
    Route::post('login', 'Auth\AuthController@login');
    Route::get('logout', 'Auth\AuthController@logout');
    Route::get('yiban/auth','HomeController@yibanAuth');
    Route::auth();
    Route::get('/', 'HomeController@mosco');
    Route::get('mosco','HomeController@mosco');
    Route::get('mosco/nav/{now?}/',  'HomeController@mosco');
    Route::get('counsellor', 'HomeController@counsellor');
    Route::post('counsellor', 'Controller@counsellorPost');
    Route::get('mosco/search/{id?}', 'MosController@search');
    Route::get('mosco/show', 'MosController@show');
    Route::get('mosco/output', 'MosController@output');

    Route::get('mosco/output_act', 'MosController@output_act');
    Route::get('mosco/judge/{id?}/', 'MosController@judge');
    Route::post('mosco/update', 'MosController@update');
    Route::get('resetpassword', 'Auth\ChangePasswordController@index');
    Route::post('resetpassword', 'Auth\ChangePasswordController@change');
});
