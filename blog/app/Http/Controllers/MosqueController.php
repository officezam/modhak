<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Mosque;
use App\NamazTime;

class MosqueController extends Controller
{

	public function __construct() {
		$this->mosque = new Mosque();
        $this->carbon = new Carbon();
        $this->namaztime = new NamazTime();
	}

	public function index(){
	    return view('backend.add_time');
    }
	public function saveMosque(Request $request){

		$carbon = new Carbon();

		$data = [
			'mosque_name' => $request->mosque_name,
			'city' => $request->city,
			'date' => $carbon->instance(new \DateTime($request->date))->toDateTimeString(),
			'fajar_time' => $carbon->instance(new \DateTime($request->fajar_time))->toDateTimeString(),
			'zuhar_time' => $carbon->instance(new \DateTime($request->zuhar_time))->toDateTimeString(),
			'asar_time' => $carbon->instance(new \DateTime($request->asar_time))->toDateTimeString(),
			'magrib_time' => $carbon->instance(new \DateTime($request->magrib_time))->toDateTimeString(),
			'esha_time' => $carbon->instance(new \DateTime($request->esha_time))->toDateTimeString(),
		];

		$userData = Mosque::create($data);

		return view('backend.add_mosque');
	}

	public function saveNamazTime(Request $request){

        $m_id = $request->m_id;

        if(empty($m_id))
        {
            $mosqueTableData = [
                'name' => $request->m_name,
                'keyword' => $request->m_keyword,
            ];
            $mosqueData = $this->mosque->create($mosqueTableData);
            $m_id = $mosqueData->id;

            $namazTimeData = [
                'm_id' => $m_id,
                'date' => $this->carbon->instance(new \DateTime($request->namaz_date))->toDateTimeString(),
                'fajar' => $this->carbon->instance(new \DateTime($request->fajar_time))->toDateTimeString(),
                'zuhar' => $this->carbon->instance(new \DateTime($request->zuhar_time))->toDateTimeString(),
                'jumma' => $this->carbon->instance(new \DateTime($request->friday_time))->toDateTimeString(),
                'asar' => $this->carbon->instance(new \DateTime($request->asar_time))->toDateTimeString(),
                'maghrib' => $this->carbon->instance(new \DateTime($request->magrib_time))->toDateTimeString(),
                'esha' => $this->carbon->instance(new \DateTime($request->esha_time))->toDateTimeString(),
            ];
            $namazTime = $this->namaztime->create($namazTimeData);


                //$fajar = $this->carbon->subDay(new \DateTime($namazTime->fajar))->toDayDateTimeString();
                $fajar = $this->carbon->toDayDateTimeString(new \DateTime($namazTime->fajar));

                $dataArray[] = ['title' => " = Fajar Time",'start' => $fajar ];
                if(!empty($namazTime->zuhar)){
                    $zuharTime =  $this->carbon->toDayDateTimeString(new \DateTime($namazTime->zuhar));
                $dataArray[] = ['title' => " = Zhuar Time",'start' => $zuharTime ];
                }
            if(!empty($namazTime->jumma)) {

                $dataArray[] = ['title' => " = Jumma Time", 'start' => $this->carbon->toDayDateTimeString(new \DateTime($namazTime->jumma))];
            }
            $asarTime = $this->carbon->toDayDateTimeString(new \DateTime($namazTime->asar));
                $dataArray[] = ['title' => " = Asar Time",'start' => $asarTime ];
                $dataArray[] = ['title' => " = Maghrib Time",'start' => $this->carbon->toDayDateTimeString(new \DateTime($namazTime->maghrib)) ];
                $dataArray[] = ['title' => " = Esha Time",'start' => $this->carbon->toDayDateTimeString(new \DateTime($namazTime->esha)) ];
                    //\'Tue Oct 9 2017 23:30:00 GMT+0500 (Pakistan Standard Time)\'
            
            return $dataArray;

        }else{
            return 'notEmpty';

        }


	    //return $request->m_name;


    }
}
