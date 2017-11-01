<?php

namespace App\Http\Controllers;

use App\Advertisement;
use App\Mosque;
use App\NamazTime;
use App\Subscriber;
use App\User;
use App\UserMosque;
use Illuminate\Http\Request;
use Plivo;
use App\SmsTemplate;
use App\Event;
use App\ReceiveSms;
use Auth;
use App\ExcelModel;

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
        if(Auth::user()->type == 'admin'){
            $mosqueData = NamazTime::where('date','=',date("Y-m-d", time()))->get();
        }else{
            $m_id = Mosque::where('u_id',Auth::user()->id)->pluck('id');
            $mosqueData = NamazTime::where('date','=',date("Y-m-d", time()))->whereIn('m_id', $m_id)->get();
        }

        if(!$mosqueData->isEmpty()) {
            $getTemplate = SmsTemplate::where('type','=','namaz')->first()->template;
            foreach ($mosqueData as $mosque):
                $mosqueName = Mosque::where('id', '=' ,$mosque->m_id )->first()->name;
                $getTemplate = str_replace("{{MosqueName}}", $mosqueName, $getTemplate);
                $getTemplate = str_replace("{{FajarNamazTime}}", \Carbon\Carbon::parse($mosque->fajar)->format('h:i A'), $getTemplate);
                if ($mosque->jumma == null) {
                    $getTemplate = str_replace("{{Zuhr/Jumma}}", 'Zuhr', $getTemplate);
                    $getTemplate = str_replace("{{ZuharjummaTime}}", \Carbon\Carbon::parse($mosque->zuhar)->format('h:i A'), $getTemplate);
                }
                if ($mosque->zuhar == null) {
                    $getTemplate = str_replace("{{Zuhr/Jumma}}", 'Jumma Time', $getTemplate);
                    $getTemplate = str_replace("{{ZuharjummaTime}}", \Carbon\Carbon::parse($mosque->jumma)->format('h:i A'), $getTemplate);
                }
                $getTemplate = str_replace("{{AsarNamazTime}}", \Carbon\Carbon::parse($mosque->asar)->format('h:i A'), $getTemplate);
                $getTemplate = str_replace("{{MaghribNamazTime}}", \Carbon\Carbon::parse($mosque->maghrib)->format('h:i A'), $getTemplate);
                $getTemplate = str_replace("{{IshaNamazTime}}", \Carbon\Carbon::parse($mosque->esha)->format('h:i A'), $getTemplate);

                $u_idArray = Subscriber::where('m_id', '=', $mosque->m_id)->get();
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
        if(Auth::user()->type == 'admin'){
            $eventData = Event::where('date','=',date("Y-m-d", time()))->get();
        }else{
            $eventData = Event::where('date','=',date("Y-m-d", time()))->where('u_id',Auth::user()->id)->get();
        }

        if(!$eventData->isEmpty()) {
            $getTemplate = SmsTemplate::where('type','=','event')->first()->template;
            foreach ($eventData as $event):
                $getTemplate = str_replace("{{EventName}}", $event->name, $getTemplate);
                $getTemplate = str_replace("{{EventDate}}", \Carbon\Carbon::parse($event->date)->format('l jS \\of F Y'), $getTemplate);
                $getTemplate = str_replace("{{EventTime}}", \Carbon\Carbon::parse($event->time)->format('h:i A'), $getTemplate);

                $u_idArray = Subscriber::where('m_id', '=', $event->m_id)->get();
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
        $AddsTemplate = Advertisement::where('type','=','namaz')->get();
        $totalAddsTemplate = count($AddsTemplate);
        $NumberCount = 0;
        foreach ($u_idArray as $userData):
            //$userPhone.=$userData->phone.'<';
            if($NumberCount ==  $totalAddsTemplate){ $NumberCount = 0; }
            if(count($AddsTemplate) > 0){
                $sms = str_replace("{{Sponsor}}", $AddsTemplate[$NumberCount]->template, $text);
            }else{
                $sms = str_replace("{{Sponsor}}", '', $text);
            }
            $params = array(
                'src' => '+15876046444', // Sender's phone number with country code
                'dst' => $userData->phone, // receiver's phone number with country code
                'text' => $sms // Your SMS text message
            );
            $response = $this->plivo->send_message($params);
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

//        $c = 10293;
        $Number = trim(str_replace('-','',$keyword));
        $Number = trim(str_replace('+','',$Number));
        $Number = trim(str_replace('(','',$Number));
        $Number = trim(str_replace(')','',$Number));


        if(is_numeric($Number)){
            $result = ExcelModel::where('phone' , '=' , $Number)->get();
            if(count($result) > 0) {
                $text = "Dear Member,\n\n";
                $text .= "You are PCAE registered member.\n\n";
                $text .= "Details as:\n\n";
                foreach ($result as $member):
                    $text .= strtoupper($member->first_name . ' ' . $member->last_name) . "\n\n";
                endforeach;
                $text .= "If you don't find your record email\n to pcaedmonton1@gmail.com\n\n";
                $text .= "Developed by.\n";
                $text .= "Ghazanfar Rehman.\n";
                //$text .= ("Dear Member, \n ".$result->first_name.' '.$result->last_name."\n".$result->address."\n".$result->email);
            }else{
                $text = "Dear Member,\n\n";
                $text .= "We don't find your record please send email\n to pcaedmonton1@gmail.com\n\n";
            }
//            dd($text);
            $params = array(
                'src' => $to_number, // Sender's phone number with country code
                'dst' => $from_number, // receiver's phone number with country code
                'text' => $text // Your SMS text message
            );
            $response = $this->plivo->send_message($params);
            exit;
        }

        $mosqueData = Mosque::where('keyword' , 'like' , '%'.$keyword.'%')->first();

        if( $mosqueData == null){
            $params = array(
                'src' => $to_number, // Sender's phone number with country code
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

                $AddsCout =  count(Advertisement::get());
                if($AddsCout > 0){
                    $AddsTemplate = Advertisement::where('sendingstatus','!=',1)->first();
                    if($AddsTemplate==null){
                        Advertisement::where('sendingstatus' ,'=' , 1)->update(['sendingstatus' => 0]);
                        $AddsTemplate = Advertisement::where('sendingstatus','!=',1)->first();
                    }
                    $sms = str_replace("{{Sponsor}}", $AddsTemplate->template, $getTemplate);
                    Advertisement::where('id',$AddsTemplate->id)->update(['sendingstatus' => 1]);

                }else{
                    $sms = str_replace("{{Sponsor}}", '', $getTemplate);
                }
                $params = array(
                    'src' => $to_number, // Sender's phone number with country code
                    'dst' => $from_number, // receiver's phone number with country code
                    'text' => $sms // Your SMS text message
                );
                $response = $this->plivo->send_message($params);
            }else{
                $params = array(
                    'src' => $to_number, // Sender's phone number with country code
                    'dst' => $from_number, // receiver's phone number with country code
                    'text' => 'No Namaz Time Registered Please Contact on that Number 7802456176' // Your SMS text message
                );
                $response = $this->plivo->send_message($params);
            }

        }

        //error_log("Message received - From: $from_number, To: $to_number, Text: $keyword");
        //dd('Test Done');
    }

    public function bulkSmsSending(Request $request)
    {
        if(Auth::user()->type == 'admin'){
            $user = Subscriber::get();
        }else{
            $user = Subscriber::where('u_id',Auth::user()->id)->get();
        }

        $AddsTemplate = Advertisement::where('type','=','namaz')->get();
        $totalAddsTemplate = count($AddsTemplate);
        $NumberCount = 0;

        foreach ($user as $useData):
            if($NumberCount ==  $totalAddsTemplate){ $NumberCount = 0; }
            if(count($AddsTemplate) > 0){
                $sms = str_replace("{{Sponsor}}", $AddsTemplate[$NumberCount]->template, $request->sms_text);
            }else{
                $sms = str_replace("{{Sponsor}}", '', $request->sms_text);
            }
            $params = array(
                'src' => '+15876046444', // Sender's phone number with country code
                'dst' => $useData->phone, // receiver's phone number with country code
                'text' => $sms // Your SMS text message
            );
            $response = $this->plivo->send_message($params);
            $NumberCount++;
        endforeach;

//        $userPhone = '';
//        foreach ($user as $userData):
//            $userPhone.=$userData->phone.'<';
//        endforeach;
//        $params = array(
//            'src' => '+15876046444', // Sender's phone number with country code
//            'dst' => $userPhone, // receiver's phone number with country code
//            'text' => $request->sms_text // Your SMS text message
//        );
//        $response = $this->plivo->send_message($params);
        $request->session()->flash('send', 'SMS Send Successfully Responce True and Queu..!');
        return view('backend.bulk_sms_template');
    }


}
