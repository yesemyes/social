<?php

namespace App\Http\Controllers;
session_start();

require_once( base_path('socials/facebook/fbsdk/src/Facebook/autoload.php') );
require_once( base_path('socials/twitter/TwitterAPIExchange.php') );
require_once( base_path('socials/linkedin/LinkedIn/LinkedIn.php') );

use Facebook\Facebook as Facebook;
use Facebook\Exceptions\FacebookResponseException as FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException as FacebookSDKException;
use TwitterAPIExchange;
use LinkedIn\LinkedIn;

use Illuminate\Http\Request;

class SocialController extends Controller
{
	public $fb;
	public $twitter;
	public $linkedin;

	public function facebook(Request $request)
	{
		$this->fb = new Facebook([
			'app_id'                => '136738110284510',
			'app_secret'            => '333cc418895ad004074aeaac05ad5f5c',
			'default_graph_version' => 'v2.5',
		]);
		$linkData = [
			'link' => $request->link,
			'message' => $request->message,
		];
		try {
			// Returns a `Facebook\FacebookResponse` object
			$response = $this->fb->post('/me/feed', $linkData, $request->token_soc);
		} catch(FacebookResponseException $e) {
			echo 'Graph returned an error: ' . $e->getMessage();
			exit;
		} catch(FacebookSDKException $e) {
			echo 'Facebook SDK returned an error: ' . $e->getMessage();
			exit;
		}
		$graphNode = $response->getGraphNode();
		if( $graphNode != null ){
			return response()->json(['result'=>'success']);
		}else{
			return response()->json(['result'=>'error']);
		}

	}

	public function twitter(Request $request) // work in live server
	{
		$settings = array(
			'oauth_access_token' => $request->access_token, // request->access_token
			'oauth_access_token_secret' => $request->access_token_secret, // request->access_token_secret
			'consumer_key' => "CKhBDjmBwp8GiaRofjRZBqw03",
			'consumer_secret' => "7VPPxg3wzuZhZeBkaqiPbiI3xacj3kN2qLtREgjN7Sim9Kk18w"
		);
		/** URL for REST request, see: https://dev.twitter.com/docs/api/1.1/ **/

		$url = 'https://api.twitter.com/1.1/statuses/update.json';
		$requestMethod = 'POST';
		$this->twitter = new TwitterAPIExchange($settings);
		$postfields = array(
			'status' => $request->message.' '.$request->link );
		$response = $this->twitter->buildOauth($url, $requestMethod)
		                          ->setPostfields($postfields)
		                          ->performRequest();
		if( $response != null ){
			return response()->json(['result'=>'success']);
		}else{
			return response()->json(['result'=>'error']);
		}

	}

	public function google(Request $request) // not working
	{
		return 'google plus API';
	}

	public function linkedin(Request $request)
	{
		$li = new LinkedIn(
			array(
				'api_key' => '77bxo3m22s83c2',
				'api_secret' => 'POVE4Giqvd4DlTnU',
				'callback_url' => 'http://social-lena.dev/linkedin/login/?wp=true'
			)
		);
		$li->setAccessToken($request->token_soc);
		$postParams = array(
			"content" => array(
				"title" => $request->message,
				"description" => "asd123",
				"submitted-url" => $request->link
			),
			"visibility" => array(
				"code" => "anyone"
			)
		);
		$response = $li->post('/people/~/shares?format=json', $postParams);
		if( $response != null ){
			return response()->json(['result'=>'success']);
		}else{
			return response()->json(['result'=>'error']);
		}
	}
}
