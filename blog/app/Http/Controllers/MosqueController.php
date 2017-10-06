<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Mosque;

class MosqueController extends Controller
{

	public function __construct() {
		$mosque = new Mosque();
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
