<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Social;
use App\Oauth;
use DB;
use Auth;
use Session;
use App;

class HomeController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
			$user = Auth::user();
			$socials = Social::get();
			$userConnectedAccounts = $user->connectedAccounts()->get()->keyBy('social_id');

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
			return view('home', ['user' => $user, 'userAccounts' => $userAccounts]);
    }

	public function policy()
	{
		return view('policy');
	}


    public function Account()
    {
        return view('account');
    }

    public function accountUpdate($id, Request $request)
    {
        if($request && $request->change_account == "change")
        {
            $name = $request->name;    
            $email = $request->email;
            $password = $request->password;
            dd($id);
        }
        else
        {
            Session::flash('msg','Error!');
            return redirect()->back();
        }
        
    }


}
