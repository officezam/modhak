<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReceiveSms extends Model
{
    protected $table = 'receive_sms';
    protected $fillable = [
        'from',
        'to',
        'keyword',
        'reply_status'
    ];

}
