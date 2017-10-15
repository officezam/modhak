<?php

namespace App\Http\Controllers;

use App\Advertisement;
use App\Mosque;
use App\NamazTime;
use App\User;
use App\UserMosque;
use Illuminate\Http\Request;
use Plivo;
use App\SmsTemplate;
use App\Event;
use App\ReceiveSms;

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
    public function bulkSms()
    {
        return view('backend.bulk_sms_template');
    }
    public function eventSMS()
    {
        return view('backend.eventsendsms');
    }

    public function smsTemplate(){
        $getData = SmsTemplate::where('type','=','namaz')->first();
        return view('backend.sms_template' , compact('getData'));
    }

    public function eventsmsTemplate(){
        $getData = SmsTemplate::where('type','=','event')->first();
        return view('backend.event_sms_template' , compact('getData'));
    }

    public function updateTemplate(Request $request){
        $getData = SmsTemplate::where('type','=',$request->type)->first();
        if($getData == null){
            SmsTemplate::create(['template' => $request->sms_template , 'type' => $request->type]);
        }else{
            if($request->sms_template == ''){ $sms_template = ''; }else{
                $sms_template = $request->sms_template;
            }
            SmsTemplate::where('type' ,'=', $request->type)->update(['template' => $sms_template]);
        }
        $getData = SmsTemplate::where('type' ,'=', $request->type)->first();
        return view($request->type=='namaz'  ? 'backend.sms_template' : 'backend.event_sms_template' , compact('getData'));

    }

    public function smsSending(Request $request)
    {
        //date_default_timezone_set('Asia/Karachi');
        date_default_timezone_set('Canada/Saskatchewan');
        $mosqueData = NamazTime::where('date','=',date("Y-m-d", time()))->get();
        if(!$mosqueData->isEmpty()) {
            $getTemplate = SmsTemplate::where('type','=','namaz')->first()->template;
            foreach ($mosqueData as $mosque):
                $mosqueName = Mosque::where('id', '=' ,$mosque->m_id )->first()->name;
                $getTemplate = str_replace("{{MosqueName}}", $mosqueName, $getTemplate);
                $getTemplate = str_replace("{{FajarNamazTime}}", \Carbon\Carbon::parse($mosque->fajar)->format('h:i A'), $getTemplate);
                if ($mosque->jumma == null) {
                    $getTemplate = str_replace("{{Zuhr/Jumma}}", 'Zuhar Time', $getTemplate);
                    $getTemplate = str_replace("{{ZuharjummaTime}}", \Carbon\Carbon::parse($mosque->zuhar)->format('h:i A'), $getTemplate);
                }
                if ($mosque->zuhar == null) {
                    $getTemplate = str_replace("{{Zuhr/Jumma}}", 'Jumma Time', $getTemplate);
                    $getTemplate = str_replace("{{ZuharjummaTime}}", \Carbon\Carbon::parse($mosque->jumma)->format('h:i A'), $getTemplate);
                }
                $getTemplate = str_replace("{{AsarNamazTime}}", \Carbon\Carbon::parse($mosque->asar)->format('h:i A'), $getTemplate);
                $getTemplate = str_replace("{{MaghribNamazTime}}", \Carbon\Carbon::parse($mosque->maghrib)->format('h:i A'), $getTemplate);
                $getTemplate = str_replace("{{IshaNamazTime}}", \Carbon\Carbon::parse($mosque->esha)->format('h:i A'), $getTemplate);

                $u_idArray = UserMosque::where('m_id', '=', $mosque->m_id)->pluck('u_id');
                $this->plivoSMSCampaign($u_idArray, $getTemplate);
            endforeach;

            $request->session()->flash('send', 'SMS Send Successfully Responce True and Queu..!');
        }else{
            $request->session()->flash('empty', 'SMS Sending Fail or No Record Found..!');
        }
        return view('backend.sendsms');
    }


    public function eventSmsSending(Request $request)
    {
        //date_default_timezone_set('Asia/Karachi');
        date_default_timezone_set('Canada/Saskatchewan');
        $eventData = Event::where('date','=',date("Y-m-d", time()))->get();
        if(!$eventData->isEmpty()) {
            $getTemplate = SmsTemplate::where('type','=','event')->first()->template;
            foreach ($eventData as $event):
                $getTemplate = str_replace("{{EventName}}", $event->name, $getTemplate);
                $getTemplate = str_replace("{{EventDate}}", \Carbon\Carbon::parse($event->date)->format('l jS \\of F Y'), $getTemplate);
                $getTemplate = str_replace("{{EventTime}}", \Carbon\Carbon::parse($event->time)->format('h:i A'), $getTemplate);

                $u_idArray = UserMosque::where('m_id', '=', $event->m_id)->pluck('u_id');
                $this->plivoSMSCampaign($u_idArray, $getTemplate);
            endforeach;

            $request->session()->flash('send', 'SMS Send Successfully Responce True and Queu..!');
        }else{
            $request->session()->flash('empty', 'SMS Sending Fail or No Record Found..!');
        }
        return view('backend.sendsms');
    }



    /*
     * SMS Sending Code
     * */
    public function plivoSMSCampaign($u_idArray , $text)
    {
        $user = User::find($u_idArray);
       // $userPhone = '';
        $AddsTemplate = Advertisement::where('type','=','namaz')->get();
        $totalAddsTemplate = count($AddsTemplate);
        $NumberCount = 0;
        foreach ($user as $userData):
            //$userPhone.=$userData->phone.'<';
            if($NumberCount ==  $totalAddsTemplate){ $NumberCount = 0; }
            $text = str_replace("{{Advertisement}}", $AddsTemplate[$NumberCount]->template, $text);
            $params = array(
            'src' => '+15876046444', // Sender's phone number with country code
            'dst' => $userData->phone, // receiver's phone number with country code
            'text' => $text // Your SMS text message
        );
        //$response = $this->plivo->send_message($params);
            $NumberCount++;
        endforeach;
        // dd( $response[1]['message']);
    }



    public function receiveSms(Request $request)
    {
        //dd($request);
        // Sender's phone numer
        $from_number = $_REQUEST['From'];
        // Receiver's phone number - Plivo number
        $to_number = $_REQUEST['To'];
        // The SMS text message which was received
        $keyword = $_REQUEST['Text'];
        // Output the text which was received to the log file.
        $receiveSms = ReceiveSms::create(['from' => $from_number , 'to' => $to_number, 'keyword' => $keyword]);

        $mosqueData = Mosque::where('keyword' , 'like' , '%'.$keyword.'%')->first();

        if( $mosqueData == null){
            $params = array(
                'src' => '+15876046444', // Sender's phone number with country code
                'dst' => $from_number, // receiver's phone number with country code
                'text' => 'Related to your keyword Not Found Please Contact on that Number 7802456176' // Your SMS text message
            );
            $response = $this->plivo->send_message($params);
        }else{
            date_default_timezone_set('Canada/Saskatchewan');
            $mosque = NamazTime::where('m_id' , '=' , $mosqueData->id)->where('date','=',date("Y-m-d", time()))->first();
            if($mosque != null) {
                $getTemplate = SmsTemplate::where('type','=','namaz')->first()
                    ->template;
                $mosqueName = Mosque::where('id', '=' ,$mosqueData->id)->first();
                $mosqueName = $mosqueName->name;

                $getTemplate = str_replace("{{MosqueName}}", $mosqueName, $getTemplate);

                $getTemplate = str_replace("{{FajarNamazTime}}", \Carbon\Carbon::parse($mosque->fajar)->format('h:i A'), $getTemplate);

                if ($mosque->jumma == null) {
                    $getTemplate = str_replace("{{Zuhr/Jumma}}", 'Zuhar Time', $getTemplate);
                    $getTemplate = str_replace("{{ZuharjummaTime}}", \Carbon\Carbon::parse($mosque->zuhar)->format('h:i A'), $getTemplate);
                }
                if ($mosque->zuhar == null) {
                    $getTemplate = str_replace("{{Zuhr/Jumma}}", 'Jumma Time', $getTemplate);
                    $getTemplate = str_replace("{{ZuharjummaTime}}", \Carbon\Carbon::parse($mosque->jumma)->format('h:i A'), $getTemplate);
                }
                $getTemplate = str_replace("{{AsarNamazTime}}", \Carbon\Carbon::parse($mosque->asar)->format('h:i A'), $getTemplate);
                $getTemplate = str_replace("{{MaghribNamazTime}}", \Carbon\Carbon::parse($mosque->maghrib)->format('h:i A'), $getTemplate);
                $getTemplate = str_replace("{{IshaNamazTime}}", \Carbon\Carbon::parse($mosque->esha)->format('h:i A'), $getTemplate);

                $params = array(
                    'src' => '+15876046444', // Sender's phone number with country code
                    'dst' => $from_number, // receiver's phone number with country code
                    'text' => $getTemplate // Your SMS text message
                );
                $response = $this->plivo->send_message($params);
            }else{
                $params = array(
                    'src' => '+15876046444', // Sender's phone number with country code
                    'dst' => $from_number, // receiver's phone number with country code
                    'text' => 'No Namaz Time Registered Please Contact on that Number 7802456176' // Your SMS text message
                );
                $response = $this->plivo->send_message($params);
            }

        }

        error_log("Message received - From: $from_number, To: $to_number, Text: $keyword");
        dd('Test Done');
    }

    public function bulkSmsSending(Request $request)
    {
        $user = User::get();
        $userPhone = '';
        foreach ($user as $userData):
            $userPhone.=$userData->phone.'<';
        endforeach;
        $params = array(
            'src' => '+15876046444', // Sender's phone number with country code
            'dst' => $userPhone, // receiver's phone number with country code
            'text' => $request->sms_text // Your SMS text message
        );
        $response = $this->plivo->send_message($params);
        $request->session()->flash('send', 'SMS Send Successfully Responce True and Queu..!');
        return view('backend.bulk_sms_template');
    }


}
