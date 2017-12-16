<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Members extends Model
{
	protected $table = 'members';
	protected $fillable = ['membertype_id', 'name', 'phone' ];
}
