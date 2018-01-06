<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExcelModel extends Model
{
    protected $table = 'excel';
    protected $fillable = [
        'first_name',
        'last_name',
        'address',
        'city',
        'state',
        'country',
        'zip_code',
        'phone',
        'email',
    ];
}
