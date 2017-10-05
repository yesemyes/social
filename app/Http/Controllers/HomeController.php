<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;
//use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\User;
use App\Api_user;
use App\Social;
use App\Oauth;
use DB;
use Auth;


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
			//$user = Auth::user();
			$user = new User;
	      //dd($user);
			$socials = Social::get();
			//$userConnectedAccounts = $user->connectedAccounts()->get()->keyBy('social_id');
			$userConnectedAccounts = $user::select('oauth.*','users.*')
			                              ->leftJoin('api_users','api_users.id','=','users.api_user_id')
			                              ->leftJoin('oauth','oauth.user_id','=','users.id')
			                              ->where('users.api_user_id',3)
			                              ->where('users.id',72)->get()->keyBy('social_id');
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
			return view('home', ['user' => $user, 'userAccounts' => $userAccounts]);
    }



	public function posts()
	{
		return view('posts');
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
