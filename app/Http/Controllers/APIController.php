<?php

namespace App\Http\Controllers;

//use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Social;
use App\Oauth;
//use App\User;

//use DB;
//use Hash;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\ApiUser;
use Auth;
use App;
use Twitter;
use File;
//use Illuminate\Support\Facades\Session;
use Session;

class APIController extends Controller
{
	public function twitter()
	{
		$data = Twitter::getUserTimeline(['count' => 10, 'format' => 'array']);
		//dd( $data );
		return view('twitt',compact('data'));
	}

	public function tweet(Request $request)
	{
		$this->validate($request, [
			'message' => 'required'
		]);

		$newTwitte = ['status' => $request->message.' '.$request->link ];
		if(!empty($request->images)){
			foreach ($request->images as $key => $value) {
				$uploaded_media = Twitter::uploadMedia(['media' => File::get($value->getRealPath())]);
				if(!empty($uploaded_media)){
					$newTwitte['media_ids'][$uploaded_media->media_id_string] = $uploaded_media->media_id_string;
				}
			}
		}
		$twitter = Twitter::postTweet($newTwitte);
		//return back();
		return response(['result' => $twitter]);
	}

	public function facebook(Request $request)
	{
		$fb = App::make('SammyK\LaravelFacebookSdk\LaravelFacebookSdk');

		$linkData = [
			'link' => $request->link,
			'message' => $request->message,
		];
		try {
			// Returns a `Facebook\FacebookResponse` object
			$response = $fb->post('/me/feed', $linkData, $request->token_soc);
		} catch(Facebook\Exceptions\FacebookResponseException $e) {
			echo 'Graph returned an error: ' . $e->getMessage();
			exit;
		} catch(Facebook\Exceptions\FacebookSDKException $e) {
			echo 'Facebook SDK returned an error: ' . $e->getMessage();
			exit;
		}

		$graphNode = $response->getGraphNode();

		//echo 'Posted with id: ' . $graphNode['id'];
		return response(['result' => $graphNode]);
	}

	/*public function register(Request $request)
	{
		$input = $request->all();
		$input['password'] = Hash::make($input['password']);
		User::create($input);
		return response()->json(['result'=>true]);
	}*/

	public function login(Request $request)
	{
		$credentials = $request->only('email', 'password');
		try {
			// verify the credentials and create a token for the user
			if (! $token = JWTAuth::attempt($credentials)) {
				return response()->json(['error' => 'invalid_credentials'], 401);
			}
		} catch (JWTException $e) {
			// something went wrong
			return response()->json(['error' => 'could_not_create_token'], 500);
		}
		$currentUser = Auth::user();
		// if no errors are encountered we can return a JWT
		return response()->json(['auth'=> $currentUser, 'token' => $token]);
	}

	public function get_oauth_users(Request $request)
	{
		$input = $request->all();
		$api_user = JWTAuth::toUser($input['token']);
		if($api_user){
			//$user = Auth::user();

			$socials = Social::get();
			$userConnectedAccounts = $api_user->connectedAccounts()->get()->keyBy('social_id');
			$userAccounts = array();
			foreach($socials as $key => $item) {
				if( isset($userConnectedAccounts[$item->id]) ){
					$userID = $userConnectedAccounts[$item->id]->id;
					$provUserID = $userConnectedAccounts[$item->id]->provider_user_id;
					$access_token = $userConnectedAccounts[$item->id]->access_token;
					$userAccounts[$key] = [
						'provider' => $item->provider,
						'userId' => $userID,
						'provUserId' => $provUserID,
						'icon' => $item['icon'],
						'access_token' => $access_token
					];
				}else{
					$userAccounts[$key] = ['provider' => $item->provider,'icon' => $item['icon']];
				}
			}
			return response()->json(['userAccounts' => $userAccounts, 'user' => $api_user]);
		}

	}

	public function destroy( Request $request )
	{


		/*if ( $request->ajax() ) {
			$account = Oauth::where('id',$id)->delete();

			return response(['message' => 'Product deleted', 'status' => 'success']);
		}*/
		$account = Oauth::where('id',$request->id)->delete();
		if($account){
			return response(['message' => 'Product deleted', 'status' => 'success']);
		}else{
			return response(['message' => 'Failed deleting the product', 'status' => 'failed']);
		}

		//return response(['message' => 'Failed deleting the product', 'status' => 'failed']);
	}

}
