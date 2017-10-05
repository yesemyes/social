<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ApiUser extends Model
{
	protected $connection = "mysql";
	protected $table = "users";

	protected $fillable = [
		'name', 'email', 'password',
	];

	/**
	 * The attributes that should be hidden for arrays.
	 *
	 * @var array
	 */
	protected $hidden = [
		'password', 'remember_token',
	];

    //
}
