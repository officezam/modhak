<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SmsTemplate extends Model
{
	protected $table = 'sms_template';
	protected $fillable = ['template','type'];
}
