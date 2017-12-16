<?php

namespace App\Http\Controllers;

use Dotenv\Validator;
use Illuminate\Http\Request;
use App\Memberstype;

class MemberstypeController extends Controller
{
	public function membersTypeData(){
		$getData = Memberstype::get();
		return view('backend.memberstype_data', compact('getData'));
	}

	public function registerForm(){
		return view('backend.add_memberstype');
	}

	public function deletMembertype($id){
		Memberstype::where('id',$id)->delete();
		return redirect()->route('members-type-data');
	}


	/**
	 * Get a validator for an incoming registration request.
	 *
	 * @param  array  $data
	 * @return \Illuminate\Contracts\Validation\Validator
	 */
	protected function validator(array $data)
	{
		return Validator::make($data, [
			'name' => 'required|string|max:255',
			'email' => 'required|string|email|max:255|unique:users',
			'address' => 'required',
			'phone' => 'required',
			'password' => 'required|string|min:6|confirmed',
		]);
	}

	/**
	 * Create a new user instance after a valid registration.
	 *
	 * @param  array  $data
	 * @return \App\User
	 */
	protected function create(array $data)
	{
		return User::create([
			'name' => $data['name'],
			'email' => $data['email'],
			'address' => $data['address'],
			'phone' => $data['phone'],
			'remember_token' => $data['_token'],
			'type' => 'user',
			'password' => bcrypt($data['password']),
		]);
	}
	public function register(Request $request)
	{
		$data = ['type' => $request->type];
		Memberstype::create($data);

		return redirect()->route('members-type-data');
	}
}
