<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Validator;

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


    public function registerUser(Request $request){
        return Validator::make($request, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'address' => 'required',
            'phone' => 'required',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }
}
