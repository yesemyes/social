<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Social;
use App\Oauth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use DB;
use Hash;
use JWTAuth;

use Illuminate\Support\Facades\Mail;

class OauthController extends Controller
{
	public function loginWithFacebook(Request $request)
	{
		// get data from request
		$code = $request->get('code');
		// get fb service
		if( isset($_GET['wp']) ) {
			$wp = $_GET['wp'];
			$fb = \OAuth::consumer('Facebook', 'http://social-lena.dev/facebook/login/?wp=true');
		}else{
			$fb = \OAuth::consumer('Facebook', 'http://social-lena.dev/facebook/login');
		}
		// if code is provided get user data and sign in
		if ( ! is_null($code))
		{
			// This was a callback request from facebook, get the token
			$token = $fb->requestAccessToken($code);
			// Send a request with it
			$result = json_decode($fb->request('/me?fields=id,first_name,last_name,name,email,gender,locale,picture'), true);
			$result['access_token'] = $token->getAccessToken();
			//$message = 'Your unique facebook user id is: ' . $result['id'] . ' and your name is ' . $result['name'];
			 //echo $message. "<br/>";
			if( isset($_GET['wp']) ) {
				$result['name'] = $result['first_name'];
				return $this->_register($result,'facebook', $wp);
			}
			if( isset($result['email']) ) {
				$result['name'] = $result['first_name'];
				return $this->_register($result,'facebook');
			}
		}
		// if not ask for permission first
		else
		{
			// get fb authorization
			$url = $fb->getAuthorizationUri();
			// return to facebook login url
			return redirect((string)$url);
		}
	}

	public function loginWithGoogle(Request $request)
	{
		// get data from request
		$code = $request->get('code');
		// get google service
		if( isset($_GET['wp']) ) {
			$wp = $_GET['wp'];
			$googleService = \OAuth::consumer('Google','http://social-lena.dev/google/login/?wp=true');
		}else{
			$googleService = \OAuth::consumer('Google','http://social-lena.dev/google/login');
		}
		// check if code is valid

		// if code is provided get user data and sign in
		if ( ! is_null($code))
		{
			// This was a callback request from google, get the token
			$token = $googleService->requestAccessToken($code);
			// Send a request with it
			$result = json_decode($googleService->request('https://www.googleapis.com/oauth2/v1/userinfo'), true);
			$result['access_token'] = $token->getAccessToken();
			if(isset($_GET['wp'])) {
				//$result['name'] = $result['first_name'];
				return $this->_register($result,'google', $wp);
			}
			if( $result['verified_email'] == true ) {
				return $this->_register($result,'google');
			}
		}
		// if not ask for permission first
		else
		{
			// get googleService authorization
			$url = $googleService->getAuthorizationUri();
			// return to google login url
			return redirect((string)$url);
		}
	}

	public function loginWithTwitter(Request $request)
	{
		// get data from request
		$token  = $request->get('oauth_token');
		$verify = $request->get('oauth_verifier');
		// get twitter service
		if( isset($_GET['wp']) ) {
			$wp = $_GET['wp'];
			$tw = \OAuth::consumer('Twitter',   'http://social-lena.dev/twitter/login/?wp=true');
		}else{
			$tw = \OAuth::consumer('Twitter', 'http://social-lena.dev/twitter/login');
		}
		// check if code is valid
		// if code is provided get user data and sign in
		if ( ! is_null($token) && ! is_null($verify))
		{
			// This was a callback request from twitter, get the token
			$token = $tw->requestAccessToken($token, $verify);
			// Send a request with it
			$result = json_decode($tw->request('account/verify_credentials.json'), true);
			$result['access_token'] = $token->getAccessToken();
			if(isset($_GET['wp'])) {
				return $this->_register($result,'twitter', $wp);
			}
			if( isset($result['access_token']) ) {
				return $this->_register($result,'twitter');
			}
		}
		// if not ask for permission first
		else
		{
			// get request token
			$reqToken = $tw->requestRequestToken();
			// get Authorization Uri sending the request token
			$url = $tw->getAuthorizationUri(['oauth_token' => $reqToken->getRequestToken()]);
			// return to twitter login url
			return redirect((string)$url);
		}
	}

	public function loginWithLinkedin(Request $request)
	{
		// get data from request
		$code = $request->get('code');
		if( isset($_GET['wp']) ) {
			$wp = $_GET['wp'];
			$linkedinService = \OAuth::consumer('Linkedin','http://social-lena.dev/linkedin/login/?wp=true');
		}else{
			$linkedinService = \OAuth::consumer('Linkedin','http://social-lena.dev/linkedin/login');
		}
		if ( ! is_null($code))
		{
			// This was a callback request from linkedin, get the token
			$token = $linkedinService->requestAccessToken($code);
			// Send a request with it. Please note that XML is the default format.
			$result = json_decode($linkedinService->request('/people/~?format=json'), true);
			$result['access_token'] = $token->getAccessToken();
			if(isset($_GET['wp'])) {
				//$result['name'] = $result['first_name'];
				return $this->_register($result,'linkedin', $wp);
			}
			if( isset($result['access_token']) ) {
				return $this->_register($result,'linkedin');
			}
		}
		// if not ask for permission first
		else
		{
			// get linkedinService authorization
			$url = $linkedinService->getAuthorizationUri(['state'=>'DCEEFWF45453sdffef424']);

			// return to linkedin login url
			return redirect((string)$url);
		}
	}

	protected function _register($data,$provider,$wp = null)
	{
		$member = Auth::user();
		$currentDate = date("Y-m-d H:i:s");
		$get_social_id = Social::where('provider',$provider)->first();
		if( isset($data['email']) && $data['email'] != null ) {
			$check_user_by_email = User::where('email',$data['email'])->first(); // fb, google
		}
		else {
			$data['email'] = $data['id'];
			$check_user_by_email = null;
		}
		if( isset($check_user_by_email) && $check_user_by_email != null ) {
			$check_oauth_by_userIdAndProvider = DB::table('oauth')
						->leftJoin('users', 'oauth.user_id', '=', 'users.id')
						->where('oauth.user_id',$check_user_by_email->id)
						->where('oauth.provider',$provider)
						->first();
		}
		else {
			$check_oauth_by_userIdAndProvider = null;
		}
	// CHECKS ALL VARIANTS
		if( $check_user_by_email == null && $member == null && $check_oauth_by_userIdAndProvider == null )
		{
			$ins_user = User::insertGetId(
			[
				'name'            => $data['name'],
				'email'           => $data['email'],
				'password'        => '',
				'remember_token'  => '',
				'created_at'      => $currentDate,
				'updated_at'      => $currentDate,
			]);
			if($ins_user != 0) {
				$get_last_user = User::where('id',$ins_user)->first();
				DB::table('oauth')->insert(
					[
						'user_id'         => $ins_user,
						'provider_user_id'=> $data['id'],
						'provider'        => $provider,
						'access_token'    => isset($data['access_token']) ? $data['access_token'] : '',
						'created_at'      => $currentDate,
						'updated_at'      => $currentDate,
						'social_id'       =>  $get_social_id->id,
					]);
				if( $wp == 'true' ) {
					$result = $data['name'].'&email='.$data['email'].'&id='.$data['id'];
					return redirect('http://localhost/social-auth.php?name='.$result);
				}else {
					Auth::login($get_last_user);
					return redirect('/home');
				}
			}
		}
		elseif( $check_user_by_email != null && $member == null )
		{
			if( $check_oauth_by_userIdAndProvider == null ) {
				DB::table('oauth')->insert(
				[
					'user_id'            => $check_user_by_email->id,
					'provider_user_id'   => $data['id'],
					'provider'           => $provider,
					'access_token'       => isset($data['access_token']) ? $data['access_token'] : '',
					'created_at'         => $currentDate,
					'updated_at'         => $currentDate,
					'social_id'          => $get_social_id->id,
				]);
				if( $wp == 'true' ) {
					$result = $data['name'].'&email='.$data['email'].'&id='.$data['id'];
					return redirect('http://localhost/social-auth.php?name='.$result);
				}else {
					Auth::login($check_user_by_email);
					return redirect('/home');
				}
			}
			else {
				$updexistsOauth = DB::table('oauth')
				                     ->where('user_id',$check_user_by_email->id)
				   	               ->where('provider',$provider)
											->where('provider_user_id',$data['id'])
											->update([
												'access_token' => $data['access_token'],
												'updated_at' => $currentDate,
											]);
				if( $updexistsOauth == 1 ) {
					if( $wp == 'true' ) {
						$result = $data['name'].'&email='.$data['email'].'&id='.$data['id'];
						return redirect('http://localhost/social-auth.php?name='.$result);
					}else {
						Auth::login($check_user_by_email);
						return redirect('/home');
					}
				}
			}
		}
		elseif( $check_oauth_by_userIdAndProvider != null && $member == null )
		{
			$get_user_by_oauth = User::select('users.*')
												->leftJoin('oauth', 'users.id', '=', 'oauth.user_id')
												->where('users.id',$check_oauth_by_userIdAndProvider->id)
												->first();
			$updexistsOauth = DB::table('oauth')
										->where('user_id',$get_user_by_oauth->id)
						            ->where('provider',$provider)
										->where('provider_user_id',$data['id'])
										->update([
												'access_token' => $data['access_token'],
												'updated_at' => $currentDate,
										]);

			if( $updexistsOauth == 1 ) {
				if( $wp == 'true' ) {
					$result = $data['name'].'&email='.$data['email'].'&id='.$data['id'];
					return redirect('http://localhost/social-auth.php?name='.$result);
				}else {
					Auth::login($get_user_by_oauth);
					return redirect('/home');
				}
			}
		}
		elseif( $member != null )
		{
			if( $check_oauth_by_userIdAndProvider == null ) {
				//Session::flash('message', 'this account '.$provider.' already exists please add your '.$provider.' in dashboard');
				DB::table('oauth')->insert(
				[
					'user_id'          => $member->id,
					'provider_user_id' => $data['id'],
					'provider'         => $provider,
					'access_token'     => isset($data['access_token']) ? $data['access_token'] : '',
					'created_at'       => $currentDate,
					'updated_at'       => $currentDate,
					'social_id'        =>  $get_social_id->id,
				]);
				Auth::login($member);
				return redirect('/home');
			}
			else {
				$updexistsOauth = DB::table('oauth')
											->where('user_id',$member->id)
				   	               ->where('provider',$provider)
											->where('provider_user_id',$data['id'])
											->update([
												'access_token' => $data['access_token'],
												'updated_at' => $currentDate,
											]);

				if( $updexistsOauth == 1 ) {
					if( $wp == 'true' ) {
						$result = $data['name'].'&email='.$data['email'].'&id='.$data['id'];
						return redirect('http://localhost/social-auth.php?name='.$result);
					}else {
						Auth::login($member);
						return redirect('/home');
					}
				}
			}
		}
		else
		{
			Session::flash('message', 'undefined error!');
			if( $wp == 'true' ) {
				$result = $data['name'].'&email='.$data['email'].'&id='.$data['id'];
				return redirect('http://localhost/social-auth.php?name='.$result);
			}else{
				return redirect('/home');
			}
		}
	// END
	}

	public function destroy( $id, Request $request )
	{
	    if ( $request->ajax() ) {
	    	$account = DB::table('oauth')->where('id',$id)->delete();

	        return response(['message' => 'Product deleted', 'status' => 'success']);
	    }
	    return response(['message' => 'Failed deleting the product', 'status' => 'failed']);   
	}

	public function checkEmail($id,$url)
	{
		$checkUser = DB::table('users')
		               ->where('remainder',$url)
							->get();
		if( md5($checkUser[0]->id) == $id )
		{
			return view( 'auth.setpass')->withId( $checkUser[0]->id );
		}
	}

	public function setPass(Request $request, $id)
	{
		if( $request->_token != null )
		{
			if( $request->password === $request->password_confirmation )
			{
				$upd_pass = DB::table('users')
				              ->where('id',$id)
				              ->update(['password' => bcrypt($request->password)]);
				if( $upd_pass )
				{
					return redirect('/login');
				}
			}
		}
	}
}