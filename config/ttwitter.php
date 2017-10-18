<?php
session_start();

function testA($a){
	$_SESSION['a'] = $a;
}
function testB($b){
	$_SESSION['b'] = $b;
}

if( isset($_SESSION['a']) ) $a = $_SESSION['a'];
else $a = '';
if( isset($_SESSION['b']) ) $b = $_SESSION['b'];
else $b = '';
//var_dump($a);die;
// You can find the keys here : https://apps.twitter.com/

return [
	'debug'               => function_exists('env') ? env('APP_DEBUG', false) : false,

	'API_URL'             => 'api.twitter.com',
	'UPLOAD_URL'          => 'upload.twitter.com',
	'API_VERSION'         => '1.1',
	'AUTHENTICATE_URL'    => 'https://api.twitter.com/oauth/authenticate',
	'AUTHORIZE_URL'       => 'https://api.twitter.com/oauth/authorize',
	'ACCESS_TOKEN_URL'    => 'https://api.twitter.com/oauth/access_token',
	'REQUEST_TOKEN_URL'   => 'https://api.twitter.com/oauth/request_token',
	'USE_SSL'             => true,
	'CONSUMER_KEY'        => function_exists('env') ? env('TWITTER_CONSUMER_KEY', '') : '',
	'CONSUMER_SECRET'     => function_exists('env') ? env('TWITTER_CONSUMER_SECRET', '') : '',

	'ACCESS_TOKEN'        => $a,
	'ACCESS_TOKEN_SECRET' => $b,
];

