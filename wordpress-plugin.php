<?php

// get token -- connect
$email = "yesemyes1111@gmail.com";
$password = "123456";

$data = json_encode([
           'email' => $email,
           'password' => $password,
       ]);
$ch = curl_init("http://social-lena.dev/api/login");
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

$result = curl_exec($ch);
$err = curl_error($ch);
$json   = json_decode($result);
curl_close($ch);

if ($err) {
   return "cURL Error #:" . $err;
}
if(isset($json->error)){
   return $json->error;
}
$token = $json->result;



if( isset($token) && $token != "wrong email or password." )
{
	if(isset($_GET['name'])){
		$email = $_GET['email'];
		var_dump($_GET['name']." ".$_GET['email']);

		/*$data = json_encode([
           'token' => $token,
           'email' => $email,
       ]);
		$ch = curl_init("http://social-lena.dev/api/users");
		curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

		$result = curl_exec($ch);
		$err = curl_error($ch);
		$json   = json_decode($result);
		curl_close($ch);*/

		$curl = curl_init();
		curl_setopt_array($curl, array(
		   CURLOPT_URL => "http://social-lena.dev/api/users?token=".$token."&email=".$email,
		   CURLOPT_RETURNTRANSFER => true,
		   CURLOPT_ENCODING => "",
		   CURLOPT_MAXREDIRS => 10,
		   CURLOPT_TIMEOUT => 30,
		   CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		   CURLOPT_CUSTOMREQUEST => "GET",

		));

		$response = curl_exec($curl);
		$err = curl_error($curl);
		$json = json_decode($response);
		curl_close($curl);

		if ($err) {
		   return "cURL Error #:" . $err;
		}
		if(isset($json->error)){
		   return $json->error;
		}
		$result_user = $json;
		var_dump($result_user);die;
		?>
		<div class="row">
	        <div class="col-lg-6">
	            <div class="panel panel-default">
	                <div class="panel-heading">
	                    Connected accounts
	                </div>
	                <div class="panel-body">
	                    <p>Here will be list of connected accounts ---</p>
	                    <ul class="">
	                        <?php foreach($result_user->userAccounts as $item):?>
	                            <?php if(isset($item->userId)):?>
	                            <li class="list-group-item">
	                                <div class="media">
	                                    <img class="pull-left hidden-xs b2s-img-network" alt="Twitter" src="http://localhost/blog-to-social/wp-content/plugins/blog2social/assets/images/portale/<?=$item->icon?>">
	                                    <div class="media-body network">
	                                        <h4><?=$item->provider?>
	                                            <span class="b2s-network-auth-count">(Connections <span class="">1</span>/1)</span>
	                                            <span class="pull-right"><a href="#">+ Profile</a></span>
	                                        </h4>
	                                        <ul class="">
	                                            <li class="">Profile: --- <span class="">(My profile)</span>
	                                                <a href="/<?=$item->provider?>/login" class="">
	                                                    <span class="glyphicon  glyphicon-refresh glyphicon-grey"></span>
	                                                </a>
	                                                <a class="deleteAccount" data-id="<?=$item->userId?>" href="#">
	                                                    <span class="glyphicon  glyphicon-trash glyphicon-grey"></span>
	                                                </a>
	                                            </li>
	                                        </ul>
	                                    </div>
	                                </div>
	                            </li>
	                            <?php else:?>
	                            <li class="list-group-item">
	                                <div class="media">
	                                    <img class="pull-left hidden-xs b2s-img-network" alt="Twitter" src="http://localhost/blog-to-social/wp-content/plugins/blog2social/assets/images/portale/<?=$item->icon?>">
	                                    <div class="media-body network">
	                                        <h4><?=$item->provider?>
	                                            <span class="b2s-network-auth-count">(Connections <span class="">0</span>/1)</span>
	                                            <span class="pull-right"><a href="http://social-lena.dev/<?=$item->provider?>/login/?wp=true">+ Profile</a></span>
	                                        </h4>
	                                    </div>
	                                </div>
	                            </li>
	                            <?php endif;?>
	                        <?php endforeach;?>
	                    </ul>
	                </div>
	            </div>
	        </div>
	    </div>
		<?php
		die;
	}else{
		echo "<a href='http://social-lena.dev/facebook/login?wp=true'>Login with facebook</a><br />";
		echo "<a href='http://social-lena.dev/google/login?wp=true'>Login with google</a><br />";	
	}
	//header("Location: http://social-lena.dev/facebook/login?wp=true");
	
}else{
	echo "wrong email or password. --- ";
}