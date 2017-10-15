<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Advertisement;

class AddsController extends Controller
{


    public function smsTemplate(){
        $getData = Advertisement::where('type','=','namaz')->get();
        return view('backend.adds_data' , compact('getData'));
    }

    public function addNewAddsTemlate(){
        return view('backend.add_sms_template');
    }

    public function editTemplateData($id){
        $getData = Advertisement::where('id','=',$id)->first();
        return view('backend.add_sms_template' , compact('getData'));
    }

    public function saveNewAddsTemlate(Request $request){
        //$getData = Advertisement::where('id','=',$request->id)->first();
        if($request->id == null){
            Advertisement::create(['template' => $request->template,'name' => $request->mame, 'type' => $request->type]);
        }else{
            if($request->template == ''){ $template = ''; }else{  $template = $request->template;  }
            Advertisement::where('id' ,'=', $request->id)->update(['template' => $request->template,'name' => $request->mame, 'type' => $request->type]);
        }
        $getData = Advertisement::where('type','=','namaz')->get();
        return view($request->type=='namaz'  ? 'backend.adds_data' : 'backend.adds_data' , compact('getData'));
    }

    /*
     * Delete Addvertisement Record From Advertisement table
     * */
    public function deletTemplateData($id, Request $request)
    {
        Advertisement::where('id', '=', $id)->delete();
        $request->session()->flash('success', 'Delete User Record Successfully..!');
        return redirect()->route('adds-data');
    }

}
