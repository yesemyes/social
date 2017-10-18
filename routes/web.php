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
Route::get('/privacy', 'HomeController@policy');
Route::get('/account', 'HomeController@account');
Route::post('/account/update/{id}', 'HomeController@accountUpdate');

Route::get('facebook/login/{wp?}', 'Auth\OauthController@loginWithFacebook');
Route::get('google/login/{wp?}', 'Auth\OauthController@loginWithGoogle');
Route::get('twitter/login/{wp?}', 'Auth\OauthController@loginWithTwitter');
Route::get('linkedin/login/{wp?}', 'Auth\OauthController@loginWithLinkedin');

//Route::get('twitt', 'APIController@twitter');
//Route::post('tweet', ['as'=>'post.tweet','uses'=>'APIController@tweet']);

Route::get('check/email/{id}/{url}', 'Auth\OauthController@checkEmail');

Route::post('/setPass/{id}', 'Auth\OauthController@setPass');

Route::post('/account/delete/{id}', 'Auth\OauthController@destroy');

/* API */
Route::group(['middleware' => 'api', 'prefix' => 'api'], function () {
    Route::post('login', 'APIController@login');
    Route::group(['middleware' => 'jwt-auth'], function () {
	    Route::get('users', 'APIController@get_oauth_users');
	    Route::post('account/delete', 'APIController@destroy');
	    Route::post('twitter', 'APIController@tweet');
	    Route::post('facebook', 'APIController@facebook');
    });
});
