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

    /*
     * Subscriber Record Fetch
     * */
    public function subscriberRecod(){
        $subscriber = User::get();
        return view('backend.subscriber_data' , compact('subscriber'));
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


    /*
     * Delete User Record From user table
     * */
    public function deleteSubscriber($id, Request $request)
    {
        User::where('id', '=', $id)->delete();
        $request->session()->flash('success', 'Delete User Record Successfully..!');
        return redirect()->route('subscriber-data');
    }



}
