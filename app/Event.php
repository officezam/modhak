<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $table = 'event';
    protected $fillable = [
        'u_id',
        'm_id',
        'name',
        'date',
        'time',
    ];
}
