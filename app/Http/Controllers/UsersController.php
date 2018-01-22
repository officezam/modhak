<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class UsersController extends Controller
{


    public function usersData(){
        $getData = User::where('type' , 'user')->get();
        return view('backend.users_data', compact('getData'));
    }

    public function registerForm(){
        return view('auth.register');
    }

    public function deletUser($id){
        User::where('id',$id)->delete();
        return redirect()->route('users-data');
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
            'email' => 'required|unique:users',
            'address' => 'required',
            'phone' => 'required|unique:users',
            'sms_count' => 'required',
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
            'sms_count' => $data['sms_count'],
            'password' => bcrypt($data['password']),
        ]);
    }
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();
        event( $this->create($request->all()));
        //$this->guard()->login($user);
        return redirect()->route('users-data');
    }

}
