<?php

namespace App\Http\Controllers;

use App\ExcelModel;
use App\Leads;
use App\Leadsdetail;
use Illuminate\Http\Request;
use App\Members;
use Aloha\Twilio\Twilio;
use App\ReceiveSms;

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
			$number = str_replace('-', '',$useData->phone);
			$response = $this->twilio->message($number, $message);
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

	/*
	 * Schedule SMS Sending Function
	*/
	public function scheduleSms($membertype_id, $message)
	{
		$members = Members::where('membertype_id' ,'=',$membertype_id)->get();
		foreach ($members as $useData):
			$number = str_replace('-', '',$useData->phone);
			$response = $this->twilio->message($number, $message);
		endforeach;
	}

	/*
	 * Bulk SMS Sending
	 * */
	public function leadsSms(Request $request)
	{
		$members   = Members::where('membertype_id' ,'=',$request->membertype_id)->get();
		$leadsData = Leads::find($request->leads_id);
		$message = $leadsData->description;

		if(strpos( $message, '{{Questions}}' ) != false)
		{
			$leadsQuestion = Leadsdetail::where('leads_id',$request->leads_id)->where('status', '!=', 'unsubscribe')->orderBy('question_no', 'asc')->get();
			foreach ($leadsQuestion as $item )
			{
				$message = str_replace('{{Questions}}',$item->question, $message);
			}
		}

		foreach ($members as $useData):
			$number = str_replace('-', '',$useData->phone);
			$response = $this->twilio->message($number, $message);
		endforeach;
		Members::where('membertype_id' ,'=',$request->membertype_id)->update(['leads_id' => $request->leads_id]);
		$request->session()->flash('send', 'SMS Send Successfully Responce True and Queu..!');
		return redirect()->route('leadscampaign');
	}

	/*
	 * Recieve SMS
	 * */
	public function receiveSms(Request $request) {
		// Sender's phone numer
		$from_number = $_REQUEST['From'];
		// Receiver's phone number - Plivo number
		$to_number = $_REQUEST['To'];
		// The SMS text message which was received
		$body = $_REQUEST['Body'];
		// Output the text which was received to the log file.

		$receiveSms = ReceiveSms::create( [ 'from' => $from_number, 'to' => $to_number, 'keyword' => $body ] );

		$memberData = Members::where('phone' ,'=',$from_number)->first();

		if($memberData)
		{
			$leads_id   = $memberData->leads_id;
			$leadsdetailData = Leadsdetail::where('leads_id' ,'=',$leads_id)->where('answer' ,'=',$body)->first();
			if($leadsdetailData)
			{
				$answerReply = $leadsdetailData->static_reply;
				$question_id = $leadsdetailData->question_id;
				$last_answer = $body;
				$response = $this->twilio->message($from_number, $answerReply);
				Members::where('phone' ,'=',$from_number)->update(['question_id' => $question_id,'last_answer' => $last_answer ]);
			}else
			{
				if($body == 'unsub' || $body == 'unsubscribe'){
					Members::where('phone' ,'=',$from_number)->update(['status' => 'unsubscribe']);
				}else{
					$invalidAnswer = $leadsdetailData->wrong_input_reply;
					$response = $this->twilio->message($from_number, $invalidAnswer);
				}

			}
		}else{
			$invalidNumber = 'This Number not Register';
			$response = $this->twilio->message($from_number, $invalidNumber);
		}

	}

	/*
	 * Recieve SMS
	 * */
	public function receiveSmsTest()
	{
		// Sender's phone numer
		$from_number = $_REQUEST['From'];
		// Receiver's phone number - Plivo number
		$to_number = $_REQUEST['To'];
		// The SMS text message which was received
		$body = $_REQUEST['Body'];
		// Output the text which was received to the log file.

		$receiveSms = ReceiveSms::create( [ 'from' => $from_number, 'to' => $to_number, 'keyword' => $body ] );

		$memberData = Members::where('phone' ,'=',$from_number)->first();

		if($memberData)
		{
			$leads_id   = $memberData->leads_id;
			$leadsdetailData = Leadsdetail::where('leads_id' ,'=',$leads_id)->where('answer' ,'=',$body)->first();
			if($leadsdetailData)
			{
				$answerReply = $leadsdetailData->static_reply;
				$question_id = $leadsdetailData->question_id;
				$last_answer = $body;
				$response = $this->twilio->message($from_number, $answerReply);
				Members::where('phone' ,'=',$from_number)->update(['question_id' => $question_id,'last_answer' => $last_answer ]);
			}else
			{
				if($body == 'unsub' || $body == 'unsubscribe'){
					Members::where('phone' ,'=',$from_number)->update(['status' => 'unsubscribe']);
				}else{
					$invalidAnswer = 'Please Reply Only Valid Answer';
					$response = $this->twilio->message($from_number, $invalidAnswer);
				}

			}
		}else{
			$invalidNumber = 'This Number not Register';
			$response = $this->twilio->message($from_number, $invalidNumber);
		}

	}









}
