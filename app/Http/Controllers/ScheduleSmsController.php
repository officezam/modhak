<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ScheduleSms;
use App\Memberstype;
use Carbon\Carbon;
use App\Http\Controllers\SmsSendTwilioController;

class ScheduleSmsController extends Controller
{

	public function __construct() {
		$this->ScheduleSms = new ScheduleSms();
		$this->smsSend     = new SmsSendTwilioController();
	}

	/*
	 * showSchedule SMS Show
	 * */
	public function showSchedule()
	{
		$scheduleSms = $this->ScheduleSms->get();
		return view('backend.schedule_sms' , compact('scheduleSms'));
	}

	/*
	 * Add Schedule sms page
	 * */
	public function addScheduleSms()
	{
		$meberType = Memberstype::get();
		return view('backend.add_schedulesms', compact('meberType'));
	}

	/*
	 * Schedule SMS Save
	 * */
	public function saveScheduleSms(Request $request)
	{
		$combinedDT = date('Y-m-d H:i:s', strtotime("$request->date $request->time"));
		$this->ScheduleSms->create([
			'membertype_id' => $request->membertype_id,
			'type' => 'once',//$request->type,
			'sms' => $request->sms_text,
			'dateandtime' => $combinedDT,
			'status' => 'Active'
		]);
		return redirect(route('schedule_sms'));
	}

	/*
	 * Schedule Sms Delete
	 * */
	public function deletSchedule($schedule_id)
	{
		$this->ScheduleSms->where('id', $schedule_id)->delete();
		return redirect(route('schedule_sms'));
	}

	/*
	 * Schedule SMS Campaign
	 * Daily SMS Sending Method
	 * */
	public function scheduleSMSDailySnding()
	{
		$currentDateTime = \Carbon\Carbon::now();
		$fromDate = $currentDateTime->toDateTimeString();
		$toDate = $currentDateTime->addMinutes(1)->toDateTimeString();

		$scheduleSms = $this->ScheduleSms->where('status','Active')
			->whereBetween('dateandtime', [$fromDate,$toDate])
//			->where('dateandtime', '>' , $fromDate)
//			->where('dateandtime','<' ,$toDate)
            ->get();

		foreach ($scheduleSms as $schdeule)
		{
			$this->smsSend->scheduleSms($schdeule->membertype_id,$schdeule->sms);
			$nextDate =  \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $schdeule->dateandtime)->addDay();
			$this->ScheduleSms->where('id',$schdeule->id)->update(['dateandtime' => $nextDate]);
		}
	}

	/*
	 * Schedule SMS Campaign
	 * weekly SMS Sending Method
	 * */
	public function scheduleSMSWeeklySnding()
	{
		$currentDateTime = \Carbon\Carbon::now();
		$fromDate = $currentDateTime->toDateTimeString();
		$toDate = $currentDateTime->addMinutes(1)->toDateTimeString();

		$scheduleSms = $this->ScheduleSms->where('status','Active')
			->whereBetween('dateandtime', [$fromDate,$toDate])
//			->where('dateandtime', '>' , $fromDate)
//			->where('dateandtime','<' ,$toDate)
            ->get();

		foreach ($scheduleSms as $schdeule)
		{
			$this->smsSend->scheduleSms($schdeule->membertype_id,$schdeule->sms);
			$nextDate =  \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $schdeule->dateandtime)->addWeekdays(4);
			$this->ScheduleSms->where('id',$schdeule->id)->update(['dateandtime' => $nextDate]);
		}
	}

	/*
	* Schedule SMS Campaign
	* weekly SMS Sending Method
	* */
	public function scheduleSMSMonthlySnding()
	{
		$currentDateTime = \Carbon\Carbon::now();
		$fromDate = $currentDateTime->toDateTimeString();
		$toDate = $currentDateTime->addMinutes(1)->toDateTimeString();

		$scheduleSms = $this->ScheduleSms->where('status','Active')
			->whereBetween('dateandtime', [$fromDate,$toDate])
//			->where('dateandtime', '>' , $fromDate)
//			->where('dateandtime','<' ,$toDate)
            ->get();

		foreach ($scheduleSms as $schdeule)
		{
			$this->smsSend->scheduleSms($schdeule->membertype_id,$schdeule->sms);
			$nextDate =  \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $schdeule->dateandtime)->addMonth();
			$this->ScheduleSms->where('id',$schdeule->id)->update(['dateandtime' => $nextDate]);
		}
	}

	/*
	* Schedule SMS Campaign
	* weekly SMS Sending Method
	* */
	public function scheduleSMSOnceSnding()
	{
		$currentDateTime = \Carbon\Carbon::now();
		$fromDate = $currentDateTime->toDateTimeString();
		$toDate = $currentDateTime->addMinutes(1)->toDateTimeString();

		$scheduleSms = $this->ScheduleSms->where('status','Active')
		     ->whereBetween('dateandtime', [$fromDate,$toDate])
//			->where('dateandtime', '>' , $fromDate)
//			->where('dateandtime','<' ,$toDate)
             ->get();

		foreach ($scheduleSms as $schdeule)
		{
			$this->smsSend->scheduleSms($schdeule->membertype_id,$schdeule->sms);
//			$nextDate =  \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $schdeule->dateandtime)->addWeekday();
			$this->ScheduleSms->where('id',$schdeule->id)->update(['status' => 'Sent']);
		}
		return 'success';
	}






}
