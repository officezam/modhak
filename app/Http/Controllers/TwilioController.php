<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Aloha\Twilio\Twilio;
use Services_Twilio;

class TwilioController extends Controller
{


	public function __construct() {
		$this->twilio = new Twilio($sid =env('TWILIO_SID'), $token=env('TWILIO_TOKEN'), $from=env('TWILIO_FROM'), $sslVerify = true);
	}

	public function  createcall(Request $request){

		// Set our Account SID and AuthToken
		$sid =env('TWILIO_SID');
		$token = env('TWILIO_TOKEN');

		// A phone number you have previously validated with Twilio
		$phonenumber = env('TWILIO_FROM');

		// Instantiate a new Twilio Rest Client
		$client = new Services_Twilio($sid, $token);

		// Initiate a new outbound call
		$call = $client->account->calls->create(
			$phonenumber, // The number of the phone initiating the call
			$request->phone, // The number of the phone receiving call
			'http://127.0.0.1:8000/dial?from='.$request->phone.'&to='.\Request::get('to'), // The URL Twilio will request when the call is answered
			array("Method" => "GET")); //use the GET Method

		dd($call);
	}
	public function dial(Request $request) {
		$response = new \Services_Twilio_Twiml;
		$response->dial(\Request::get('to'), array(
			'callerId' => \Request::get('from')
		));
		return \Response::make($response, '200')->header('Content-Type', 'text/xml');
		print $response;
	}

}
