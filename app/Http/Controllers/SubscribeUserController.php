<?php

namespace App\Http\Controllers;
use App\Mosque;
use App\Subscriber;
use App\UserMosque;
use Illuminate\Http\Request;
use Auth;

class SubscribeUserController extends Controller
{
    public function index(){
        if(Auth::user()->type == 'admin'){
            $mosque = Mosque::get();
        }else{
            $mosque = Mosque::where('u_id',Auth::user()->id)->get();
        }
	    return view('backend.subscribe_user' , compact('mosque'));
    }

    /*
     * Subscriber Record Fetch
     * */
    public function subscriberRecod(){
        if(Auth::user()->type == 'admin'){
            $subscriber = Subscriber::get();
        }else{
            $subscriber = Subscriber::where('u_id',Auth::user()->id)->get();
        }
        return view('backend.subscriber_data' , compact('subscriber'));
    }
    public function saveSubscriber(Request $request){

	    $data = [
            'm_id' => $request->m_id,
            'u_id' => Auth::user()->id,
		    'name' => $request->name,
		    'phone' => $request->phone,
	    ];


	    $userData = Subscriber::create($data);
	    $mosque = Mosque::get();
	    $request->session()->flash('success', 'Subscriber Saved Successfully');
	    return view('backend.subscribe_user' , compact('mosque'));
    }


    /*
     * Delete User Record From user table
     * */
    public function deleteSubscriber($id, Request $request)
    {
        Subscriber::where('id', '=', $id)->delete();
        $request->session()->flash('success', 'Delete User Record Successfully..!');
        return redirect()->route('subscriber-data');
    }



}
