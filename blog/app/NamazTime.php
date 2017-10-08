<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NamazTime extends Model
{
    protected $table = 'namaz_time';
    protected $fillable = [
        'm_id',
        'date',
        'fajar',
        'zuhar',
        'jumma',
        'asar',
        'maghrib',
        'esha',
    ];
}

