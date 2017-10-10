<?php

namespace App\Http\Controllers;

use App\Mosque;
use App\NamazTime;
use App\User;
use App\UserMosque;
use Illuminate\Http\Request;
use Plivo;
use App\SmsTemplate;

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
	    $getData = SmsTemplate::first();
        return view('backend.sms_template' , compact('getData'));
    }
    public function updateTemplate(Request $request){
    	$getData = SmsTemplate::first();
		if($getData == null){
			SmsTemplate::create(['template' => $request->sms_template]);
		}else{
			if($request->sms_template == ''){ $sms_template = ''; }else{
				$sms_template = $request->sms_template;
			}
			SmsTemplate::where('id', 1)->update(['template' => $sms_template]);
		}
	    $getData = SmsTemplate::first();
		return view('backend.sms_template' , compact('getData'));

    }

    public function smsSending(Request $request)
    {

        $mosqueData = NamazTime::where('date','=',date("Y-m-d", time()))->get();

        $getTemplate = SmsTemplate::first()->template;
        //dd($getTemplate->template);
        foreach ($mosqueData as $mosque):
            $mosqueName = Mosque::first()->name;
            $getTemplate =  str_replace("{{MosqueName}}", $mosqueName, $getTemplate);
            $getTemplate =  str_replace("{{FajarNamazTime}}", \Carbon\Carbon::parse($mosque->fajar)->format('h:i A'), $getTemplate);
            if($mosque->jumma == null){
                $getTemplate =  str_replace("{{Zuhr/Jumma}}", 'Zuhar Time', $getTemplate);
                $getTemplate =  str_replace("{{ZuharjummaTime}}", \Carbon\Carbon::parse($mosque->zuhar)->format('h:i A'), $getTemplate);
            }
            if($mosque->zuhar == null){
                $getTemplate =  str_replace("{{Zuhr/Jumma}}", 'Jumma Time', $getTemplate);
                $getTemplate =  str_replace("{{ZuharjummaTime}}", \Carbon\Carbon::parse($mosque->jumma)->format('h:i A'), $getTemplate);
            }
            $getTemplate =  str_replace("{{AsarNamazTime}}", \Carbon\Carbon::parse($mosque->asar)->format('h:i A'), $getTemplate);
            $getTemplate =  str_replace("{{MaghribNamazTime}}", \Carbon\Carbon::parse($mosque->maghrib)->format('h:i A'), $getTemplate);
            $getTemplate =  str_replace("{{IshaNamazTime}}", \Carbon\Carbon::parse($mosque->esha)->format('h:i A'), $getTemplate);

            $u_idArray = UserMosque::where('m_id' , '=' ,$mosque->m_id)->pluck('u_id');
            $this->plivoSMSCampaign($u_idArray , $getTemplate);
        endforeach;

        $request->session()->flash('send', 'SMS Send Successfully..!');
        return view('backend.sendsms');
    }



    /*
     * SMS Sending Code
     * */
    public function plivoSMSCampaign($u_idArray , $text)
    {
        $user = User::find($u_idArray);
        $userPhone = '';
        foreach ($user as $userData):
            $userPhone.=$userData->phone.'<';
        endforeach;
        $params = array(
            'src' => '+15876046444', // Sender's phone number with country code
            'dst' => $userPhone, // receiver's phone number with country code
            //'dst' => '+17802456176', // receiver's phone number with country code
            'text' => $text // Your SMS text message
        );
        $response = $this->plivo->send_message($params);
    }

}
