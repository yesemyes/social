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
			'client_id'     => '114777295493-rlp2c28pr2l2dpmpi4spec663fjrf5si.apps.googleusercontent.com',
			'client_secret' => 'iUIqdh9Hf9-Tbm8iOVLV3x3L',
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
		'Instagram' => [
			'client_id'     => '7502c314346d4f2fac1d02cbe9074c71',
			'client_secret' => '38eae833dda340d2a1e9330b9be11dc5',
			'scope'         => ['basic', 'comments', 'relationships', 'likes'],
		],
		'Reddit' => [
			'client_id'     => 'GkVN6o9276WXqQ',
			'client_secret' => 'VSxB-u2Fknmng72nUMqI0MY5koI',
			'scope'         => ['identity', 'submit'],
		],
		'Pinterest' => [
			'client_id'     => '4929206043270984634',
			'client_secret' => 'afd0a4a201791bc34136f9539ea3916bf563d2271e4a6b3139e21a83098007e7',
			'scope'			=>	['read_public','write_public'],
		],

	]

];