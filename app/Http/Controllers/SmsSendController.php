<?php

namespace App\Http\Controllers;

use App\UserMosque;
use Illuminate\Http\Request;
use Plivo;

class SmsSendController extends Controller
{

    public function __construct()
    {
        $this->plivo = new Plivo\RestAPI($auth_id = "MANDIWNGMYY2M2MJMXYT", $auth_token = "Nzk4M2E2ZmI4NjdjY2NkMTY0ZDUwY2E0NTlmMzkz");
    }

    public function index()
    {
        return view('backend.sendsms');
    }

    public function smsTemplate(){
        return view('backend.sms_template');
    }

    public function smsSending(Request $request){

        //$getUserData = UserMosque::allUsers();
        //dd($getUserData);

        $params = array(
            'src' => '+15876046444', // Sender's phone number with country code
            'dst' => '+17802456176', // receiver's phone number with country code
            'text' => 'Hi, Test Message From Plivo Amir Working On it ' // Your SMS text message
        );
        // Send message
        //$response = $this->plivo->send_message($params);
        //dd($response);
        $request->session()->flash('send', 'SMS Send Successfully..!');
        return view('backend.sendsms');
    }

}
