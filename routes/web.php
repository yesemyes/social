<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index');
Route::get('/account', 'HomeController@account');
Route::post('/account/update/{id}', 'HomeController@accountUpdate');

Route::get('facebook/login/{wp?}', 'Auth\OauthController@loginWithFacebook');
Route::get('google/login', 'Auth\OauthController@loginWithGoogle');
Route::get('twitter/login', 'Auth\OauthController@loginWithTwitter');
Route::get('linkedin/login', 'Auth\OauthController@loginWithLinkedin');

Route::get('check/email/{id}/{url}', 'Auth\OauthController@checkEmail');

Route::post('/setPass/{id}', 'Auth\OauthController@setPass');

Route::post('/account/delete/{id}', 'Auth\OauthController@destroy');



/* API */
Route::group(['middleware' => 'api', 'prefix' => 'api'], function () {
    //Route::post('register', 'APIController@register');
    Route::post('login', 'APIController@login');
    Route::post('twitter/login', 'APIController@login');
    Route::group(['middleware' => 'jwt-auth'], function () {
    	//Route::post('get_user_details', 'APIController@get_user_details');
    	//Route::post('del_user', 'APIController@del_user');
    	//Route::get('twitter/login', 'Auth\OauthController@loginWithTwitter');
    	//Route::get('facebook/login', 'Auth\OauthController@loginWithFacebook');
    });
});
