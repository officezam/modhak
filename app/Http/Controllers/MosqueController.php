<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Mosque;
use App\NamazTime;
use Illuminate\Support\Facades\Auth;

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
        if(\Auth::user()){
            if(Auth::user()->type == 'admin'){
                $mosqueData = $this->mosque->get();
            }else{
                $mosqueData = $this->mosque->where('u_id',Auth::user()->id)->get();
            }
            return view('backend.mosque_data',compact('mosqueData'));
        }else{
            return redirect()->route('login');
        }

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
                'u_id' => Auth::user()->id,
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


    /*
     * Delete Mosque Record From Mosque table
     * Delete Mosque Record From Namaz Time
     * */
    public function deleteMosqueData($id, Request $request)
    {
        $this->mosque->where('id', '=', $id)->delete();
        $this->namaztime->where('m_id', '=', $id)->delete();
        $request->session()->flash('success', 'Delete Record and Namaz Time..!');
        return redirect()->route('mosque_record');
    }
//    public function saveMosque(Request $request){
//
//        $carbon = new Carbon();
//
//        $data = [
//            'mosque_name' => $request->mosque_name,
//            'city' => $request->city,
//            'date' => $carbon->instance(new \DateTime($request->date))->toDateTimeString(),
//            'fajar_time' => $carbon->instance(new \DateTime($request->fajar_time))->toDateTimeString(),
//            'zuhar_time' => $carbon->instance(new \DateTime($request->zuhar_time))->toDateTimeString(),
//            'asar_time' => $carbon->instance(new \DateTime($request->asar_time))->toDateTimeString(),
//            'magrib_time' => $carbon->instance(new \DateTime($request->magrib_time))->toDateTimeString(),
//            'esha_time' => $carbon->instance(new \DateTime($request->esha_time))->toDateTimeString(),
//        ];
//
//        $userData = Mosque::create($data);
//
//        return view('backend.add_mosque');
//    }


    public function copyMosque(Request $request){

        $copiedMosqueID = $request->m_id;
        $mosqueTableData = [
            'u_id' => Auth::user()->id,
            'name' => $request->mosque_mame,
            'keyword' => $request->mosque_keyword,
        ];
        $mosqueData = $this->mosque->create($mosqueTableData);
        $m_id = $mosqueData->id;
        $copyData = $this->namaztime->where('m_id' ,$copiedMosqueID)->get();
        foreach ($copyData as $namazTime)
        {
            $namazTimeData = [
                'm_id' => $m_id,
                'date' => $namazTime->date,
                'fajar' =>  $namazTime->fajar,
                'zuhar' => $namazTime->zuhar,
                'jumma' => $namazTime->jumma,
                'asar' =>  $namazTime->asar,
                'maghrib' => $namazTime->maghrib,
                'esha' =>  $namazTime->esha,
            ];
            $namazTime = $this->namaztime->create($namazTimeData);
        }
        return redirect()->route('mosque_record');
    }



}
