<?php

namespace App;
use App\Memberstype;

use Illuminate\Database\Eloquent\Model;

class Members extends Model
{
	protected $table = 'members';
	protected $fillable = ['membertype_id', 'name', 'phone', 'first_name', 'last_name',	'address', 'city', 'state',	'country','zip_code','email','type','status','leads_id','question_id','last_answer'];

	public function membersType() {
		return $this->belongsTo('App\Memberstype', 'membertype_id', 'id');
	}


}
