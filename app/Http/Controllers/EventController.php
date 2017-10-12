<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Event;
use App\Mosque;

class EventController extends Controller
{

    public function eventsRecord(){
        $events = Event::get();
        return view('backend.event_data' , compact('events'));
    }



    /*
     * Event Add Form Display
     * */
    public function eventAdd(){
        $mosque = Mosque::get();
        return view('backend.add_event' , compact('mosque'));
    }

    /*
    * Function Get All Data For Specific Event
    * Full Year Wise Data Fetch
    * */
    public function getEventTime($event_id)
    {
        dd($event_id);
        $namazData  = $this->namaztime->where('m_id',$namaz_id)->get();
        $mosqueData = $this->mosque->find($namaz_id);
        $dataArray = [];
        foreach ($namazData as $namazTime)
        {
            $dataArray[] = ['title' => " = Fajar Time",'start' => Carbon::parse($namazTime->fajar)->format('c') ];
            if($namazTime->zuhar != null)
            {
                $dataArray[] = ['title' => " = Zhuar Time",'start' =>  Carbon::parse($namazTime->zuhar)->format('c') ];
            }
            if($namazTime->jumma != null){
                $dataArray[] = ['title' => " = Jumma Time", 'start' => Carbon::parse($namazTime->jumma)->format('c')];
            }
            $dataArray[] = ['title' => " = Asar Time",'start'       => Carbon::parse($namazTime->asar)->format('c') ];
            $dataArray[] = ['title' => " = Maghrib Time",'start'    =>Carbon::parse($namazTime->maghrib)->format('c') ];
            $dataArray[] = ['title' => " = Esha Time",'start'       => Carbon::parse($namazTime->esha)->format('c') ];
        }
        return view('backend.update_time', compact('mosqueData','dataArray'));
    }


    /*
     * Delete Event Record From Event table
     * Delete Event Record
     * */
    public function deleteEventData($id, Request $request)
    {
        $this->mosque->where('id', '=', $id)->delete();
        $this->namaztime->where('m_id', '=', $id)->delete();
        $request->session()->flash('success', 'Delete Record and Namaz Time..!');
        return redirect()->route('mosque_record');
    }

}
