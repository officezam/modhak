<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Leadsdetail extends Model
{
	protected $table = 'Leadsdetail';
	protected $fillable = ['leads_id', 'question', 'answer', 'static_reply', 'audio'];

}
