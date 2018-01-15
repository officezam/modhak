<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Leadsdetail extends Model
{
	protected $table = 'Leadsdetail';
	protected $fillable = ['leads_id', 'question', 'answer', 'question_no', 'static_reply', 'audio'];

}
