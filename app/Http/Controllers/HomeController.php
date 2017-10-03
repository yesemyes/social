<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\User;
use App\Social;
use App\Oauth;
use DB;

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
					$userAccounts[$key] = ['provider' => $item->provider,'userId' => $userID, 'icon' => $item['icon']];
				}else{
					$userAccounts[$key] = ['provider' => $item->provider,'icon' => $item['icon']];
				}
			}
			return view('home', ['user' => $user, 'userAccounts' => $userAccounts]);
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
