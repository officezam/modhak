<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Mosque;
use App\NamazTime;
use phpDocumentor\Reflection\Types\Null_;

class MosqueController extends Controller
{

	public function __construct() {
		$this->mosque = new Mosque();
        $this->carbon = new Carbon();
        $this->namaztime = new NamazTime();
	}

	/*
	 * Show Add Mosque Time Callendar
	 * */
	public function index(){
	    return view('backend.add_time');
    }

    /*
     * Show All Mosque Data
     * */
    public function mosqueRecord(){
    	$mosqueData = $this->mosque->get();
	    return view('backend.mosque_data',compact('mosqueData'));
    }

    /*
     * Function Save Nw=ew Mosque Data OR
     * Update Exiting Data OR
     * Save New Data For Existing Mosque
     * */
	public function saveNamazTime(Request $request){
//dd($request);
        $m_id = $request->m_id;

        if(empty($m_id))
        {
            $mosqueTableData = [
                'u_id' => '1',
                'name' => $request->m_name,
                'keyword' => $request->m_keyword,
            ];
            $mosqueData = $this->mosque->create($mosqueTableData);
            $m_id = $mosqueData->id;
            if(!empty($request->zuhar_time)){ $zuhar_time = date('Y-m-d H:i:s', strtotime("$request->namaz_date $request->zuhar_time")); }else{ $zuhar_time = null ;}
            if(!empty($request->friday_time)){ $jumma_time = date('Y-m-d H:i:s', strtotime("$request->namaz_date $request->friday_time")); }else{ $jumma_time = null;}
            $namazTimeData = [
                'm_id' => $m_id,
                'date' => $request->namaz_date,
                'fajar' => date('Y-m-d H:i:s', strtotime("$request->namaz_date $request->fajar_time")),
                'zuhar' => $zuhar_time,
                'jumma' => $jumma_time,
                'asar' => date('Y-m-d H:i:s', strtotime("$request->namaz_date $request->asar_time")),
                'maghrib' => date('Y-m-d H:i:s', strtotime("$request->namaz_date $request->magrib_time")),
                'esha' => date('Y-m-d H:i:s', strtotime("$request->namaz_date $request->esha_time")),
            ];
            $namazTime = $this->namaztime->create($namazTimeData);
            return $m_id;
//                $dataArray[] = ['title' => " = Fajar Time",'start' => $namazTime->fajar ];
//            if(!empty($namazTime->zuhar)){
//                $dataArray[] = ['title' => " = Zhuar Time",'start' => $namazTime->zuhar ];
//            }else{
//                $dataArray[] = ['title' => " = Jumma Time", 'start' => $namazTime->jumma];
//            }
//                $dataArray[] = ['title' => " = Asar Time",'start'       => $namazTime->asar ];
//                $dataArray[] = ['title' => " = Maghrib Time",'start'    => $namazTime->maghrib ];
//                $dataArray[] = ['title' => " = Esha Time",'start'       => $namazTime->esha ];
//            return $dataArray;

        }else{
           $updateData =  $this->namaztime->where('m_id', $m_id)->where('date', '=', $request->namaz_date)->first();

           if($updateData !=null ){

	           if($request->zuhar_time){ $zuhar_time = date('Y-m-d H:i:s', strtotime("$request->namaz_date $request->zuhar_time")); }else{ $zuhar_time = null ;}
	           if($request->friday_time){ $jumma_time = date('Y-m-d H:i:s', strtotime("$request->namaz_date $request->friday_time")); }else{ $jumma_time = null;}
	           $namazTimeData = [
		           'fajar' => date('Y-m-d H:i:s', strtotime("$request->namaz_date $request->fajar_time")),
		           'zuhar' => $zuhar_time,
		           'jumma' => $jumma_time,
		           'asar' => date('Y-m-d H:i:s', strtotime("$request->namaz_date $request->asar_time")),
		           'maghrib' => date('Y-m-d H:i:s', strtotime("$request->namaz_date $request->magrib_time")),
		           'esha' => date('Y-m-d H:i:s', strtotime("$request->namaz_date $request->esha_time")),
	           ];
	           $namazTime = $this->namaztime->where('id', $updateData->id)
	                                        ->update($namazTimeData);
	           return $m_id;
           }else{
	           if(!empty($request->zuhar_time)){ $zuhar_time = date('Y-m-d H:i:s', strtotime("$request->namaz_date $request->zuhar_time")); }else{ $zuhar_time = null ;}
	           if(!empty($request->friday_time)){ $jumma_time = date('Y-m-d H:i:s', strtotime("$request->namaz_date $request->friday_time")); }else{ $jumma_time = null;}
	           $namazTimeData = [
		           'm_id' => $m_id,
		           'date' => $request->namaz_date,
		           'fajar' => date('Y-m-d H:i:s', strtotime("$request->namaz_date $request->fajar_time")),
		           'zuhar' => $zuhar_time,
		           'jumma' => $jumma_time,
		           'asar' => date('Y-m-d H:i:s', strtotime("$request->namaz_date $request->asar_time")),
		           'maghrib' => date('Y-m-d H:i:s', strtotime("$request->namaz_date $request->magrib_time")),
		           'esha' => date('Y-m-d H:i:s', strtotime("$request->namaz_date $request->esha_time")),
	           ];
	           $namazTime = $this->namaztime->create($namazTimeData);
	           return $m_id;

           }

        }

    }

    /*
     * Function Get All Data For Specific Mosque
     * Full Year Wise Data Fetch
     * */
    public function getNamazTime($namaz_id)
    {
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





}
