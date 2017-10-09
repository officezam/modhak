<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserMosque extends Model
{
	protected $table = 'user_mosque';
	protected $fillable = [
		'u_id',
		'm_id',
	];


    /**
     * Get the post that owns the comment.
     */
    public function users()
    {
        return $this->belongsTo('App\User', 'id', 'm_id');
    }

    public function allUsers(){
        return $this->belongsToMany('App\User');
    }

}
