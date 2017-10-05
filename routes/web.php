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

//Auth::routes();
// Authentication Routes...
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');

// Registration Routes...
Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('register', 'Auth\RegisterController@register');

// Password Reset Routes...
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm');
Route::post('password/reset', 'Auth\ResetPasswordController@reset');


Route::get('/home', 'HomeController@index');
Route::get('/account', 'HomeController@account');
Route::get('/posts', 'HomeController@posts');
Route::post('/account/update/{id}', 'HomeController@accountUpdate');

Route::get('facebook/login/{wp?}', 'Auth\OauthController@loginWithFacebook');
Route::get('google/login/{wp?}', 'Auth\OauthController@loginWithGoogle');
Route::get('twitter/login/{wp?}', 'Auth\OauthController@loginWithTwitter');
Route::get('linkedin/login/{wp?}', 'Auth\OauthController@loginWithLinkedin');

Route::get('check/email/{id}/{url}', 'Auth\OauthController@checkEmail');

Route::post('/setPass/{id}', 'Auth\OauthController@setPass');

Route::post('/account/delete/{id}', 'Auth\OauthController@destroy');



/* API */
Route::group(['middleware' => 'api', 'prefix' => 'api'], function () {
    Route::post('login', 'Auth\AuthController@authenticate');
    Route::group(['middleware' => 'jwt.auth'], function () {
	    Route::get('users', 'APIController@get_oauth_users');
    });
});
