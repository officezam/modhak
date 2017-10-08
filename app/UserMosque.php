<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserMosque extends Model
{
	protected $table = 'user_mosque';
	protected $fillable = [
		'u_id',
		'm_id',
	];
}
