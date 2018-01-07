<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ScheduleSms extends Model
{
	protected $table = 'schedulesms';
	protected $fillable = ['membertype_id','type', 'sms', 'dateandtime', 'status'];
}
