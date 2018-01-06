<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Excel;
use App\Members;

class ExcelReadController extends Controller
{

	public function __construct()
	{
		$this->MembersModel = new Members();
	}

	public function excelReader(Request $request){
		//dd($request);
		//Excel::load(Input::file('file'), function ($reader) {});

//        Excel::selectSheets('sheet1','LIFE MEMBERS')->load();
//        $result = Excel::load(public_path().'/csvFiles/600USALeadsRoyharber.xlsx', function($reader){})->get();
		$membertype_id = $request->membertype_id;
		$path = $request->file('sheet')->getRealPath();

		$result = Excel::load($path, function($reader){})->get();
		$result->each(function($row )
		{
			$number = str_replace('-', '',$row->phonenumber);
			$number = '+1'.$number;
			$result = $this->MembersModel->where('phone',$number)->first();
			if($result == null && $row->phonenumber != ''){
				$data = [
					'membertype_id' => 'Excel',
					'name' => $row->firstname.' '.$row->lastname,
					'first_name' => $row->firstname,
					'last_name' => $row->lastname,
					'address' => $row->address,
					'city' => $row->city,
					'state' => $row->provincestate,
					'country' => $row->country,
					'zip_code' => $row->zippostal_code,
					'phone' => $number,
					'email' => $row->email,
					'type' => 'Excell',
				];
				$this->MembersModel->create($data);
			}
		});
		$this->MembersModel->where('membertype_id' , 'Excel')->update(['membertype_id' => $request->membertype_id]);
		return redirect()->route('excel-members-data');
		echo '<h1> Updated Record </h1>';
	}


	public function excelCeater(){

		$data = $this->MembersModel->get();
		$EmailArr = array();

		foreach ($data as $value):
			$EmailArr[] = array($value->email);
		endforeach;

		$data = $EmailArr;

		Excel::create('Filename', function($excel) use($data) {

			$excel->sheet('Sheetname', function($sheet) use($data) {
				$sheet->fromArray($data);
			});

		})->export('csv');
		dd($EmailArr);
		$result = Excel::load(public_path().'/csvFiles/Outlook.csv', function($reader){})->get();
		$result->each(function($row)
		{
			$sheetData = $row->email;
			if (strpos($sheetData, '@') !== false) {
				$EmailArr[] = [$sheetData];
				$data = [
					'first_name' => 'amir',
					'last_name' => 'Shahzad',
					'address' => '123',
					'phone' => '12334',
					'email' => $sheetData,
				];
				$this->MembersModel->create($data);
			}else{
				$ExtraDataArr[] = [$sheetData];
			}
		});
	}

}
