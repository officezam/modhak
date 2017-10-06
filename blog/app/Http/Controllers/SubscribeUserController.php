<?php

namespace App\Http\Controllers;
use App\Mosque;
use App\User;

use App\UserMosque;
use Illuminate\Http\Request;

class SubscribeUserController extends Controller
{
    public function index(){
    	$mosque = Mosque::get();
	    return view('backend.subscribe_user' , compact('mosque'));

    }
    public function saveSubscriber(Request $request){

	    $data = [
		    'name' => $request->name,
		    'phone' => $request->phone,
		    'email' => 'officezam@gmail.com',
	        'password' => '123456',
	    ];

	    $userData = User::create($data);


	    $data = [
		    'm_id' => $request->m_id,
		    'u_id' => $userData->id,
	    ];

	    $userData = UserMosque::create($data);
	    $mosque = Mosque::get();
	    $request->session()->flash('success', 'Subscriber Saved Successfully');
	    return view('backend.subscribe_user' , compact('mosque'));
    }
}
