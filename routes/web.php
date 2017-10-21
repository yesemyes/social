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
//Route::post('/account/update/{id}', 'HomeController@accountUpdate');

Route::get('facebook/login/{wp?}', 'Auth\OauthController@loginWithFacebook');
Route::get('google/login/{wp?}', 'Auth\OauthController@loginWithGoogle');
Route::get('twitter/login/{wp?}', 'Auth\OauthController@loginWithTwitter');
Route::get('linkedin/login/{wp?}', 'Auth\OauthController@loginWithLinkedin');
Route::get('instagram/login/{wp?}', 'Auth\OauthController@loginWithInstagram');


//Route::get('check/email/{id}/{url}', 'Auth\OauthController@checkEmail');

//Route::post('/setPass/{id}', 'Auth\OauthController@setPass');

Route::post('/account/delete/{id}', 'Auth\OauthController@destroy');

/* API */
Route::group(['middleware' => 'api', 'prefix' => 'api'], function () {
    Route::post('login', 'APIController@login');
    Route::post('register', 'APIController@register');
    Route::group(['middleware' => 'jwt-auth'], function () {
	    Route::post('users', 'APIController@get_oauth_users');
	    Route::post('account/delete', 'APIController@destroy');
	    Route::post('twitter', 'SocialController@twitter');
	    Route::post('facebook', 'SocialController@facebook');
	    Route::post('linkedin', 'SocialController@linkedin');
    });
});
