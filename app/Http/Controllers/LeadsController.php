<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Leads;
use App\Leadsdetail;

class LeadsController extends Controller
{

	public function __construct() {

		$this->Leads = new Leads();
		$this->Leadsdetail = new Leadsdetail();
	}

	/*
	 * Show Leads Table Record
	 * */
	public function index()
	{
		$leads = $this->Leads->get();
		return view('backend.leads_data', compact('leads'));
	}

	/*
	 * Add Leads Page
	 * */
	public function addLead()
	{
		return view('backend.add_lead');
	}


	/*
	 * Save Lead and Questions
	 * */
	public function saveLead(Request $request)
	{
		$leadSaved = $this->Leads->create([
			'name' => $request->name,
			'description' => $request->description
		]);
		$id = $leadSaved->id;
		if(isset($request->question)){
			foreach($request->question as $key => $value)
			{
				$this->Leadsdetail->create([
					'leads_id' => $id,
					'question' => $request->question[$key],
					'answer' => $request->answer[$key],
					'static_reply' => $request->static_reply[$key]
				]);
			}
		}
		return redirect(route('leadsmanagement'));
	}

	/*
	 * Delete Lead Record
	 * */
	public function deleteLead($lead_id)
	{
		$this->Leadsdetail->where('leads_id',$lead_id)->delete();
		$this->Leads->find($lead_id)->delete();
		return redirect(route('leadsmanagement'));
	}

	/*
	 * Leads Question Detail
	 * */
	public function leadsQuestiondata($lead_id)
	{
		$leadsDetail = $this->Leadsdetail->where('leads_id',$lead_id)->get();
		$leads       = $this->Leads->find($lead_id);
		return view('backend.leadsquestion', compact('leads', 'leadsDetail'));
	}

	/*
	 * Delete Question Record
	 * */
	public function deleteQuestion($question_id)
	{
		$this->Leadsdetail->find($question_id)->delete();
		return redirect(route('leadsmanagement'));
	}



}
