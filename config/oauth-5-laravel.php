<?php

return [

	/*
	|--------------------------------------------------------------------------
	| oAuth Config
	|--------------------------------------------------------------------------
	*/

	/**
	 * Storage
	 */
	'storage' => '\\OAuth\\Common\\Storage\\Session',

	/**
	 * Consumers
	 */
	'consumers' => [

		'Facebook' => [
			'client_id'     => '136738110284510',
			'client_secret' => '333cc418895ad004074aeaac05ad5f5c',
			'scope'         => ['email'],
		],
		'Google' => [
			'client_id'     => '149135955700-mptte3s01jl1cloulu7hceequp1f9jq1.apps.googleusercontent.com',
			'client_secret' => 'UqkZkvhyd8RwwqLey0_hn9m0',
			'scope'         => ['userinfo_email', 'userinfo_profile'],
		],
		'Twitter' => [
			'client_id'     => 'CKhBDjmBwp8GiaRofjRZBqw03',
			'client_secret' => '7VPPxg3wzuZhZeBkaqiPbiI3xacj3kN2qLtREgjN7Sim9Kk18w',
			// No scope - oauth1 doesn't need scope
		],
		'Linkedin' => [
		    'client_id'     => '77bxo3m22s83c2',
		    'client_secret' => 'POVE4Giqvd4DlTnU',
		],

	]

];