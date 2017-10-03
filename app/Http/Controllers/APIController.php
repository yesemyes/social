<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\User;
use Hash;
use JWTAuth;
class APIController extends Controller
{

	/*public function register(Request $request)
	{
		$input = $request->all();
		$input['password'] = Hash::make($input['password']);
		User::create($input);
		return response()->json(['result'=>true]);
	}*/

	public function login(Request $request)
	{
		$input = $request->all();
		if (!$token = JWTAuth::attempt($input)) {
			return response()->json(['result' => 'wrong email or password.']);
		}
		return response()->json(['result' => $token]);
	}


	/*public function get_user(Request $request)
	{
		$input = $request->all();
		$user = JWTAuth::toUser($input['token']);
		if( $input['id'] == 59 ){
			$get_user_details = User::find($input['id']);
			return response()->json(['result_get_user' => $get_user_details]);
		}
	}*/

	public function get_user_details(Request $request)
	{
		$input = $request->all();
		$user = JWTAuth::toUser($input['token']);
		return response()->json(['result_user' => $user]);
	}

}
