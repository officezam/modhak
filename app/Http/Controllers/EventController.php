<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Event;
use App\Mosque;
use Carbon\Carbon;
use Auth;
class EventController extends Controller
{

	public function __construct() {
		$this->event  = new Event();
		$this->mosque = new Mosque();
	}

	public function eventsRecord(){
        if(Auth::user()->type == 'admin'){
            $events = $this->event->get();
        }else{
            $events = $this->event->where('u_id',Auth::user()->id)->get();
        }
        return view('backend.event_data' , compact('events'));
    }



    /*
     * Event Add Form Display
     * */
    public function eventAdd(){
        if(Auth::user()->type == 'admin'){
            $mosque = $this->mosque->get();
        }else{
            $mosque = $this->mosque->where('u_id',Auth::user()->id)->get();
        }
        return view('backend.add_event' , compact('mosque'));
    }

    /*
    * Function Get All Data For Specific Event
    * Full Year Wise Data Fetch
    * */
    public function getEventTime($m_id)
    {
	    $mosque = $this->mosque->get();
	    $eventData  = $this->event->where('m_id',$m_id)->get();
        $dataArray = [];
        foreach ($eventData as $eventTime)
        {
            $dataArray[] = ['title' => " = $eventTime->name",'start' => Carbon::parse($eventTime->time)->format('c') ];
        }
        return view('backend.update_event', compact('mosque','m_id','dataArray'));
    }




	/*
 * Function Save Nw=ew Mosque Data OR
 * Update Exiting Data OR
 * Save New Data For Existing Mosque
 * */
	public function saveEventTime(Request $request){

		$m_id = $request->m_id;

		if(empty($m_id))
		{
			$eventTableData = [
				'u_id' => Auth::user()->id,
				'm_id' => $m_id,
				'name' => $request->name,
				'date' => date('Y-m-d H:i:s', strtotime("$request->date")),
				'time' => date('Y-m-d H:i:s', strtotime("$request->date $request->time")),
			];

			$eventData = $this->event->create($eventTableData);
			//$e_id = $eventData->id;
			return $m_id;
		}else{
			$updateData =  $this->event->where('m_id', $m_id)->where('date', '=', $request->date)->first();
			if($updateData !=null ){
				$eventTableData = [
					'u_id' => Auth::user()->id,
					'm_id' => $m_id,
					'name' => $request->name,
					'date' => date('Y-m-d H:i:s', strtotime("$request->date")),
					'time' => date('Y-m-d H:i:s', strtotime("$request->date $request->time")),
				];
				 $this->event->where('id', $updateData->id)
				                             ->update($eventTableData);
				return $m_id;
			}else{
				$eventTableData = [
					'u_id' => Auth::user()->id,
					'm_id' => $m_id,
					'name' => $request->name,
					'date' => date('Y-m-d H:i:s', strtotime("$request->date")),
					'time' => date('Y-m-d H:i:s', strtotime("$request->date $request->time")),
				];
				$eventData = $this->event->create($eventTableData);
				//$e_id = $eventData->id;
				return $m_id;
			}

		}

	}


	/*
	 * Delete Event Record From Event table
	 * Delete Event Record
	 * */
    public function deleteEventData($id, Request $request)
    {
	    $this->event->where('id', '=', $id)->delete();
        $request->session()->flash('success', 'Event Deleted Successfully..!');
        return redirect()->route('events_record');
    }

}
