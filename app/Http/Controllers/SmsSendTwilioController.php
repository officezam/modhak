<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Members;
use Aloha\Twilio\Twilio;

class SmsSendTwilioController extends Controller
{

	public function __construct() {
	$this->twilio = new Twilio($sid =env('TWILIO_SID'), $token=env('TWILIO_TOKEN'), $from=env('TWILIO_FROM'), $sslVerify = true);
	}

	public function smsBulkSend(Request $request)
	{
		$members = Members::where('membertype_id' ,'=',$request->membertype_id)->get();
		$message = $request->sms_text;
		foreach ($members as $useData):
			$response = $this->twilio->message($useData->phone, $message);
		endforeach;

		$request->session()->flash('send', 'SMS Send Successfully Responce True and Queu..!');
		return redirect()->route('bulkmessages');
	}

	public function smssingleSend(Request $request)
	{
		$response = $this->twilio->message($request->phone, $request->sms_text);

		$request->session()->flash('send', 'SMS Send Successfully Responce True and Queu..!');
		return redirect()->route('singlemessages');
	}

}
