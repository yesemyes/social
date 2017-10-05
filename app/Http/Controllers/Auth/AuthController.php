<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Auth;

class AuthController extends Controller
{
    //
	public function __construct() {
		$this->middleware('guest', ['except' => 'getLogout']);
	}

	public function authenticate(Request $request) {
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
}
