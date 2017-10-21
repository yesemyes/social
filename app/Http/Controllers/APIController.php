<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Social;
use App\Oauth;
use App\User;
use DB;
use Hash;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Auth;
use App;
use File;
//use Illuminate\Support\Facades\Session;
use Session;

class APIController extends Controller
{
	public function register(Request $request)
	{
		$input = $request->all();
		$input['password'] = Hash::make($input['password']);
		$result = User::create($input);
		return response()->json(['result'=>true]);
	}

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

		if( $currentUser != null ) {
			return response()->json(['auth'=> $currentUser, 'token' => $token, 'status' => true]);
		}
		return response()->json(['status' => false]);

	}

	public function get_oauth_users(Request $request)
	{
		$input = $request->all();
		$api_user = JWTAuth::toUser($input['token']);
		if($api_user) {
			$user = $input['user'];
			$socials = Social::get();
			$userConnectedAccounts = Oauth::select('oauth.*')->leftJoin('users','users.id','=','oauth.user_id')
			                              ->where('oauth.user_name',$user)->where('oauth.user_id',$api_user->id)->get()->keyBy('social_id');
			$userAccounts = array();
			foreach($socials as $key => $item) {
				if( isset($userConnectedAccounts[$item->id]) ){
					$userAccounts[$key] = [
						'provider'           => $item->provider,
						'userId'             => $userConnectedAccounts[$item->id]->id,
						'provUserId'         => $userConnectedAccounts[$item->id]->provider_user_id,
						'icon'               => $item['icon'],
						'access_token'       => $userConnectedAccounts[$item->id]->access_token,
						'access_token_secret'=> $userConnectedAccounts[$item->id]->access_token_secret,
						'first_name'         => $userConnectedAccounts[$item->id]->first_name,
						'last_name'          => $userConnectedAccounts[$item->id]->last_name
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
		$account = Oauth::where('id',$request->id)->delete();
		if($account){
			return response(['status' => 'success']);
		}else{
			return response(['status' => 'failed']);
		}
	}

}
