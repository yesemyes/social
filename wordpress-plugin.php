<?php

// get token -- connect
$email = "yesemyes1@gmail.com";
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


if( isset($token) )
{
	if(isset($_GET['name'])){
		var_dump($_GET['name']." ".$_GET['email']);
		die;
	}
	//header("Location: http://social-lena.dev/facebook/login?wp=true");
	echo "<a href='http://social-lena.dev/facebook/login?wp=true'>Login with facebook</a><br />";
	echo "<a href='http://social-lena.dev/twitter/login?wp=true'>Login with twitter</a><br />";
}