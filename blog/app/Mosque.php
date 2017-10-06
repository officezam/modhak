<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mosque extends Model
{
	protected $table = 'mosque';
	protected $fillable = [
		'mosque_name',
		'city',
		'date',
		'fajar_time',
		'zuhar_time',
		'asar_time',
		'magrib_time',
		'esha_time',
	];

	public function saveMosque22($request){
		dd($request);
//		$data = [
//			'name' => $user['full_name'],
//			'city' => $user->email,
//			'password' => bcrypt($user->password),
//			'catsone_candidate_id' => 1,
//			'public_id' => 1,
//			'country' => 1,
//			'is_admin' => '2'
//		];
//
//		$userData = $this->create($data);

	}
}
