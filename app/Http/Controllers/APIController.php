<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Social;
use App\Oauth;
//use App\User;

//use DB;
//use Hash;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\ApiUser;
class APIController extends Controller
{
	/*public function register(Request $request)
	{
		$input = $request->all();
		$input['password'] = Hash::make($input['password']);
		User::create($input);
		return response()->json(['result'=>true]);
	}*/

	/*public function login(Request $request)
	{
		$dbb = Api_user::get();
		//dd( $dbb );
		$input = $request->all();
		if (!$token = JWTAuth::attempt($input)) {
			return response()->json(['result' => 'wrong email or password.']);
		}
		return response()->json(['result' => $token]);
	}*/

	public function index()
	{
		return response()->json(['auth'=>Auth::user(), 'users'=>User::all()]);
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
		return response()->json(['auth'=> $currentUser, 'token' => $token]);
	}

	public function get_oauth_users(Request $request)
	{
		$input = $request->all();
		$api_user = JWTAuth::toUser($input['token']);

		if($api_user){
			$user = Auth::user();
			//dd($api_user);
			$userID = $api_user['id'];
			$email = $input['email'];
			//return response()->json(['userAccounts' => $email]);
			$socials = Social::get();
			$userConnectedAccounts = $api_user->connectedAccounts()->get()->keyBy('social_id');
			//dd($userConnectedAccounts);
			$userAccounts = array();
			foreach($socials as $key => $item) {
				if( isset($userConnectedAccounts[$item->id]) ){
					$userID = $userConnectedAccounts[$item->id]->id;
					$userAccounts[$key] = ['provider' => $item->provider,'userId' => $userID, 'icon' => $item['icon']];
				}else{
					$userAccounts[$key] = ['provider' => $item->provider,'icon' => $item['icon']];
				}
			}
			return response()->json(['userAccounts' => $userAccounts]);
		}

	}

}
