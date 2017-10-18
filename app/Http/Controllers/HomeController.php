<?php

namespace App\Http\Controllers;

//use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use App\User;
//use App\Api_user;
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
			//$user = new User;
	      //dd($user);
			$socials = Social::get();
			$userConnectedAccounts = $user->connectedAccounts()->get()->keyBy('social_id');
			/*$userConnectedAccounts = $user::select('oauth.*','users.*')
			                              ->leftJoin('api_users','api_users.id','=','users.api_user_id')
			                              ->leftJoin('oauth','oauth.user_id','=','users.id')
			                              ->where('users.api_user_id',3)
			                              ->where('users.id',72)->get()->keyBy('social_id');*/
			//dd($userConnectedAccounts);
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
				//dump($item);
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
