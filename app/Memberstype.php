<?php

namespace App;
use App\Members;
use Illuminate\Database\Eloquent\Model;

class Memberstype extends Model
{
	protected $table = 'membertype';
	protected $fillable = ['type' ];


	public function members() {
		return $this->hasMany('App\Members', 'membertype_id');
	}


}
