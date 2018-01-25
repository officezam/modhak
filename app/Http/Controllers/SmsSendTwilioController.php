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
//		$response = $this->twilio->create($request->phone,
//			array(
//				'from' => '+13233100845',
//				'body' => $request->sms_text
//			)
//		);

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
		$members   = Members::where([['membertype_id' ,'=',$request->membertype_id],['status', '<>', 'unsubscribe']])->get();
		$leadsData = Leads::find($request->leads_id);
		$message = $leadsData->description;

		if(strpos( $message, '{{Questions}}' ) != false)
		{
			$leadsQuestion = Leadsdetail::where('leads_id',$request->leads_id)->orderBy('question_no', 'asc')->get();
			$message1 = '';
			foreach ($leadsQuestion as $item )
			{
				$message1 = $message1."\r\n".$item->question;
			}
			$message = str_replace('{{Questions}}',$message1, $message);
		}


		foreach ($members as $useData):
			$number = str_replace('-', '',$useData->phone);
			$message = str_replace('{{FirstName}}',$useData->first_name, $message);
			$message = str_replace('{{LastName}}',$useData->last_name, $message);
			$message = str_replace('{{Email}}',$useData->email, $message);
			$message = str_replace('{{Phone}}',$useData->phone, $message);
			$message = str_replace('{{Country}}',$useData->country, $message);
			$number = str_replace('+1','', $number);
				if(strlen(trim($number)) == '10')
				{
					$number = '+1'.$number;
					try {
						$response = $this->twilio->message($number, $message);
						Members::where('phone' ,'=',$number)->update(['status' => 'sent']);
					} catch (Exception $e) {
						echo 'Caught exception: ',  $e->getMessage(), "\n";
						$response = $this->twilio->message(+923007272332, $e->getMessage());
					}
				}
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
			$member_funnel = $memberData->member_funnel;
			$leadsdetailData = Leadsdetail::where('leads_id' ,'=',$leads_id)->where('answer' ,'=',$body)->first();
			if($leadsdetailData)
			{
				$answerReply = $leadsdetailData->static_reply;
				$question_id = $leadsdetailData->question_id;
				$last_answer = $body;
				$response = $this->twilio->message($from_number, $answerReply);
				if(strpos($body,'already'))
				{
					$member_funnel = 'Funnel B';
				}else{
					if(is_null($member_funnel))
					{
						$member_funnel = 'Funnel A';
					}
				}
				Members::where('phone' ,'=',$from_number)->update(['member_funnel' => $member_funnel, 'question_id' => $question_id,'last_answer' => $last_answer ]);
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




	/*
	 * Receive sms Data fetch
	 * receiveSmsData
	*/
	public function receiveSmsData()
	{
		$receiveSms = ReceiveSms::get();
		return view('backend.receivesms_data', compact('receiveSms'));
	}


	/*
	 * Receive sms Data fetch
	 * receiveSmsData
	*/
	public function reply_sms($reply_sms_id)
	{
		$replySms = ReceiveSms::where('id',$reply_sms_id)->first();
		return view('backend.replysms', compact('replySms'));
	}

	/*
	 * Send Verification Code to Member*/
	public function sendVerificationCode($toNumber, $verificationCode)
	{
		$response = $this->twilio->message($toNumber, $verificationCode);
	}
}
