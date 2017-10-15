<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subscriber extends Model
{
    protected $table = 'subscriber';
    protected $fillable = ['u_id','m_id', 'name', 'phone' ];
}
